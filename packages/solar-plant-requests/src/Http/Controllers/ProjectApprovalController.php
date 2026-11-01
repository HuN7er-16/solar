<?php

namespace SolarPlantRequests\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use SolarPlantRequests\Enums\SolarPlantRequestStatus;
use SolarPlantRequests\Models\SolarPlantRequest;

class ProjectApprovalController
{
    /**
     * بررسی دسترسی: فقط خود متقاضی یا راهبر
     */
    private function canAccess(Request $request, SolarPlantRequest $solarPlantRequest): bool
    {
        $user = $request->user();

        if (SolarPlantRequest::userHasRole($user, 'leader')) {
            return true;
        }

        return $solarPlantRequest->user_id === $user->id;
    }

    /**
     * نمایش جزئیات پروژه برای تایید یا رد توسط متقاضی
     * فقط در وضعیت awaiting_project_approval قابل دسترس است
     */
    public function show(Request $request, SolarPlantRequest $solarPlantRequest): View
    {
        abort_unless(
            $this->canAccess($request, $solarPlantRequest),
            403,
            'شما دسترسی به این صفحه ندارید.'
        );

        abort_unless(
            $solarPlantRequest->status === SolarPlantRequestStatus::AWAITING_PROJECT_APPROVAL,
            422,
            'این تقاضا در مرحله تایید پروژه نیست.'
        );

        return view('solar-plant-requests::requests.project-approval-review', [
            'req' => $solarPlantRequest,
        ]);
    }

    /**
     * تایید پروژه توسط متقاضی → وضعیت به equipment_installation
     */
    public function approve(Request $request, SolarPlantRequest $solarPlantRequest): RedirectResponse
    {
        abort_unless(
            $this->canAccess($request, $solarPlantRequest),
            403,
            'شما دسترسی به این عملیات ندارید.'
        );

        abort_unless(
            $solarPlantRequest->status === SolarPlantRequestStatus::AWAITING_PROJECT_APPROVAL,
            422,
            'تایید پروژه فقط در مرحله تایید پروژه امکان‌پذیر است.'
        );

        $solarPlantRequest->update([
            'project_approval_status'  => 'approved',
            'project_approved_at'      => now(),
            'project_rejection_reason' => null,
            'status'                   => SolarPlantRequestStatus::EQUIPMENT_INSTALLATION,
        ]);

        // ── ارسال پیام اطلاع‌رسانی به متقاضی ─────────────────────────
        // TODO: ارسال SMS بعد از تایید پروژه
        // try {
        //     $mobile = function_exists('convertPersianToEnglish')
        //         ? convertPersianToEnglish($solarPlantRequest->mobile)
        //         : $solarPlantRequest->mobile;
        //
        //     $message = "سامانه ساتا اصناف\nپروژه نیروگاه خورشیدی شما با کد {$solarPlantRequest->unique_code} تایید شد و عملیات نصب تجهیزات آغاز می‌شود.";
        //
        //     if ($mobile) {
        //         \Behin\Sms\Controllers\SmsController::send($mobile, $message);
        //     }
        // } catch (\Throwable $e) {
        //     \Illuminate\Support\Facades\Log::warning('ارسال SMS تایید پروژه ناموفق بود.', [
        //         'request_id'  => $solarPlantRequest->id,
        //         'unique_code' => $solarPlantRequest->unique_code,
        //         'error'       => $e->getMessage(),
        //     ]);
        // }

        return redirect()
            ->route('solar-plant-requests.index')
            ->with('status', 'پروژه با موفقیت تایید شد. عملیات نصب تجهیزات آغاز خواهد شد.');
    }

    /**
     * رد پروژه توسط متقاضی → وضعیت به contractor_assigned برمی‌گردد تا راهبر ویرایش کند
     */
    public function reject(Request $request, SolarPlantRequest $solarPlantRequest): RedirectResponse
    {
        abort_unless(
            $this->canAccess($request, $solarPlantRequest),
            403,
            'شما دسترسی به این عملیات ندارید.'
        );

        abort_unless(
            $solarPlantRequest->status === SolarPlantRequestStatus::AWAITING_PROJECT_APPROVAL,
            422,
            'رد پروژه فقط در مرحله تایید پروژه امکان‌پذیر است.'
        );

        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'min:10', 'max:1000'],
        ], [
            'rejection_reason.required' => 'لطفاً دلیل رد پروژه را وارد کنید.',
            'rejection_reason.min'      => 'دلیل رد باید حداقل ۱۰ کاراکتر باشد.',
        ]);

        $solarPlantRequest->update([
            'project_approval_status'  => 'rejected',
            'project_approved_at'      => now(),
            'project_rejection_reason' => $validated['rejection_reason'],
            'status'                   => SolarPlantRequestStatus::CONTRACTOR_ASSIGNED,
        ]);

        // ── ارسال پیام اطلاع‌رسانی به متقاضی ─────────────────────────
        // TODO: ارسال SMS بعد از رد پروژه
        // try {
        //     $mobile = function_exists('convertPersianToEnglish')
        //         ? convertPersianToEnglish($solarPlantRequest->mobile)
        //         : $solarPlantRequest->mobile;
        //
        //     $message = "سامانه ساتا اصناف\nپروژه نیروگاه خورشیدی شما با کد {$solarPlantRequest->unique_code} رد شد. راهبر سیستم پروژه را مجدداً بررسی خواهد کرد.";
        //
        //     if ($mobile) {
        //         \Behin\Sms\Controllers\SmsController::send($mobile, $message);
        //     }
        // } catch (\Throwable $e) {
        //     \Illuminate\Support\Facades\Log::warning('ارسال SMS رد پروژه ناموفق بود.', [
        //         'request_id'  => $solarPlantRequest->id,
        //         'unique_code' => $solarPlantRequest->unique_code,
        //         'error'       => $e->getMessage(),
        //     ]);
        // }

        return redirect()
            ->route('solar-plant-requests.index')
            ->with('status', 'پروژه رد شد. راهبر سیستم برای بررسی مجدد اطلاع‌رسانی می‌شود.');
    }
}
