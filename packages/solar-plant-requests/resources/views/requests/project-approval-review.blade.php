<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" href="{{ url('behin/logo.ico') . '?' . config('app.version') }}">
    <title>بررسی و تایید پروژه - نیروگاه خورشیدی</title>
    <script src="{{ url('behin/behin-dist/dist/js/tailwind-3.4.17.min.js') }}"></script>
    <link href="{{ url('behin/behin-dist/css/css2.css') }}?family=Vazirmatn:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        html, body { font-family: 'Vazirmatn', sans-serif; }
        .container { max-width: 900px; margin-inline: auto; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 min-h-screen">

@php
use SolarPlantRequests\Enums\SolarPlantRequestStatus;
$details = $req->project_details ?? [];
@endphp

{{-- Header --}}
<header class="bg-gradient-to-l from-teal-500 via-cyan-400 to-sky-400 text-white">
    <div class="container px-6 py-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold">بررسی و تایید پروژه</h1>
            <p class="mt-1 text-sm opacity-80 font-mono" dir="ltr">{{ $req->unique_code }}</p>
        </div>
        <a href="{{ route('solar-plant-requests.index') }}"
           class="flex items-center gap-2 bg-white/20 hover:bg-white/30 text-white px-4 py-2.5 rounded-lg font-semibold text-sm transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            بازگشت
        </a>
    </div>
</header>

<main class="container px-6 py-8 space-y-6">

    {{-- اطلاعیه مهم --}}
    <div class="bg-amber-50 border border-amber-300 rounded-xl px-5 py-4 flex gap-3">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
        <div>
            <p class="font-bold text-amber-800 text-sm mb-1">توجه: تایید پروژه الزامی است</p>
            <p class="text-amber-700 text-sm leading-relaxed">راهبر سیستم جزئیات پروژه نیروگاه خورشیدی شما را تعریف کرده است. لطفاً اطلاعات زیر را با دقت مطالعه کنید و در صورت موافقت تایید نمایید. در صورت مغایرت، می‌توانید با ذکر دلیل آن را رد کنید.</p>
        </div>
    </div>

    {{-- اطلاعات درخواست --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h2 class="font-bold text-lg text-gray-800 mb-4 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-teal-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            اطلاعات متقاضی
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
            <div class="flex justify-between py-2 border-b border-gray-50">
                <span class="text-gray-500">نام:</span>
                <span class="font-semibold text-gray-800">
                    @if ($req->applicant_type->value === 'company')
                        {{ $req->company_name }}
                    @else
                        {{ $req->first_name }} {{ $req->last_name }}
                    @endif
                </span>
            </div>
            <div class="flex justify-between py-2 border-b border-gray-50">
                <span class="text-gray-500">کد پیگیری:</span>
                <span class="font-mono font-semibold text-gray-800" dir="ltr">{{ $req->unique_code }}</span>
            </div>
            @if ($req->province)
            <div class="flex justify-between py-2 border-b border-gray-50">
                <span class="text-gray-500">محل اجرا:</span>
                <span class="font-semibold text-gray-800">{{ $req->province }}{{ $req->city ? ' / ' . $req->city : '' }}</span>
            </div>
            @endif
            @if ($req->capacity_kw)
            <div class="flex justify-between py-2 border-b border-gray-50">
                <span class="text-gray-500">ظرفیت نیروگاه:</span>
                <span class="font-semibold text-gray-800" dir="ltr">{{ $req->capacity_kw }} کیلووات</span>
            </div>
            @endif
            @if ($req->selected_package_title)
            <div class="flex justify-between py-2 border-b border-gray-50">
                <span class="text-gray-500">پکیج انتخابی:</span>
                <span class="font-semibold text-gray-800">{{ $req->selected_package_title }}</span>
            </div>
            @endif
            @if ($req->selected_package_price)
            <div class="flex justify-between py-2 border-b border-gray-50">
                <span class="text-gray-500">هزینه پکیج:</span>
                <span class="font-semibold text-amber-700" dir="ltr">{{ number_format($req->selected_package_price) }} ریال</span>
            </div>
            @endif
            @if ($req->contractor_name)
            <div class="flex justify-between py-2 border-b border-gray-50">
                <span class="text-gray-500">پیمانکار مجری:</span>
                <span class="font-semibold text-gray-800">{{ $req->contractor_name }}</span>
            </div>
            @endif
        </div>
    </div>

    {{-- جزئیات پروژه --}}
    <div class="bg-white rounded-2xl shadow-sm border border-teal-100 p-6">
        <h2 class="font-bold text-lg text-gray-800 mb-5 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-teal-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0119 9.414V19a2 2 0 01-2 2z"/>
            </svg>
            جزئیات پروژه
            <span class="mr-auto text-xs bg-teal-100 text-teal-700 px-2.5 py-1 rounded-full font-semibold">تعریف‌شده توسط راهبر</span>
        </h2>

        @if (!empty($details))
        <div class="space-y-4">

            {{-- عنوان --}}
            @if (!empty($details['title']))
            <div class="bg-teal-50 rounded-xl px-5 py-4">
                <p class="text-xs text-teal-600 mb-1 font-semibold">عنوان پروژه</p>
                <p class="text-gray-800 font-bold text-base">{{ $details['title'] }}</p>
            </div>
            @endif

            {{-- تاریخ‌ها و هزینه --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                @if (!empty($details['start_date']))
                <div class="bg-gray-50 rounded-xl px-4 py-3 text-center">
                    <p class="text-xs text-gray-500 mb-1">تاریخ شروع</p>
                    <p class="font-bold text-gray-800">{{ $details['start_date'] }}</p>
                </div>
                @endif
                @if (!empty($details['end_date']))
                <div class="bg-gray-50 rounded-xl px-4 py-3 text-center">
                    <p class="text-xs text-gray-500 mb-1">تاریخ پایان (تخمینی)</p>
                    <p class="font-bold text-gray-800">{{ $details['end_date'] }}</p>
                </div>
                @endif
                @if (!empty($details['cost']))
                <div class="bg-amber-50 rounded-xl px-4 py-3 text-center">
                    <p class="text-xs text-amber-600 mb-1">هزینه اجرا</p>
                    <p class="font-bold text-amber-800" dir="ltr">{{ number_format((int)$details['cost']) }} ریال</p>
                </div>
                @endif
            </div>

            {{-- توضیحات --}}
            @if (!empty($details['description']))
            <div class="bg-gray-50 rounded-xl px-5 py-4">
                <p class="text-xs text-gray-500 mb-2 font-semibold">توضیحات پروژه</p>
                <p class="text-gray-700 text-sm leading-relaxed whitespace-pre-line">{{ $details['description'] }}</p>
            </div>
            @endif

            {{-- مراحل اجرا --}}
            @if (!empty($details['phases']))
            <div class="bg-gray-50 rounded-xl px-5 py-4">
                <p class="text-xs text-gray-500 mb-2 font-semibold">مراحل اجرا</p>
                <p class="text-gray-700 text-sm leading-relaxed whitespace-pre-line">{{ $details['phases'] }}</p>
            </div>
            @endif

            {{-- زمان ارسال --}}
            @if (!empty($details['submitted_at']))
            <p class="text-xs text-gray-400 text-left" dir="ltr">
                ارسال توسط راهبر: {{ $details['submitted_at'] }}
            </p>
            @endif

        </div>
        @else
        <div class="text-center py-8 text-gray-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-3 opacity-40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0119 9.414V19a2 2 0 01-2 2z"/>
            </svg>
            <p>جزئیات پروژه هنوز تکمیل نشده است.</p>
        </div>
        @endif
    </div>

    {{-- نتیجه قبلی (اگر قبلاً رد شده بود) --}}
    @if ($req->project_approval_status === 'rejected' && $req->project_rejection_reason)
    <div class="bg-red-50 border border-red-200 rounded-xl px-5 py-4">
        <p class="font-bold text-red-700 text-sm mb-1 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            دلیل رد قبلی شما
        </p>
        <p class="text-red-700 text-sm leading-relaxed">{{ $req->project_rejection_reason }}</p>
    </div>
    @endif

    {{-- دکمه‌های تایید / رد --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-bold text-gray-800 mb-4">اقدام شما</h3>
        <div class="flex flex-wrap gap-4">

            {{-- دکمه تایید --}}
            <form method="POST" action="{{ route('solar-plant-requests.project-approval.approve', $req) }}"
                  onsubmit="return confirm('آیا از تایید این پروژه اطمینان دارید؟')">
                @csrf
                <button type="submit"
                        class="inline-flex items-center gap-2 bg-teal-600 hover:bg-teal-700 text-white px-6 py-3 rounded-xl font-bold text-sm transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    تایید پروژه و شروع اجرا
                </button>
            </form>

            {{-- دکمه رد --}}
            <button type="button" onclick="document.getElementById('reject-modal').classList.remove('hidden')"
                    class="inline-flex items-center gap-2 bg-red-50 border border-red-300 hover:bg-red-100 text-red-700 px-6 py-3 rounded-xl font-bold text-sm transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                رد پروژه
            </button>

        </div>
    </div>

</main>

{{-- مودال رد پروژه --}}
<div id="reject-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6">
        <h3 class="font-bold text-lg text-gray-800 mb-1">رد پروژه</h3>
        <p class="text-sm text-gray-500 mb-5">لطفاً دلیل رد پروژه را با دقت بنویسید. راهبر سیستم پروژه را اصلاح و مجدداً ارسال خواهد کرد.</p>

        <form method="POST" action="{{ route('solar-plant-requests.project-approval.reject', $req) }}">
            @csrf
            <div class="mb-4">
                <label for="rejection_reason" class="block text-sm font-semibold text-gray-700 mb-2">
                    دلیل رد <span class="text-red-500">*</span>
                </label>
                <textarea id="rejection_reason"
                          name="rejection_reason"
                          rows="4"
                          minlength="10"
                          maxlength="1000"
                          required
                          placeholder="مثال: هزینه اجرا با قرارداد مغایرت دارد، تاریخ شروع مناسب نیست، ..."
                          class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-400 resize-none"></textarea>
                <p class="mt-1 text-xs text-gray-400">حداقل ۱۰ کاراکتر</p>
            </div>

            @if ($errors->has('rejection_reason'))
            <p class="text-red-600 text-sm mb-3">{{ $errors->first('rejection_reason') }}</p>
            @endif

            <div class="flex gap-3 justify-end">
                <button type="button" onclick="document.getElementById('reject-modal').classList.add('hidden')"
                        class="px-5 py-2.5 rounded-lg text-sm font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 transition">
                    انصراف
                </button>
                <button type="submit"
                        class="px-5 py-2.5 rounded-lg text-sm font-semibold text-white bg-red-600 hover:bg-red-700 transition">
                    ثبت رد پروژه
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// اگر validation error برگشت، مودال را دوباره باز کن
@if ($errors->has('rejection_reason'))
document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('reject-modal').classList.remove('hidden');
});
@endif
</script>

</body>
</html>
