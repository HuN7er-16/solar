<?php

namespace SolarPlantRequests\Http\Controllers;

use ContractorCatalog\Models\Contractor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use SolarPlantRequests\Enums\SolarPlantRequestStatus;
use SolarPlantRequests\Models\SolarPlantRequest;

class ContractorSelectionController
{
    private function canAccess(Request $request, SolarPlantRequest $solarPlantRequest): bool
    {
        $user = $request->user();

        if (SolarPlantRequest::userHasRole($user, 'leader')) {
            return true;
        }

        return $solarPlantRequest->user_id === $user->id;
    }

    /**
     * صفحه انتخاب پیمانکار — فیلتر بر اساس استان تقاضا
     * فقط در وضعیت CONTRACTOR_SELECTION قابل دسترس است
     */
    public function show(Request $request, SolarPlantRequest $solarPlantRequest): View
    {
        abort_unless(
            $this->canAccess($request, $solarPlantRequest),
            403,
            'شما دسترسی به این صفحه ندارید.'
        );

        abort_unless(
            $solarPlantRequest->status === SolarPlantRequestStatus::CONTRACTOR_SELECTION,
            422,
            'انتخاب پیمانکار فقط در مرحله «انتخاب پیمانکار» امکان‌پذیر است.'
        );

        // استان فیلتر — پیش‌فرض استان خود تقاضا، قابل تغییر توسط کاربر
        $selectedProvince = $request->query('province', $solarPlantRequest->province);

        $contractorsQuery = Contractor::query();

        if ($selectedProvince) {
            $contractorsQuery->where('province', $selectedProvince);
        }

        $contractors = $contractorsQuery->orderBy('company_name')->get();
        $provinces   = config('contractor-catalog.provinces', []);

        return view('solar-plant-requests::requests.contractor-selection', [
            'req'              => $solarPlantRequest,
            'contractors'      => $contractors,
            'provinces'        => $provinces,
            'selectedProvince' => $selectedProvince,
        ]);
    }

    /**
     * ذخیره انتخاب پیمانکار → وضعیت به CONTRACTOR_ASSIGNED
     * راهبر می‌تواند پروژه تعریف کند
     */
    public function store(Request $request, SolarPlantRequest $solarPlantRequest): RedirectResponse
    {
        abort_unless(
            $this->canAccess($request, $solarPlantRequest),
            403,
            'شما دسترسی به این عملیات ندارید.'
        );

        abort_unless(
            $solarPlantRequest->status === SolarPlantRequestStatus::CONTRACTOR_SELECTION,
            422,
            'انتخاب پیمانکار فقط در مرحله «انتخاب پیمانکار» امکان‌پذیر است.'
        );

        $validated = $request->validate([
            'contractor_id' => ['required', 'integer', 'exists:contractors,id'],
        ], [
            'contractor_id.required' => 'انتخاب پیمانکار الزامی است.',
            'contractor_id.exists'   => 'پیمانکار انتخابی در سیستم وجود ندارد.',
        ]);

        $contractor = Contractor::where('id', $validated['contractor_id'])
            ->firstOrFail();

        $solarPlantRequest->update([
            'selected_contractor_id' => $contractor->id,
            'contractor_name'        => $contractor->company_name,
            'status'                 => SolarPlantRequestStatus::CONTRACTOR_ASSIGNED,
        ]);

        return redirect()
            ->route('solar-plant-requests.index')
            ->with('status', 'پیمانکار با موفقیت انتخاب شد. تقاضای شما برای تعریف پروژه به راهبر ارسال شد.');
    }
}
