<?php

namespace RequestExpertReview\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use SolarPlantRequests\Enums\SolarPlantRequestStatus;
use SolarPlantRequests\Models\SolarPlantRequest;

class ExpertRequestController
{
    private function isLeader(): bool
    {
        $leaderRoleIds = collect(config('solar-plant-requests.roles.leader', [1]))
            ->map(fn ($id) => (string) $id)
            ->all();

        return in_array((string) Auth::user()?->role_id, $leaderRoleIds, true);
    }

    private function isExpert(): bool
    {
        // TODO: مقدار expert را در config/request-expert-review.php پر کنید
        $expertRoleId = config('request-expert-review.roles.expert'); // <-- null تا زمانی که پر نشده

        if (! $expertRoleId) {
            return false;
        }

        return (string) Auth::user()?->role_id === (string) $expertRoleId;
    }

    private function canAccess(): bool
    {
        return $this->isLeader() || $this->isExpert();
    }

    /**
     * لیست تقاضاهای اختصاص‌یافته به کارشناس جاری
     */
    public function index(): View
    {
        abort_unless($this->canAccess(), 403, 'شما دسترسی به این صفحه ندارید.');

        $userId = Auth::id();

        $query = SolarPlantRequest::query()
            ->where('status', SolarPlantRequestStatus::UNDER_REVIEW)
            ->latest();

        // کارشناس فقط تقاضاهای اختصاص‌یافته به خودش را می‌بیند
        if (! $this->isLeader()) {
            $query->where('expert_user_id', $userId);
        }

        $requests = $query->get();

        return view('request-expert-review::expert.index', compact('requests'));
    }

    /**
     * صفحه جزئیات و ویرایش تقاضا توسط کارشناس
     */
    public function show(SolarPlantRequest $solarPlantRequest): View
    {
        abort_unless($this->canAccess(), 403, 'شما دسترسی به این صفحه ندارید.');

        if (! $this->isLeader()) {
            abort_unless(
                $solarPlantRequest->expert_user_id === Auth::id(),
                403,
                'این تقاضا به شما اختصاص داده نشده است.'
            );
        }

        abort_unless(
            $solarPlantRequest->status === SolarPlantRequestStatus::UNDER_REVIEW,
            422,
            'این تقاضا در مرحله بررسی کارشناس نیست.'
        );

        $solarPlantRequest->load(['user', 'panels', 'inverters', 'batteries']);

        return view('request-expert-review::expert.show', compact('solarPlantRequest'));
    }

    /**
     * ذخیره ویرایش‌های کارشناس روی فیلدهای فنی تقاضا
     * اطلاعات شخصی متقاضی قابل ویرایش نیست
     */
    public function update(Request $request, SolarPlantRequest $solarPlantRequest): RedirectResponse
    {
        abort_unless($this->canAccess(), 403, 'شما دسترسی به این عملیات ندارید.');

        if (! $this->isLeader()) {
            abort_unless(
                $solarPlantRequest->expert_user_id === Auth::id(),
                403,
                'این تقاضا به شما اختصاص داده نشده است.'
            );
        }

        abort_unless(
            $solarPlantRequest->status === SolarPlantRequestStatus::UNDER_REVIEW,
            422,
            'این تقاضا در مرحله بررسی کارشناس نیست.'
        );

        $validated = $request->validate([
            'bill_identifier'    => ['nullable', 'string', 'max:255'],
            'area'               => ['nullable', 'integer', 'min:0'],
            'installation_area'  => ['nullable', 'integer', 'min:0'],
            'capacity_kw'        => ['nullable', 'integer', 'min:1'],
            'usage_type'         => ['nullable', 'string', 'max:20'],
            'surface_type'       => ['nullable', 'string', 'max:20'],
            'purpose'            => ['nullable', 'string', 'max:20'],
            'has_three_phase'    => ['nullable', 'boolean'],
            'wants_loan'         => ['nullable', 'boolean'],
            'is_shared_property' => ['nullable', 'boolean'],
            'description'        => ['nullable', 'string'],
        ]);

        $validated['has_three_phase']    = (bool) ($validated['has_three_phase']    ?? false);
        $validated['wants_loan']         = (bool) ($validated['wants_loan']         ?? false);
        $validated['is_shared_property'] = (bool) ($validated['is_shared_property'] ?? false);

        $solarPlantRequest->update($validated);

        return redirect()
            ->route('request-expert-review.expert.show', $solarPlantRequest)
            ->with('success', 'تغییرات با موفقیت ذخیره شد.');
    }
}
