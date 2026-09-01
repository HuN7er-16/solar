<?php

namespace ExpertInitialVisit\Http\Controllers;

use Behin\Sms\Controllers\SmsController;
use ExpertInitialVisit\Models\ExpertInitialVisit;
use ExpertInitialVisit\Models\ExpertVisitEquipmentItem;
use ExpertInitialVisit\Models\ExpertVisitPhoto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use SolarPlantRequests\Models\SolarPlantRequest;

class ExpertInitialVisitController
{
    // ----------------------------------------------------------------
    // helpers
    // ----------------------------------------------------------------

    private function isLeader(): bool
    {
        $ids = collect(config('expert-initial-visit.roles.leader', [1]))
            ->map(fn ($id) => (string) $id)->all();
        return in_array((string) Auth::user()?->role_id, $ids, true);
    }

    private function isExpert(): bool
    {
        $roleId = config('expert-initial-visit.roles.expert');
        if (! $roleId) return false;
        return (string) Auth::user()?->role_id === (string) $roleId;
    }

    private function canAccess(): bool
    {
        return $this->isLeader() || $this->isExpert();
    }

    // ----------------------------------------------------------------
    // لیست فرم‌های بازدید (کارشناس: فقط خودش — راهبر: همه)
    // ----------------------------------------------------------------

    public function index(): View
    {
        abort_unless($this->canAccess(), 403);

        $query = ExpertInitialVisit::query()
            ->with(['request', 'expert'])
            ->latest();

        if (! $this->isLeader()) {
            $query->where('expert_user_id', Auth::id());
        }

        $visits = $query->paginate(15);

        return view('expert-initial-visit::visits.index', compact('visits'));
    }

    // ----------------------------------------------------------------
    // فرم ثبت بازدید جدید
    // ----------------------------------------------------------------

    public function create(Request $request): View
    {
        abort_unless($this->canAccess(), 403);

        // پیدا کردن تقاضا
        $solarPlantRequest = SolarPlantRequest::query()
            ->findOrFail($request->query('request_id'));

        // کارشناس فقط به تقاضای اختصاص‌یافته خودش دسترسی دارد
        if (! $this->isLeader()) {
            abort_unless(
                $solarPlantRequest->expert_user_id === Auth::id(),
                403,
                'این تقاضا به شما اختصاص داده نشده است.'
            );
        }

        // بررسی اینکه قبلاً فرم ثبت نشده باشد
        $existing = ExpertInitialVisit::query()
            ->where('solar_plant_request_id', $solarPlantRequest->id)
            ->first();

        if ($existing) {
            return redirect()
                ->route('expert-initial-visit.show', $existing)
                ->with('info', 'فرم بازدید اولیه قبلاً برای این تقاضا ثبت شده است.');
        }

        return view('expert-initial-visit::visits.create', compact('solarPlantRequest'));
    }

    // ----------------------------------------------------------------
    // ذخیره فرم بازدید
    // ----------------------------------------------------------------

    public function store(Request $request): RedirectResponse
    {
        abort_unless($this->canAccess(), 403);

        $solarPlantRequest = SolarPlantRequest::query()->findOrFail($request->input('solar_plant_request_id'));

        if (! $this->isLeader()) {
            abort_unless(
                $solarPlantRequest->expert_user_id === Auth::id(),
                403
            );
        }

        // بررسی تکراری نبودن
        abort_if(
            ExpertInitialVisit::query()
                ->where('solar_plant_request_id', $solarPlantRequest->id)
                ->exists(),
            422,
            'فرم بازدید اولیه قبلاً برای این تقاضا ثبت شده است.'
        );

        $validated = $this->validateForm($request);

        // اگر نتیجه «عدم امکان اجرا» باشد، دلیل الزامی است
        if ($validated['assessment_result'] === ExpertInitialVisit::RESULT_NOT_FEASIBLE) {
            $request->validate([
                'not_feasible_reason' => ['required', 'string', 'min:10'],
            ], [
                'not_feasible_reason.required' => 'در صورت عدم امکان اجرا، ذکر دلیل الزامی است.',
                'not_feasible_reason.min'      => 'دلیل باید حداقل ۱۰ کاراکتر باشد.',
            ]);
        }

        DB::transaction(function () use ($validated, $request, $solarPlantRequest) {
            // ساخت فرم اصلی
            $visit = ExpertInitialVisit::query()->create([
                ...$validated,
                'solar_plant_request_id' => $solarPlantRequest->id,
                'expert_user_id'         => Auth::id(),
                'report_status'          => 'submitted',
                'submitted_at'           => now(),
            ]);

            // ذخیره تجهیزات بار اضطراری (جدول پویا)
            $this->saveEquipmentItems($visit->id, $request);

            // ذخیره تصاویر
            $this->savePhotos($visit->id, $request);
        });

        return redirect()
            ->route('expert-initial-visit.index')
            ->with('success', 'فرم بازدید اولیه با موفقیت ثبت و ارسال شد.');
    }

    // ----------------------------------------------------------------
    // نمایش فرم بازدید
    // ----------------------------------------------------------------

    public function show(ExpertInitialVisit $expertInitialVisit): View
    {
        abort_unless($this->canAccess(), 403);

        if (! $this->isLeader()) {
            abort_unless(
                $expertInitialVisit->expert_user_id === Auth::id(),
                403
            );
        }

        $expertInitialVisit->load(['request', 'request.user', 'expert', 'equipmentItems', 'photos']);

        return view('expert-initial-visit::visits.show', compact('expertInitialVisit'));
    }

    // ----------------------------------------------------------------
    // helpers: ذخیره تجهیزات
    // ----------------------------------------------------------------

    private function saveEquipmentItems(int $visitId, Request $request): void
    {
        $names        = $request->input('equipment_name', []);
        $quantities   = $request->input('equipment_quantity', []);
        $powers       = $request->input('equipment_power_watts', []);
        $hours        = $request->input('equipment_usage_hours', []);
        $criticals    = $request->input('equipment_is_critical', []);
        $notes        = $request->input('equipment_notes', []);

        foreach ($names as $i => $name) {
            if (empty(trim($name))) continue;

            $qty   = (int) ($quantities[$i] ?? 1);
            $power = isset($powers[$i]) && $powers[$i] !== '' ? (float) $powers[$i] : null;
            $total = ($power !== null) ? round($power * $qty, 2) : null;

            ExpertVisitEquipmentItem::query()->create([
                'expert_initial_visit_id' => $visitId,
                'name'                    => trim($name),
                'quantity'                => max(1, $qty),
                'power_watts'             => $power,
                'total_power_watts'       => $total,
                'usage_hours'             => isset($hours[$i]) && $hours[$i] !== '' ? (int) $hours[$i] : null,
                'is_critical'             => isset($criticals[$i]) && $criticals[$i] === '1',
                'notes'                   => isset($notes[$i]) ? trim($notes[$i]) : null,
            ]);
        }
    }

    // ----------------------------------------------------------------
    // helpers: ذخیره تصاویر
    // ----------------------------------------------------------------

    private function savePhotos(int $visitId, Request $request): void
    {
        $types    = $request->input('photo_type', []);
        $captions = $request->input('photo_caption', []);
        $files    = $request->file('photo_file', []);
        $path     = config('expert-initial-visit.upload_path', 'expert-initial-visits/photos');

        foreach ($files as $i => $file) {
            if (! $file || ! $file->isValid()) continue;

            $storedPath = $file->store($path, 'public');

            ExpertVisitPhoto::query()->create([
                'expert_initial_visit_id' => $visitId,
                'photo_type'              => $types[$i] ?? 'other',
                'path'                    => $storedPath,
                'caption'                 => isset($captions[$i]) ? trim($captions[$i]) : null,
                'sort_order'              => $i,
            ]);
        }
    }

    // ----------------------------------------------------------------
    // helpers: validation
    // ----------------------------------------------------------------

    private function validateForm(Request $request): array
    {
        return $request->validate([
            'solar_plant_request_id'           => ['required', 'integer', 'exists:solar_plant_requests,id'],
            'visit_date'                       => ['required', 'date'],

            // بخش ۲
            'location_matches'                 => ['required', 'boolean'],
            'actual_address'                   => ['nullable', 'string'],
            'location_physically_confirmed'    => ['required', 'boolean'],
            'location_access'                  => ['required', 'in:easy,medium,hard'],

            // بخش ۳
            'suitable_space_exists'            => ['required', 'boolean'],
            'installation_location_type'       => ['required', 'in:flat_roof,sloped_roof,ground,parking_canopy,other'],
            'installation_location_type_other' => ['nullable', 'string', 'max:200'],
            'total_area_sqm'                   => ['nullable', 'integer', 'min:0'],
            'usable_area_sqm'                  => ['nullable', 'integer', 'min:0'],
            'access_to_installation_site'      => ['required', 'boolean'],
            'physical_obstacle_exists'         => ['required', 'boolean'],
            'obstacle_types'                   => ['nullable', 'array'],
            'obstacle_notes'                   => ['nullable', 'string'],

            // بخش ۴
            'surface_type'                     => ['required', 'in:concrete,metal,tile,soil,other'],
            'surface_orientation'              => ['required', 'in:horizontal,sloped'],
            'panel_direction'                  => ['required', 'in:south,southeast,southwest,east,west,north,other'],
            'shading_level'                    => ['required', 'in:none,low,medium,high'],
            'shading_sources'                  => ['nullable', 'array'],
            'surface_condition'                => ['required', 'in:suitable,unsuitable,suitable_with_fix'],
            'surface_notes'                    => ['nullable', 'string'],

            // بخش ۵
            'structure_load_capacity'          => ['required', 'in:suitable,needs_reinforcement,unsuitable,needs_expert_review'],
            'reinforcement_needed'             => ['required', 'boolean'],
            'special_structure_needed'         => ['required', 'boolean'],
            'site_risks'                       => ['nullable', 'array'],
            'overall_risk_level'               => ['required', 'in:low,medium,high'],
            'structure_notes'                  => ['nullable', 'string'],

            // بخش ۶
            'electricity_type'                 => ['required', 'in:single_phase,three_phase'],
            'connection_capacity_ampere'        => ['nullable', 'integer', 'min:0'],
            'main_panel_accessible'            => ['required', 'boolean'],
            'main_panel_condition'             => ['required', 'in:suitable,needs_fix,unsuitable,needs_review'],
            'electrical_installation_condition'=> ['required', 'in:suitable,needs_fix,unsuitable'],
            'grid_connection_possible'         => ['required', 'boolean'],
            'electrical_fix_needed'            => ['required', 'boolean'],
            'electrical_notes'                 => ['nullable', 'string'],

            // بخش ۷
            'has_emergency_load'               => ['required', 'boolean'],
            'total_emergency_load_kw'          => ['nullable', 'numeric', 'min:0'],
            'emergency_supply_hours'           => ['nullable', 'integer', 'min:0'],
            'battery_need'                     => ['nullable', 'in:yes,no,optional'],
            'emergency_load_notes'             => ['nullable', 'string'],

            // بخش ۸
            'inverter_location'                => ['required', 'in:yes,no,with_fix'],
            'battery_location'                 => ['required', 'in:yes,no,with_fix,not_needed'],
            'equipment_ventilation_ok'         => ['required', 'boolean'],
            'cable_route_ok'                   => ['required', 'boolean'],
            'new_equipment_space_needed'       => ['required', 'boolean'],
            'equipment_location_notes'         => ['nullable', 'string'],

            // بخش ۹
            'applicant_requested_capacity_kw'  => ['nullable', 'numeric', 'min:0'],
            'installable_capacity_kw'          => ['nullable', 'numeric', 'min:0'],
            'expert_proposed_capacity_kw'      => ['required', 'numeric', 'min:0'],
            'expert_proposed_inverter_kw'      => ['nullable', 'numeric', 'min:0'],
            'battery_required'                 => ['required', 'boolean'],
            'expert_proposed_battery_kwh'      => ['nullable', 'numeric', 'min:0'],
            'capacity_difference_reason'       => ['nullable', 'string'],

            // بخش ۱۰
            'pre_execution_fix_needed'         => ['required', 'boolean'],
            'pre_execution_fix_types'          => ['nullable', 'array'],
            'pre_execution_fix_description'    => ['nullable', 'string'],

            // بخش ۱۱
            'assessment_result'                => ['required', 'in:feasible,feasible_with_fix,not_feasible'],
            'not_feasible_reason'              => ['nullable', 'string'],

            // بخش ۱۲
            'expert_summary'                   => ['required', 'string', 'min:20'],
        ], [
            // بخش ۱
            'visit_date.required'                            => 'بخش ۱: تاریخ بازدید الزامی است.',
            'visit_date.date'                                => 'بخش ۱: فرمت تاریخ بازدید صحیح نیست.',

            // بخش ۲
            'location_matches.required'                      => 'بخش ۲: مشخص کنید محل بازدید با محل اعلام‌شده مطابقت دارد یا خیر.',
            'location_physically_confirmed.required'         => 'بخش ۲: تأیید وجود فیزیکی محل الزامی است.',
            'location_access.required'                       => 'بخش ۲: وضعیت دسترسی به محل را انتخاب کنید.',

            // بخش ۳
            'suitable_space_exists.required'                 => 'بخش ۳: مشخص کنید فضای مناسب برای احداث وجود دارد یا خیر.',
            'installation_location_type.required'            => 'بخش ۳: نوع محل نصب را انتخاب کنید.',
            'access_to_installation_site.required'           => 'بخش ۳: وضعیت دسترسی به محل نصب الزامی است.',
            'physical_obstacle_exists.required'              => 'بخش ۳: وجود یا عدم وجود مانع فیزیکی را مشخص کنید.',

            // بخش ۴
            'surface_type.required'                          => 'بخش ۴: نوع سطح نصب را انتخاب کنید.',
            'surface_orientation.required'                   => 'بخش ۴: وضعیت سطح (افقی/شیب‌دار) را انتخاب کنید.',
            'panel_direction.required'                       => 'بخش ۴: جهت سطح نصب پنل را انتخاب کنید.',
            'shading_level.required'                         => 'بخش ۴: میزان سایه‌اندازی را انتخاب کنید.',
            'surface_condition.required'                     => 'بخش ۴: وضعیت سطح برای نصب پنل را انتخاب کنید.',

            // بخش ۵
            'structure_load_capacity.required'               => 'بخش ۵: وضعیت تحمل بار سازه را انتخاب کنید.',
            'reinforcement_needed.required'                  => 'بخش ۵: نیاز به مقاوم‌سازی را مشخص کنید.',
            'special_structure_needed.required'              => 'بخش ۵: نیاز به سازه خاص را مشخص کنید.',
            'overall_risk_level.required'                    => 'بخش ۵: سطح ریسک کلی پروژه را انتخاب کنید.',

            // بخش ۶
            'electricity_type.required'                      => 'بخش ۶: نوع برق محل (تک‌فاز/سه‌فاز) را انتخاب کنید.',
            'main_panel_accessible.required'                 => 'بخش ۶: دسترسی به تابلو برق اصلی را مشخص کنید.',
            'main_panel_condition.required'                  => 'بخش ۶: وضعیت تابلو برق را انتخاب کنید.',
            'electrical_installation_condition.required'     => 'بخش ۶: وضعیت کلی تأسیسات برق را انتخاب کنید.',
            'grid_connection_possible.required'              => 'بخش ۶: امکان اتصال به شبکه را مشخص کنید.',
            'electrical_fix_needed.required'                 => 'بخش ۶: نیاز به اصلاح تأسیسات برق را مشخص کنید.',

            // بخش ۷
            'has_emergency_load.required'                    => 'بخش ۷: نیاز به بار اضطراری را مشخص کنید.',

            // بخش ۸
            'inverter_location.required'                     => 'بخش ۸: وضعیت محل نصب اینورتر را انتخاب کنید.',
            'battery_location.required'                      => 'بخش ۸: وضعیت محل نصب باتری را انتخاب کنید.',
            'equipment_ventilation_ok.required'              => 'بخش ۸: تهویه محل تجهیزات را مشخص کنید.',
            'cable_route_ok.required'                        => 'بخش ۸: وضعیت مسیر کابل‌کشی را مشخص کنید.',
            'new_equipment_space_needed.required'            => 'بخش ۸: نیاز به ایجاد فضای جدید را مشخص کنید.',

            // بخش ۹
            'expert_proposed_capacity_kw.required'           => 'بخش ۹: ظرفیت پیشنهادی کارشناس الزامی است.',
            'expert_proposed_capacity_kw.numeric'            => 'بخش ۹: ظرفیت پیشنهادی باید عدد باشد.',
            'battery_required.required'                      => 'بخش ۹: نیاز به باتری را مشخص کنید.',

            // بخش ۱۰
            'pre_execution_fix_needed.required'              => 'بخش ۱۰: نیاز به اصلاح پیش از اجرا را مشخص کنید.',

            // بخش ۱۱
            'assessment_result.required'                     => 'بخش ۱۱: نتیجه ارزیابی اولیه الزامی است.',
            'assessment_result.in'                           => 'بخش ۱۱: یکی از گزینه‌های نتیجه ارزیابی را انتخاب کنید.',

            // بخش ۱۲
            'expert_summary.required'                        => 'بخش ۱۲: جمع‌بندی کارشناسی الزامی است.',
            'expert_summary.min'                             => 'بخش ۱۲: جمع‌بندی کارشناسی باید حداقل ۲۰ کاراکتر باشد.',
        ]);
    }
}
