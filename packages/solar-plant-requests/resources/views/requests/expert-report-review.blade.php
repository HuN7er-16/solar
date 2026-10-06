<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" href="{{ url('behin/logo.ico') . '?' . config('app.version') }}">
    <title>بررسی گزارش کارشناسی - نیروگاه خورشیدی</title>
    <script src="{{ url('behin/behin-dist/dist/js/tailwind-3.4.17.min.js') }}"></script>
    <link href="{{ url('behin/behin-dist/css/css2.css') }}?family=Vazirmatn:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        html, body { font-family: 'Vazirmatn', sans-serif; }
        .container { max-width: 900px; margin-inline: auto; }
        .info-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        @media (max-width: 640px) { .info-row { grid-template-columns: 1fr; } }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 min-h-screen">

@php
use SolarPlantRequests\Enums\SolarPlantRequestStatus;
@endphp

{{-- Header --}}
<header class="bg-gradient-to-l from-amber-400 via-yellow-300 to-lime-300 text-gray-900">
    <div class="container px-6 py-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold">بررسی گزارش کارشناسی</h1>
            <p class="mt-1 text-sm font-mono" dir="ltr">{{ $req->unique_code }}</p>
        </div>
        <a href="{{ route('solar-plant-requests.index') }}"
           class="flex items-center gap-2 bg-gray-900 text-white px-5 py-2.5 rounded-lg font-semibold text-sm hover:bg-gray-700 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            بازگشت
        </a>
    </div>
</header>

<main class="container px-6 py-8 space-y-5">

    {{-- راهنما --}}
    <div class="bg-indigo-50 border border-indigo-200 rounded-xl p-4 flex gap-3 text-sm text-indigo-800">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p>گزارش بازدید اولیه کارشناس برای تقاضای شما آماده شده است. لطفاً اطلاعات زیر را بررسی کنید و در صورت تایید، مراحل بعدی را ادامه دهید.</p>
    </div>

    @if (!$visit)
    {{-- گزارشی ثبت نشده --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 px-8 py-12 text-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-14 w-14 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0119 9.414V19a2 2 0 01-2 2z"/>
        </svg>
        <p class="text-gray-500">هنوز گزارش بازدید اولیه برای این تقاضا ثبت نشده است.</p>
    </div>
    @else

    {{-- ─── اطلاعات کلی بازدید ─── --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h2 class="font-bold text-base mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
            <span class="text-lg">📋</span> اطلاعات کلی بازدید
        </h2>
        <div class="info-row text-sm">
            <div>
                <p class="text-gray-400 mb-0.5">تاریخ بازدید</p>
                <p class="font-medium">{{ $visit->visit_date_jalali ?? '—' }}</p>
            </div>
            <div>
                <p class="text-gray-400 mb-0.5">نتیجه ارزیابی</p>
                <p class="font-medium">{!! $visit->assessment_result_label !!}</p>
            </div>
        </div>
        @if ($visit->expert_summary)
        <div class="mt-4 p-4 bg-gray-50 rounded-xl text-sm text-gray-700 leading-relaxed">
            <p class="font-semibold text-gray-600 mb-1">جمع‌بندی کارشناس:</p>
            {{ $visit->expert_summary }}
        </div>
        @endif
        @if ($visit->assessment_result === 'not_feasible' && $visit->not_feasible_reason)
        <div class="mt-3 p-4 bg-red-50 rounded-xl text-sm text-red-700">
            <p class="font-semibold mb-1">علت عدم امکان اجرا:</p>
            {{ $visit->not_feasible_reason }}
        </div>
        @endif
    </div>

    {{-- ─── احراز محل ─── --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h2 class="font-bold text-base mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
            <span class="text-lg">📍</span> احراز محل
        </h2>
        <div class="info-row text-sm">
            <div>
                <p class="text-gray-400 mb-0.5">مطابقت محل با اعلام متقاضی</p>
                <p class="font-medium">{{ $visit->location_matches ? 'بله، مطابقت دارد' : 'خیر، مطابقت ندارد' }}</p>
            </div>
            <div>
                <p class="text-gray-400 mb-0.5">تایید فیزیکی محل</p>
                <p class="font-medium">{{ $visit->location_physically_confirmed ? 'تایید شد' : 'تایید نشد' }}</p>
            </div>
            @if ($visit->location_access)
            <div>
                <p class="text-gray-400 mb-0.5">دسترسی به محل</p>
                <p class="font-medium">{{ ['easy' => 'آسان', 'medium' => 'متوسط', 'hard' => 'سخت'][$visit->location_access] ?? $visit->location_access }}</p>
            </div>
            @endif
            @if ($visit->actual_address)
            <div class="col-span-2">
                <p class="text-gray-400 mb-0.5">آدرس واقعی</p>
                <p class="font-medium">{{ $visit->actual_address }}</p>
            </div>
            @endif
        </div>
    </div>

    {{-- ─── وضعیت محل و فضا ─── --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h2 class="font-bold text-base mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
            <span class="text-lg">🏗️</span> وضعیت محل و فضا
        </h2>
        <div class="info-row text-sm">
            <div>
                <p class="text-gray-400 mb-0.5">فضای مناسب برای احداث</p>
                <p class="font-medium">{{ $visit->suitable_space_exists ? 'وجود دارد' : 'وجود ندارد' }}</p>
            </div>
            @if ($visit->total_area_sqm)
            <div>
                <p class="text-gray-400 mb-0.5">مساحت کل محل</p>
                <p class="font-medium">{{ $visit->total_area_sqm }} متر مربع</p>
            </div>
            @endif
            @if ($visit->usable_area_sqm)
            <div>
                <p class="text-gray-400 mb-0.5">مساحت قابل استفاده</p>
                <p class="font-medium">{{ $visit->usable_area_sqm }} متر مربع</p>
            </div>
            @endif
            @if ($visit->installation_location_type)
            @php
            $locTypes = [
                'flat_roof'      => 'پشت‌بام تخت',
                'sloped_roof'    => 'پشت‌بام شیبدار',
                'ground'         => 'زمین',
                'parking_canopy' => 'سایبان پارکینگ',
                'other'          => 'سایر',
            ];
            @endphp
            <div>
                <p class="text-gray-400 mb-0.5">نوع محل نصب</p>
                <p class="font-medium">{{ $locTypes[$visit->installation_location_type] ?? $visit->installation_location_type }}
                    @if ($visit->installation_location_type === 'other' && $visit->installation_location_type_other)
                        ({{ $visit->installation_location_type_other }})
                    @endif
                </p>
            </div>
            @endif
            @if ($visit->physical_obstacle_exists)
            <div>
                <p class="text-gray-400 mb-0.5">موانع فیزیکی</p>
                <p class="font-medium text-orange-700">وجود دارد</p>
                @if ($visit->obstacle_notes)
                    <p class="text-xs text-gray-500 mt-0.5">{{ $visit->obstacle_notes }}</p>
                @endif
            </div>
            @endif
        </div>
    </div>

    {{-- ─── سطح نصب پنل ─── --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h2 class="font-bold text-base mb-4 pb-2 border-b border-gray-100 flex items-center gap-2">
            <span class="text-lg">☀️</span> سطح نصب پنل
        </h2>
        <div class="info-row text-sm">
            @if ($visit->panel_direction)
            @php
            $directions = ['south'=>'جنوب','southeast'=>'جنوب‌شرق','southwest'=>'جنوب‌غرب','east'=>'شرق','west'=>'غرب','north'=>'شمال','other'=>'سایر'];
            @endphp
            <div>
                <p class="text-gray-400 mb-0.5">جهت نصب پنل</p>
                <p class="font-medium">{{ $directions[$visit->panel_direction] ?? $visit->panel_direction }}</p>
            </div>
            @endif
            @if ($visit->shading_level)
            @php $shadingLabels = ['none'=>'بدون سایه','low'=>'کم','medium'=>'متوسط','high'=>'زیاد']; @endphp
            <div>
                <p class="text-gray-400 mb-0.5">میزان سایه‌اندازی</p>
                <p class="font-medium {{ $visit->shading_level === 'high' ? 'text-red-700' : '' }}">
                    {{ $shadingLabels[$visit->shading_level] ?? $visit->shading_level }}
                </p>
            </div>
            @endif
            @if ($visit->surface_condition)
            @php $scLabels = ['suitable'=>'مناسب','unsuitable'=>'نامناسب','suitable_with_fix'=>'مناسب با اصلاح']; @endphp
            <div>
                <p class="text-gray-400 mb-0.5">وضعیت سطح برای نصب</p>
                <p class="font-medium">{{ $scLabels[$visit->surface_condition] ?? $visit->surface_condition }}</p>
            </div>
            @endif
            @if ($visit->overall_risk_level)
            @php $riskLabels = ['low'=>'کم','medium'=>'متوسط','high'=>'زیاد']; @endphp
            <div>
                <p class="text-gray-400 mb-0.5">سطح ریسک کلی</p>
                <p class="font-medium {{ $visit->overall_risk_level === 'high' ? 'text-red-700' : ($visit->overall_risk_level === 'medium' ? 'text-orange-700' : 'text-green-700') }}">
                    {{ $riskLabels[$visit->overall_risk_level] ?? $visit->overall_risk_level }}
                </p>
            </div>
            @endif
        </div>
    </div>

    {{-- ─── ظرفیت پیشنهادی کارشناس ─── --}}
    <div class="bg-white rounded-2xl shadow-sm border border-amber-200 p-6">
        <h2 class="font-bold text-base mb-4 pb-2 border-b border-amber-100 flex items-center gap-2">
            <span class="text-lg">⚡</span> ظرفیت پیشنهادی کارشناس
        </h2>
        <div class="info-row text-sm">
            @if ($visit->applicant_requested_capacity_kw)
            <div>
                <p class="text-gray-400 mb-0.5">ظرفیت درخواستی متقاضی</p>
                <p class="font-medium">{{ $visit->applicant_requested_capacity_kw }} کیلووات</p>
            </div>
            @endif
            @if ($visit->expert_proposed_capacity_kw)
            <div>
                <p class="text-gray-400 mb-0.5">ظرفیت پیشنهادی کارشناس</p>
                <p class="font-bold text-amber-700 text-base">{{ $visit->expert_proposed_capacity_kw }} کیلووات</p>
            </div>
            @endif
            @if ($visit->installable_capacity_kw)
            <div>
                <p class="text-gray-400 mb-0.5">ظرفیت قابل نصب (بر اساس فضا)</p>
                <p class="font-medium">{{ $visit->installable_capacity_kw }} کیلووات</p>
            </div>
            @endif
            @if ($visit->expert_proposed_inverter_kw)
            <div>
                <p class="text-gray-400 mb-0.5">ظرفیت پیشنهادی اینورتر</p>
                <p class="font-medium">{{ $visit->expert_proposed_inverter_kw }} کیلووات</p>
            </div>
            @endif
            @if ($visit->battery_required)
            <div>
                <p class="text-gray-400 mb-0.5">نیاز به باتری</p>
                <p class="font-medium">بله — {{ $visit->expert_proposed_battery_kwh ?? '—' }} کیلووات‌ساعت</p>
            </div>
            @endif
            @if ($visit->capacity_difference_reason)
            <div style="grid-column: span 2">
                <p class="text-gray-400 mb-0.5">علت تفاوت ظرفیت</p>
                <p class="font-medium leading-relaxed">{{ $visit->capacity_difference_reason }}</p>
            </div>
            @endif
        </div>
    </div>

    {{-- ─── اصلاحات پیش از اجرا ─── --}}
    @if ($visit->pre_execution_fix_needed)
    <div class="bg-orange-50 border border-orange-200 rounded-2xl p-6">
        <h2 class="font-bold text-base mb-3 flex items-center gap-2 text-orange-800">
            <span class="text-lg">⚠️</span> اصلاحات مورد نیاز پیش از اجرا
        </h2>
        @if ($visit->pre_execution_fix_description)
        <p class="text-sm text-orange-700 leading-relaxed">{{ $visit->pre_execution_fix_description }}</p>
        @endif
    </div>
    @endif

    @endif {{-- /if $visit --}}

    {{-- ─── دکمه‌های تایید / رد ─── --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h2 class="font-bold text-base mb-4">اقدام شما</h2>

        <div class="flex flex-col sm:flex-row gap-4">

            {{-- تایید --}}
            <form method="POST"
                  action="{{ route('solar-plant-requests.expert-report.approve', $req) }}"
                  class="flex-1"
                  onsubmit="return confirm('آیا گزارش کارشناسی را تایید می‌کنید؟ پس از تایید وارد مرحله انتخاب پکیج می‌شوید.')">
                @csrf
                <button type="submit"
                        class="w-full inline-flex items-center justify-center gap-2 bg-green-600 text-white px-6 py-3 rounded-xl font-bold text-sm hover:bg-green-700 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    تایید گزارش و ادامه
                </button>
            </form>

            {{-- رد --}}
            <button type="button"
                    onclick="document.getElementById('rejectModal').classList.remove('hidden')"
                    class="flex-1 inline-flex items-center justify-center gap-2 bg-white border-2 border-red-400 text-red-600 px-6 py-3 rounded-xl font-bold text-sm hover:bg-red-50 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                رد گزارش و بازگشت به کارشناس
            </button>

        </div>
    </div>

</main>

{{-- Modal رد گزارش --}}
<div id="rejectModal"
     class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
        <h3 class="font-bold text-lg mb-1 text-red-700">رد گزارش کارشناسی</h3>
        <p class="text-sm text-gray-500 mb-4">لطفاً دلیل رد گزارش را برای کارشناس شرح دهید.</p>

        <form method="POST" action="{{ route('solar-plant-requests.expert-report.reject', $req) }}">
            @csrf
            <textarea name="rejection_reason"
                      rows="4"
                      required
                      minlength="10"
                      placeholder="مثال: ظرفیت پیشنهادی با نیاز من تطابق ندارد..."
                      class="w-full border border-gray-300 rounded-xl p-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-400 resize-none mb-4">{{ old('rejection_reason') }}</textarea>

            @error('rejection_reason')
            <p class="text-red-600 text-xs mb-3">{{ $message }}</p>
            @enderror

            <div class="flex gap-3">
                <button type="submit"
                        class="flex-1 bg-red-600 text-white px-4 py-2.5 rounded-lg font-bold text-sm hover:bg-red-700 transition">
                    ارسال و رد گزارش
                </button>
                <button type="button"
                        onclick="document.getElementById('rejectModal').classList.add('hidden')"
                        class="flex-1 border border-gray-300 text-gray-700 px-4 py-2.5 rounded-lg font-semibold text-sm hover:bg-gray-50 transition">
                    انصراف
                </button>
            </div>
        </form>
    </div>
</div>

{{-- نمایش خودکار modal در صورت validation error --}}
@error('rejection_reason')
<script>document.getElementById('rejectModal').classList.remove('hidden');</script>
@enderror

<footer class="bg-gray-900 text-gray-100 mt-12">
    <div class="container px-6 py-6 flex flex-col md:flex-row items-center justify-between gap-4 text-sm">
        <div>اتحادیه کشوری سوخت‌های جایگزین و انرژی‌های تجدیدپذیر</div>
        <div><span>ایمیل: info@altfuel.ir</span></div>
    </div>
</footer>

</body>
</html>
