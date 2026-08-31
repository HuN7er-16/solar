<?php

namespace RequestExpertReview\Http\Controllers;

use Behin\Sms\Controllers\SmsController;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use SolarPlantRequests\Enums\SolarPlantRequestStatus;
use SolarPlantRequests\Models\SolarPlantRequest;

class AdminAssignExpertController
{
    private function isLeader(): bool
    {
        $leaderRoleIds = collect(config('solar-plant-requests.roles.leader', [1]))
            ->map(fn ($id) => (string) $id)
            ->all();

        return in_array((string) auth()->user()?->role_id, $leaderRoleIds, true);
    }

    /**
     * لیست همه تقاضاها برای راهبر — تقاضاهای ثبت اولیه آماده تخصیص کارشناس هستند
     */
    public function index(Request $request)
    {
        abort_unless($this->isLeader(), 403, 'فقط راهبر سایت می‌تواند به این صفحه دسترسی داشته باشد.');

        $requests = SolarPlantRequest::query()
            ->latest()
            ->get();

        $experts = ExpertGetController::getAll();

        return view('request-expert-review::admin.index', compact('requests', 'experts'));
    }

    /**
     * اساین کارشناس به تقاضا — فقط برای تقاضاهای در وضعیت initial_registration
     * وضعیت تقاضا به under_review تغییر می‌کند
     * پس از اساین، پیامک اطلاع‌رسانی به کارشناس ارسال می‌شود
     */
    public function assignExpert(Request $request, SolarPlantRequest $solarPlantRequest): RedirectResponse
    {
        abort_unless($this->isLeader(), 403, 'فقط راهبر سایت می‌تواند کارشناس تخصیص دهد.');

        abort_unless(
            $solarPlantRequest->status === SolarPlantRequestStatus::INITIAL,
            422,
            'تخصیص کارشناس فقط برای تقاضاهای در وضعیت «ثبت اولیه» امکان‌پذیر است.'
        );

        $validated = $request->validate([
            'expert_user_id' => ['required', 'integer', 'exists:users,id'],
        ], [
            'expert_user_id.required' => 'انتخاب کارشناس الزامی است.',
            'expert_user_id.exists'   => 'کارشناس انتخابی در سیستم وجود ندارد.',
        ]);

        $expert = ExpertGetController::getById((int) $validated['expert_user_id']);

        abort_if(! $expert, 422, 'کارشناس انتخابی معتبر نیست.');

        $solarPlantRequest->update([
            'expert_user_id' => $expert->id,
            'expert_name'    => $expert->name,
            'status'         => SolarPlantRequestStatus::UNDER_REVIEW,
        ]);

        // ارسال پیامک اطلاع‌رسانی به کارشناس
        $this->sendAssignmentSms($expert, $solarPlantRequest);

        return redirect()
            ->back()
            ->with('success', "کارشناس «{$expert->name}» با موفقیت به تقاضا اختصاص داده شد و وضعیت به «در حال بررسی» تغییر کرد.");
    }

    /**
     * ارسال پیامک اطلاع‌رسانی به کارشناس
     * برای غیرفعال کردن در محیط تست: EXPERT_VISIT_SMS_ENABLED=false در .env
     */
    private function sendAssignmentSms($expert, SolarPlantRequest $solarPlantRequest): void
    {
        // بررسی فعال بودن SMS
        if (! config('expert-initial-visit.sms_enabled', true)) {
            return;
        }

        // پیدا کردن شماره موبایل کارشناس از جدول experts
        $mobile = null;

        try {
            // اول از جدول experts
            $expertProfile = \DB::table('experts')->where('user_id', $expert->id)->first();
            if ($expertProfile?->mobile) {
                $mobile = $expertProfile->mobile;
            } elseif ($expert->phone) {
                // fallback به شماره کاربری
                $mobile = $expert->phone;
            }

            if (! $mobile) {
                return;
            }

            if (function_exists('convertPersianToEnglish')) {
                $mobile = convertPersianToEnglish($mobile);
            }

            $message = "سامانه جامع اتحادیه سوخت‌های جایگزین\n"
                . "کارشناس گرامی {$expert->name}،\n"
                . "یک تقاضای بازدید اولیه با کد {$solarPlantRequest->unique_code} "
                . "به شما اختصاص داده شده است.\n"
                . "لطفاً برای مشاهده جزئیات وارد سامانه شوید.";

            SmsController::send($mobile, $message);

        } catch (\Throwable $e) {
            Log::warning('ارسال پیامک اختصاص کارشناس ناموفق بود.', [
                'expert_id'   => $expert->id,
                'request_id'  => $solarPlantRequest->id,
                'error'       => $e->getMessage(),
            ]);
        }
    }
}
