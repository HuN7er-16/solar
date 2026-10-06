<?php

namespace SolarPlantEquipment\Http\Controllers;

use BatteryCatalog\Models\BatteryCatalog;
use ContractorCatalog\Models\Contractor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use InverterCatalog\Models\InverterCatalog;
use PanelCatalog\Models\PanelCatalog;
use SolarPlantEquipment\Models\SolarProject;

class ContractorProjectsController
{
    /** پیدا کردن رکورد پیمانکار مرتبط با کاربر جاری */
    private function getContractor(): ?Contractor
    {
        return Contractor::where('user_id', Auth::id())->first();
    }

    /** بررسی دسترسی: کاربر باید پیمانکار باشد و پروژه متعلق به همین پیمانکار */
    private function authorizeProject(SolarProject $project): void
    {
        $contractor = $this->getContractor();

        abort_unless($contractor, 403, 'پروفایل پیمانکار برای کاربر جاری یافت نشد.');
        abort_unless(
            (string) $project->contractor_id === (string) $contractor->id,
            403,
            'شما دسترسی به این پروژه ندارید.'
        );
    }

    /**
     * لیست پروژه‌های مربوط به پیمانکار جاری
     */
    public function index(): View
    {
        $contractor = $this->getContractor();
        abort_unless($contractor, 403, 'پروفایل پیمانکار برای کاربر جاری یافت نشد.');

        $projects = SolarProject::query()
            ->with(['request'])
            ->where('contractor_id', $contractor->id)
            ->latest()
            ->get();

        return view('solar-plant-equipment::contractor.my-projects', compact('projects', 'contractor'));
    }

    /**
     * نمایش جزئیات پروژه برای پیمانکار
     * همراه با امکان افزودن تجهیزات و پر کردن فیلدهای خالی
     */
    public function show(SolarProject $project): View
    {
        $this->authorizeProject($project);

        $project->load([
            'request',
            'contractor',
            'inspector',
            'installedPanels',
            'installedPanels.catalog',
            'installedInverters',
            'installedInverters.catalog',
            'installedBatteries',
            'installedBatteries.catalog',
        ]);

        $panels    = PanelCatalog::query()->orderBy('brand')->get();
        $inverters = InverterCatalog::query()->orderBy('brand')->get();
        $batteries = BatteryCatalog::query()->orderBy('brand')->get();

        return view('solar-plant-equipment::contractor.project-detail', compact(
            'project',
            'panels',
            'inverters',
            'batteries'
        ));
    }

    /**
     * ذخیره فیلدهای قابل ویرایش توسط پیمانکار:
     *   - تاریخ شروع و پایان نصب
     *   - تاریخ بهره‌برداری
     *   - مختصات جغرافیایی
     *   - توضیحات
     *
     * فیلدهایی که فقط راهبر تغییر می‌دهد (request_id, contractor_id, inspector_id,
     * status, satba_contract_number, health_card_*) دست‌نخورده می‌مانند.
     */
    public function update(Request $request, SolarProject $project): RedirectResponse
    {
        $this->authorizeProject($project);

        $validated = $request->validate([
            'installation_start_date' => ['nullable', 'date'],
            'installation_end_date'   => ['nullable', 'date'],
            'commissioning_date'      => ['nullable', 'date'],
            'latitude'                => ['nullable', 'numeric', 'between:-90,90'],
            'longitude'               => ['nullable', 'numeric', 'between:-180,180'],
            'description'             => ['nullable', 'string', 'max:2000'],
        ], [
            'latitude.between'  => 'عرض جغرافیایی باید بین -90 و 90 باشد.',
            'longitude.between' => 'طول جغرافیایی باید بین -180 و 180 باشد.',
        ]);

        // تبدیل تاریخ‌های شمسی به میلادی
        $dateFields = ['installation_start_date', 'installation_end_date', 'commissioning_date'];
        foreach ($dateFields as $field) {
            if (! empty($validated[$field]) && function_exists('toGregorianDate')) {
                $validated[$field] = toGregorianDate($validated[$field]);
            }
        }

        $project->update($validated);

        return redirect()
            ->route('solar-plant-equipment.contractor.projects.show', $project)
            ->with('success', 'اطلاعات پروژه با موفقیت ذخیره شد.');
    }

    /**
     * آماده برای بازرسی — پیمانکار وقتی کارش تمام شد این را می‌زند
     */
    public function readyForInspection(Request $request, SolarProject $project): RedirectResponse
    {
        $this->authorizeProject($project);

        abort_unless(
            $project->status === SolarProject::STATUS_IN_PROGRESS,
            422,
            'وضعیت پروژه باید «در حال اجرا» باشد.'
        );

        $project->update(['status' => SolarProject::STATUS_READY_FOR_INSPECTION]);

        return redirect()
            ->route('solar-plant-equipment.contractor.projects.show', $project)
            ->with('success', 'پروژه آماده بازرسی اعلام شد.');
    }
}
