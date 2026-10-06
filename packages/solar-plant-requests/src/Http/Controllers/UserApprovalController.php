<?php

namespace SolarPlantRequests\Http\Controllers;

use ExpertInitialVisit\Models\ExpertInitialVisit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use SolarPlantRequests\Enums\SolarPlantRequestStatus;
use SolarPlantRequests\Models\SolarPlantRequest;

class UserApprovalController
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
     * نمایش گزارش کارشناسی اولیه برای متقاضی (read-only)
     * فقط در وضعیت awaiting_user_approval قابل دسترس است
     */
    public function show(Request $request, SolarPlantRequest $solarPlantRequest): View
    {
        abort_unless(
            $this->canAccess($request, $solarPlantRequest),
            403,
            'شما دسترسی به این صفحه ندارید.'
        );

        abort_unless(
            $solarPlantRequest->status === SolarPlantRequestStatus::AWAITING_USER_APPROVAL,
            422,
            'این تقاضا در مرحله تایید متقاضی نیست.'
        );

        // گزارش بازدید اولیه کارشناس
        $visit = null;
        if (class_exists(ExpertInitialVisit::class)) {
            $visit = ExpertInitialVisit::query()
                ->where('solar_plant_request_id', $solarPlantRequest->id)
                ->latest()
                ->first();
        }

        return view('solar-plant-requests::requests.expert-report-review', [
            'req'   => $solarPlantRequest,
            'visit' => $visit,
        ]);
    }

    /**
     * تایید گزارش توسط متقاضی → وضعیت به package_selection
     */
    public function approve(Request $request, SolarPlantRequest $solarPlantRequest): RedirectResponse
    {
        abort_unless(
            $this->canAccess($request, $solarPlantRequest),
            403,
            'شما دسترسی به این عملیات ندارید.'
        );

        abort_unless(
            $solarPlantRequest->status === SolarPlantRequestStatus::AWAITING_USER_APPROVAL,
            422,
            'تایید گزارش فقط در مرحله تایید متقاضی امکان‌پذیر است.'
        );

        $solarPlantRequest->update([
            'user_approval_status' => 'approved',
            'user_approved_at'     => now(),
            'user_rejection_reason'=> null,
            'status'               => SolarPlantRequestStatus::PACKAGE_SELECTION,
        ]);

        return redirect()
            ->route('solar-plant-requests.index')
            ->with('status', 'گزارش کارشناسی تایید شد. لطفاً پکیج مورد نظر خود را انتخاب کنید.');
    }

    /**
     * رد گزارش توسط متقاضی → وضعیت به under_review برمی‌گردد
     */
    public function reject(Request $request, SolarPlantRequest $solarPlantRequest): RedirectResponse
    {
        abort_unless(
            $this->canAccess($request, $solarPlantRequest),
            403,
            'شما دسترسی به این عملیات ندارید.'
        );

        abort_unless(
            $solarPlantRequest->status === SolarPlantRequestStatus::AWAITING_USER_APPROVAL,
            422,
            'رد گزارش فقط در مرحله تایید متقاضی امکان‌پذیر است.'
        );

        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'min:10', 'max:1000'],
        ], [
            'rejection_reason.required' => 'لطفاً دلیل رد گزارش را وارد کنید.',
            'rejection_reason.min'      => 'دلیل رد باید حداقل ۱۰ کاراکتر باشد.',
        ]);

        $solarPlantRequest->update([
            'user_approval_status'  => 'rejected',
            'user_approved_at'      => now(),
            'user_rejection_reason' => $validated['rejection_reason'],
            'status'                => SolarPlantRequestStatus::UNDER_REVIEW,
        ]);

        return redirect()
            ->route('solar-plant-requests.index')
            ->with('status', 'گزارش کارشناسی رد شد. تقاضا برای بررسی مجدد به کارشناس بازگشت.');
    }
}
