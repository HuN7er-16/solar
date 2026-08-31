<?php

namespace SolarPlantRequests\Http\Controllers;

use Behin\Sms\Controllers\SmsController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use SolarPlantRequests\Enums\SolarPlantRequestStatus;
use SolarPlantRequests\Http\Requests\StoreSolarPlantRequestRequest;
use SolarPlantRequests\Models\SolarPlantRequest;
use SolarPlantRequests\Http\Controllers\PanelManufacturer\GetController as PanelManufacturerGetController;
use SolarPlantRequests\Http\Controllers\InverterManufacturer\GetController as InverterManufacturerGetController;
use SolarPlantRequests\Http\Controllers\BatteryManufacturer\GetController as BatteryManufacturerGetController;

class SolarPlantRequestController
{
    public function index(Request $request): View|JsonResponse
    {
        $user = $request->user();

        $requests = SolarPlantRequest::query()
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        if ($request->wantsJson()) {
            return response()->json(['data' => $requests]);
        }

        return view('solar-plant-requests::requests.my-requests', [
            'requests' => $requests,
        ]);
    }

    public function apply(): View
    {
        return view('solar-plant-requests::requests.apply');
    }

    public function store(StoreSolarPlantRequestRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $imagePaths = [];
        foreach ($request->file('images', []) as $file) {
            if ($file && $file->isValid()) {
                $imagePaths[] = $file->store('solar-plant-requests/images', 'public');
            }
        }

        $documentPaths = [];
        foreach ($request->file('documents', []) as $file) {
            if ($file && $file->isValid()) {
                $documentPaths[] = $file->store('solar-plant-requests/documents', 'public');
            }
        }

        unset($validated['images'], $validated['documents']);

        $solarPlantRequest = SolarPlantRequest::create([
            ...$validated,
            'user_id'   => $request->user()->id,
            'status'    => SolarPlantRequestStatus::INITIAL,
            'images'    => $imagePaths ?: null,
            'documents' => $documentPaths ?: null,
        ]);

        try {
            $mobile = function_exists('convertPersianToEnglish')
                ? convertPersianToEnglish($solarPlantRequest->mobile)
                : $solarPlantRequest->mobile;

            $message = "سامانه جامع اتحادیه سوخت‌های جایگزین و خدمات وابسته\nدرخواست نیروگاه خورشیدی شما با کد {$solarPlantRequest->unique_code} با موفقیت در سامانه ثبت شد.\nجهت پیگیری وضعیت از پنل کاربری خود وارد شوید.";

            if ($mobile) {
                SmsController::send($mobile, $message);
            }
        } catch (\Throwable $e) {
            Log::warning('ارسال اس‌ام‌اس تایید ثبت درخواست ناموفق بود.', [
                'request_id'  => $solarPlantRequest->id,
                'unique_code' => $solarPlantRequest->unique_code,
                'mobile'      => $solarPlantRequest->mobile,
                'error'       => $e->getMessage(),
            ]);
        }

        return redirect()
            ->route('solar-plant-requests.index')
            ->with('status', 'درخواست شما با موفقیت ثبت شد. کد پیگیری شما در پنل کاربری قابل مشاهده است.');
    }

    public function assignContractor(Request $request, SolarPlantRequest $solarPlantRequest): JsonResponse
    {
        abort_unless(
            SolarPlantRequest::userHasRole($request->user(), 'leader'),
            403,
            'فقط کاربر دارای نقش راهبر می‌تواند پیمانکار تخصیص دهد.'
        );

        $validated = $request->validate([
            'contractor_id' => ['required', 'integer', 'exists:users,id'],
            'contractor_name' => ['required', 'string', 'max:255'],
        ]);

        $solarPlantRequest->fill([
            'contractor_id' => $validated['contractor_id'],
            'contractor_name' => $validated['contractor_name'],
            'status' => SolarPlantRequestStatus::EQUIPMENT_INSTALLATION,
        ]);

        $solarPlantRequest->save();

        return response()->json($solarPlantRequest->fresh());
    }

    public function downloadFile(Request $request): \Symfony\Component\HttpFoundation\StreamedResponse|\Illuminate\Http\Response
    {
        $path = $request->query('path', '');

        if (!$path || !str_starts_with($path, 'solar-plant-requests/')) {
            abort(403);
        }

        if (!Storage::disk('public')->exists($path)) {
            abort(404, 'فایل یافت نشد.');
        }

        return Storage::disk('public')->download($path, basename($path));
    }

    public function detail(Request $request, SolarPlantRequest $solarPlantRequest): View
    {
        abort_unless(
            $solarPlantRequest->user_id === $request->user()->id
            || SolarPlantRequest::userHasRole($request->user(), 'leader'),
            403,
            'شما دسترسی به این درخواست ندارید.'
        );

        return view('solar-plant-requests::requests.detail', [
            'req' => $solarPlantRequest,
        ]);
    }

    public function show(Request $request, SolarPlantRequest $solarPlantRequest): View
    {
        abort_unless(
            $solarPlantRequest->status === SolarPlantRequestStatus::CERTIFICATE_ISSUED,
            403,
            'درخواست در مرحله صدور گواهی سلامت نیست'
        );

        abort_unless(
            $solarPlantRequest->user_id === $request->user()->id,
            403,
            'شما دسترسی به این ادرس ندارید'
        );

        return view('solar-plant-requests::requests.certificate',[
            'solarPlantRequest' => $solarPlantRequest,
            'manufacturers' => PanelManufacturerGetController::getAll(),
            'inverterManufacturers' => InverterManufacturerGetController::getAll(),
            'batteryManufacturers' => BatteryManufacturerGetController::getAll(),
        ]);
    }
}
