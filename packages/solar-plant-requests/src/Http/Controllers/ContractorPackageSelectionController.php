<?php

namespace SolarPlantRequests\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use SolarPlantRequests\Enums\SolarPlantRequestStatus;
use SolarPlantRequests\Http\Controllers\Contractor\GetController as ContractorGetController;
use SolarPlantRequests\Models\SolarPlantPackage;
use SolarPlantRequests\Models\SolarPlantRequest;

class ContractorPackageSelectionController
{
    /**
     * بررسی اینکه کاربر جاری می‌تواند به این درخواست دسترسی داشته باشد.
     * - خود متقاضی (owner)
     * - راهبر سیستم
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
     * صفحه انتخاب پیمانکار و پکیج
     * فقط در وضعیت CONTRACTOR_ASSIGNED قابل دسترس است
     */
    public function show(Request $request, SolarPlantRequest $solarPlantRequest): View
    {
        abort_unless(
            $this->canAccess($request, $solarPlantRequest),
            403,
            'شما دسترسی به این صفحه ندارید.'
        );

        abort_unless(
            $solarPlantRequest->status === SolarPlantRequestStatus::CONTRACTOR_ASSIGNED,
            422,
            'انتخاب پیمانکار و پکیج فقط در مرحله «انتخاب پیمانکار و پکیج» امکان‌پذیر است.'
        );

        $contractors = ContractorGetController::getAll();
        $packages    = SolarPlantPackage::active()->get()->groupBy('capacity_kw');

        return view('solar-plant-requests::requests.contractor-package-selection', [
            'req'         => $solarPlantRequest,
            'contractors' => $contractors,
            'packages'    => $packages,
        ]);
    }

    /**
     * ذخیره انتخاب پیمانکار و پکیج
     * پس از ذخیره، وضعیت به EQUIPMENT_INSTALLATION تغییر می‌کند
     */
    public function store(Request $request, SolarPlantRequest $solarPlantRequest): RedirectResponse
    {
        abort_unless(
            $this->canAccess($request, $solarPlantRequest),
            403,
            'شما دسترسی به این عملیات ندارید.'
        );

        abort_unless(
            $solarPlantRequest->status === SolarPlantRequestStatus::CONTRACTOR_ASSIGNED,
            422,
            'انتخاب پیمانکار و پکیج فقط در مرحله «انتخاب پیمانکار و پکیج» امکان‌پذیر است.'
        );

        $validated = $request->validate([
            'contractor_id' => ['required', 'integer', 'exists:users,id'],
            'package_id'    => ['required', 'integer', 'exists:solar_plant_packages,id'],
        ], [
            'contractor_id.required' => 'انتخاب پیمانکار الزامی است.',
            'contractor_id.exists'   => 'پیمانکار انتخابی در سیستم وجود ندارد.',
            'package_id.required'    => 'انتخاب پکیج الزامی است.',
            'package_id.exists'      => 'پکیج انتخابی در سیستم وجود ندارد.',
        ]);

        // بررسی معتبر بودن پیمانکار (role_id = 5)
        $contractor = ContractorGetController::getById($validated['contractor_id']);
        abort_if(! $contractor, 422, 'پیمانکار انتخابی معتبر نیست.');

        // بررسی فعال بودن پکیج
        $package = SolarPlantPackage::where('id', $validated['package_id'])
            ->where('is_active', true)
            ->firstOrFail();

        $solarPlantRequest->update([
            'contractor_id'           => $contractor->id,
            'contractor_name'         => $contractor->name,
            'selected_package_id'     => $package->id,
            'selected_package_price'  => $package->price,
            'selected_package_title'  => $package->title,
            'status'                  => SolarPlantRequestStatus::EQUIPMENT_INSTALLATION,
        ]);

        return redirect()
            ->route('solar-plant-requests.index')
            ->with('status', 'پیمانکار و پکیج با موفقیت انتخاب شدند. درخواست شما وارد مرحله نصب تجهیزات شد.');
    }
}
