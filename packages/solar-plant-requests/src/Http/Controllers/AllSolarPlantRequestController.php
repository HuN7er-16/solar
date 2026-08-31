<?php

namespace SolarPlantRequests\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Morilog\Jalali\Jalalian;
use SolarPlantRequests\Enums\SolarPlantRequestStatus;
use SolarPlantRequests\Http\Controllers\Contractor\GetController;
use SolarPlantRequests\Models\SolarPlantRequest;

class AllSolarPlantRequestController
{
    public function index(Request $request): View|JsonResponse
    {
        abort_unless(
            SolarPlantRequest::userHasRole($request->user(), 'leader'),
            403,
            'فقط کاربر دارای نقش راهبر می‌تواند به همه درخواست‌ها دسترسی داشته باشد.'
        );

        $user = $request->user();

        $validStatuses = array_column(SolarPlantRequestStatus::cases(), 'value');

        $status = $request->query('status');
        if (!in_array($status, $validStatuses, true)) {
            $status = null;
        }

        $name = trim((string) $request->query('name', ''));
        $number = trim((string) $request->query('number', ''));
        $dateFrom = $this->jalaliToCarbon($request->query('date_from'));
        $dateTo = $this->jalaliToCarbon($request->query('date_to'));

        $requests = SolarPlantRequest::query()
            ->when($status, fn (Builder $query) => $query->where('status', $status))
            ->when($name !== '', function (Builder $query) use ($name) {
                $query->where(function (Builder $query) use ($name) {
                    $query->where('first_name', 'like', "%{$name}%")
                        ->orWhere('last_name', 'like', "%{$name}%")
                        ->orWhere('company_name', 'like', "%{$name}%");
                });
            })
            ->when($number !== '', function (Builder $query) use ($number) {
                $query->where(function (Builder $query) use ($number) {
                    $query->where('unique_code', 'like', "%{$number}%")
                        ->orWhere('national_code', 'like', "%{$number}%")
                        ->orWhere('mobile', 'like', "%{$number}%");
                });
            })
            ->when($dateFrom, fn (Builder $query) => $query->where('created_at', '>=', $dateFrom->startOfDay()))
            ->when($dateTo, fn (Builder $query) => $query->where('created_at', '<=', $dateTo->endOfDay()))
            ->latest()
            ->get();

        $contractors = GetController::getAll();

        if ($request->wantsJson()) {
            return response()->json(['data' => $requests]);
        }

        return view('solar-plant-requests::requests.all-requests', [
            'requests' => $requests,
            'contractors' => $contractors,
        ]);
    }

    /**
     * تبدیل تاریخ شمسی ورودی (مثل 1404/05/01 یا با ارقام فارسی) به Carbon میلادی.
     * در صورت نامعتبر بودن مقدار، null برگردانده می‌شود.
     */
    private function jalaliToCarbon(?string $value): ?Carbon
    {
        $value = str_replace(
            ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹', '٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩', '-', '.'],
            ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '/', '/'],
            trim((string) $value)
        );

        if ($value === '') {
            return null;
        }

        try {
            return Jalalian::fromFormat('Y/m/d', $value)->toCarbon();
        } catch (\Throwable) {
            return null;
        }
    }

    public function assignContractor(Request $request, SolarPlantRequest $solarPlantRequest): RedirectResponse|JsonResponse
    {
        abort_unless(
            SolarPlantRequest::userHasRole($request->user(), 'leader'),
            403,
            'فقط کاربر دارای نقش راهبر می‌تواند پیمانکار تخصیص دهد.'
        );

        $validated = $request->validate([
            'contractor_id' => ['required', 'integer', 'exists:users,id'],
        ]);

        $contractor = GetController::getById($validated['contractor_id']);

        abort_if(
            !$contractor,
            422,
            'پیمانکار انتخابی معتبر نیست.'
        );

        $solarPlantRequest->fill([
            'contractor_id' => $validated['contractor_id'],
            'contractor_name' => $contractor->name,
            'status' => SolarPlantRequestStatus::EQUIPMENT_INSTALLATION,
        ]);

        $solarPlantRequest->save();

        if ($request->wantsJson()) {
            return response()->json(['data' => $solarPlantRequest->fresh()]);
        }

        return redirect()->route('solar-plant-requests.all-requests.index')->with('success', 'پیمانکار با موفقیت تخصیص شد.');
    }
}
