<?php

namespace SolarPlantRequests\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use SolarPlantRequests\Enums\SolarPlantRequestStatus;
use SolarPlantRequests\Models\SolarPlantPackage;
use SolarPlantRequests\Models\SolarPlantRequest;

class PackageSelectionController
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
     * صفحه انتخاب پکیج
     * فقط در وضعیت PACKAGE_SELECTION قابل دسترس است
     */
    public function show(Request $request, SolarPlantRequest $solarPlantRequest): View
    {
        abort_unless(
            $this->canAccess($request, $solarPlantRequest),
            403,
            'شما دسترسی به این صفحه ندارید.'
        );

        abort_unless(
            $solarPlantRequest->status === SolarPlantRequestStatus::PACKAGE_SELECTION,
            422,
            'انتخاب پکیج فقط در مرحله «انتخاب پکیج» امکان‌پذیر است.'
        );

        $packages = SolarPlantPackage::active()->get()->groupBy('capacity_kw');

        return view('solar-plant-requests::requests.package-selection', [
            'req'      => $solarPlantRequest,
            'packages' => $packages,
        ]);
    }

    /**
     * ذخیره انتخاب پکیج → وضعیت به CONTRACTOR_SELECTION
     */
    public function store(Request $request, SolarPlantRequest $solarPlantRequest): RedirectResponse
    {
        abort_unless(
            $this->canAccess($request, $solarPlantRequest),
            403,
            'شما دسترسی به این عملیات ندارید.'
        );

        abort_unless(
            $solarPlantRequest->status === SolarPlantRequestStatus::PACKAGE_SELECTION,
            422,
            'انتخاب پکیج فقط در مرحله «انتخاب پکیج» امکان‌پذیر است.'
        );

        $validated = $request->validate([
            'package_id' => ['required', 'integer', 'exists:solar_plant_packages,id'],
        ], [
            'package_id.required' => 'انتخاب پکیج الزامی است.',
            'package_id.exists'   => 'پکیج انتخابی در سیستم وجود ندارد.',
        ]);

        $package = SolarPlantPackage::where('id', $validated['package_id'])
            ->where('is_active', true)
            ->firstOrFail();

        $solarPlantRequest->update([
            'selected_package_id'    => $package->id,
            'selected_package_price' => $package->price,
            'selected_package_title' => $package->title,
            'status'                 => SolarPlantRequestStatus::CONTRACTOR_SELECTION,
        ]);

        return redirect()
            ->route('solar-plant-requests.index')
            ->with('status', 'پکیج با موفقیت انتخاب شد. لطفاً پیمانکار مورد نظر خود را انتخاب کنید.');
    }
}
