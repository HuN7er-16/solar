<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" href="{{ url('behin/logo.ico') . '?' . config('app.version') }}">
    <title>انتخاب پیمانکار - نیروگاه خورشیدی</title>
    <script src="{{ url('behin/behin-dist/dist/js/tailwind-3.4.17.min.js') }}"></script>
    <link href="{{ url('behin/behin-dist/css/css2.css') }}?family=Vazirmatn:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        html, body { font-family: 'Vazirmatn', sans-serif; }
        .container { max-width: 900px; margin-inline: auto; }

        .cnt-card {
            cursor: pointer;
            border: 2px solid #E5E7EB;
            border-radius: 1rem;
            transition: border-color 0.15s, box-shadow 0.15s, background-color 0.15s;
        }
        .cnt-card:hover { border-color: #8B5CF6; box-shadow: 0 4px 14px rgba(139,92,246,0.15); }
        .cnt-card.selected { border-color: #8B5CF6; background-color: #F5F3FF; box-shadow: 0 4px 20px rgba(139,92,246,0.2); }
        .cnt-card input[type="radio"] { display: none; }

        .check-badge {
            width: 1.5rem; height: 1.5rem; border-radius: 50%;
            border: 2px solid #D1D5DB;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            transition: background-color 0.15s, border-color 0.15s;
        }
        .cnt-card.selected .check-badge { background-color: #8B5CF6; border-color: #8B5CF6; }
        .check-badge svg { display: none; }
        .cnt-card.selected .check-badge svg { display: block; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 min-h-screen">

{{-- Header --}}
<header class="bg-gradient-to-l from-purple-400 via-violet-400 to-indigo-400 text-white">
    <div class="container px-6 py-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold">انتخاب پیمانکار</h1>
            <p class="mt-1 text-sm opacity-80 font-mono" dir="ltr">{{ $req->unique_code }}</p>
        </div>
        <a href="{{ route('solar-plant-requests.index') }}"
           class="flex items-center gap-2 bg-white/20 text-white px-5 py-2.5 rounded-lg font-semibold text-sm hover:bg-white/30 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            بازگشت
        </a>
    </div>
</header>

<main class="container px-6 py-8">

    @if ($errors->any())
    <div class="mb-6 rounded-xl bg-red-50 border border-red-200 text-red-800 px-4 py-3 text-sm">
        <ul class="list-disc list-inside space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- پکیج انتخاب‌شده --}}
    @if ($req->selected_package_title)
    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-6 flex flex-wrap items-center gap-4 text-sm">
        <div class="flex items-center gap-2 text-amber-800">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
            </svg>
            <span class="font-semibold">پکیج انتخابی:</span>
            <span>{{ $req->selected_package_title }}</span>
        </div>
        @if ($req->selected_package_price)
        <span class="font-bold text-amber-700" dir="ltr">{{ number_format($req->selected_package_price) }} ریال</span>
        @endif
    </div>
    @endif

    {{-- فیلتر استان --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-6">
        <div class="flex items-center gap-2 mb-3 text-sm font-semibold text-gray-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            فیلتر بر اساس استان
        </div>
        <form method="GET"
              action="{{ route('solar-plant-requests.contractor-selection', $req) }}"
              class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-40">
                <select name="province"
                        onchange="this.form.submit()"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-400">
                    <option value="">همه استان‌ها</option>
                    @foreach ($provinces as $province)
                    <option value="{{ $province }}" {{ $selectedProvince === $province ? 'selected' : '' }}>
                        {{ $province }}
                    </option>
                    @endforeach
                </select>
            </div>
            @if ($selectedProvince && $selectedProvince !== $req->province)
            <a href="{{ route('solar-plant-requests.contractor-selection', $req) }}"
               class="text-xs text-gray-500 hover:text-red-500 transition">
                پاک کردن فیلتر
            </a>
            @endif
        </form>

        @if ($selectedProvince)
        <p class="mt-2 text-xs text-gray-400">
            نمایش پیمانکاران استان <span class="font-semibold text-gray-600">{{ $selectedProvince }}</span>
            @if ($selectedProvince === $req->province)
            <span class="text-purple-600">(استان تقاضای شما)</span>
            @endif
        </p>
        @endif
    </div>

    <form method="POST"
          action="{{ route('solar-plant-requests.contractor-selection.store', $req) }}"
          id="contractorForm">
        @csrf

        @if ($contractors->isEmpty())
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 px-8 py-12 text-center text-gray-400 mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 mb-3 opacity-40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <p class="mb-2">
                @if ($selectedProvince)
                    پیمانکاری در استان «{{ $selectedProvince }}» یافت نشد.
                @else
                    پیمانکاری در سیستم ثبت نشده است.
                @endif
            </p>
            @if ($selectedProvince)
            <a href="{{ route('solar-plant-requests.contractor-selection', $req) }}"
               class="text-sm text-purple-600 hover:underline">
                مشاهده پیمانکاران همه استان‌ها
            </a>
            @endif
        </div>
        @else

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
            @foreach ($contractors as $contractor)
            @php
                // نزدیک به انقضا (کمتر از ۳۰ روز)
                $daysLeft = $contractor->license_expiry_date
                    ? now()->diffInDays($contractor->license_expiry_date, false)
                    : null;
                $isExpired = $daysLeft !== null && $daysLeft <= 0;
                $isSoonExpire = $daysLeft !== null && $daysLeft > 0 && $daysLeft <= 30;
            @endphp
            <label class="cnt-card p-5 flex gap-4 {{ old('contractor_id') == $contractor->id ? 'selected' : '' }} {{ $isExpired ? 'opacity-50 pointer-events-none' : '' }}"
                   for="cnt_{{ $contractor->id }}"
                   onclick="selectContractor(this)">
                <input type="radio"
                       name="contractor_id"
                       id="cnt_{{ $contractor->id }}"
                       value="{{ $contractor->id }}"
                       {{ old('contractor_id') == $contractor->id ? 'checked' : '' }}
                       {{ $isExpired ? 'disabled' : '' }}>

                {{-- آواتار --}}
                <div class="w-11 h-11 rounded-xl bg-purple-100 flex items-center justify-center text-purple-700 font-bold text-base shrink-0">
                    {{ mb_substr($contractor->company_name, 0, 1) }}
                </div>

                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-2">
                        <p class="font-bold text-sm leading-snug">{{ $contractor->company_name }}</p>
                        <div class="check-badge mt-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                    </div>

                    <div class="mt-1.5 space-y-0.5 text-xs text-gray-500">
                        <p>{{ $contractor->province }} — {{ $contractor->city }}</p>
                        <p>مدیر عامل: {{ $contractor->ceo_name }}</p>
                        <p dir="ltr" class="text-right">{{ $contractor->contact_person_mobile }}</p>
                    </div>

                    <div class="mt-2 flex flex-wrap gap-1.5">
                        <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full">
                            {{ $contractor->registered_projects_count }} پروژه ثبت‌شده
                        </span>
                        @if ($isExpired)
                        <span class="text-xs bg-red-100 text-red-700 px-2 py-0.5 rounded-full">پروانه منقضی</span>
                        @elseif ($isSoonExpire)
                        <span class="text-xs bg-orange-100 text-orange-700 px-2 py-0.5 rounded-full">انقضا تا {{ $daysLeft }} روز</span>
                        @else
                        <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full">پروانه معتبر</span>
                        @endif
                    </div>
                </div>
            </label>
            @endforeach
        </div>

        @endif

        @error('contractor_id')
        <p class="mb-4 text-sm text-red-600">{{ $message }}</p>
        @enderror

        {{-- دکمه ثبت --}}
        <div class="flex items-center justify-between flex-wrap gap-4">
            <a href="{{ route('solar-plant-requests.index') }}"
               class="inline-flex items-center gap-2 border border-gray-300 text-gray-700 px-5 py-2.5 rounded-lg text-sm font-semibold hover:bg-gray-50 transition">
                انصراف
            </a>
            <button type="submit"
                    id="submitBtn"
                    disabled
                    class="inline-flex items-center gap-2 bg-purple-600 text-white px-8 py-2.5 rounded-lg text-sm font-bold hover:bg-purple-700 transition disabled:opacity-50 disabled:cursor-not-allowed">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                ثبت نهایی انتخاب پیمانکار
            </button>
        </div>

    </form>
</main>

<footer class="bg-gray-900 text-gray-100 mt-12">
    <div class="container px-6 py-6 flex flex-col md:flex-row items-center justify-between gap-4 text-sm">
        <div>اتحادیه کشوری سوخت‌های جایگزین و انرژی‌های تجدیدپذیر</div>
        <div><span>ایمیل: info@altfuel.ir</span></div>
    </div>
</footer>

<script>
    function selectContractor(card) {
        document.querySelectorAll('.cnt-card').forEach(c => c.classList.remove('selected'));
        card.classList.add('selected');
        card.querySelector('input[type="radio"]').checked = true;
        document.getElementById('submitBtn').disabled = false;
    }

    document.addEventListener('DOMContentLoaded', function () {
        const checked = document.querySelector('input[type="radio"]:checked');
        if (checked) {
            checked.closest('label')?.classList.add('selected');
            document.getElementById('submitBtn').disabled = false;
        }
    });
</script>
</body>
</html>
