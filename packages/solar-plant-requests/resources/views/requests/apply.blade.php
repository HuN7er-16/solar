<!doctype html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ url('behin/logo.ico') . '?' . config('app.version') }}">
    <title>ثبت درخواست نیروگاه خورشیدی</title>
    <script src="{{ url('behin/behin-dist/dist/js/tailwind-3.4.17.min.js') }}"></script>
    <link href="{{ url('behin/behin-dist/css/css2.css') }}?family=Vazirmatn:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        html,
        body {
            font-family: 'Vazirmatn', sans-serif;
        }

        .container {
            max-width: 900px;
            margin-inline: auto;
        }

        .step {
            display: none;
        }

        .step.active {
            display: block;
        }

        .step-indicator {
            transition: all 0.3s ease;
        }

        .step-indicator.active {
            background-color: #f59e0b;
            color: white;
        }

        .step-indicator.completed {
            background-color: #22c55e;
            color: white;
        }

        .step-connector {
            transition: background-color 0.3s ease;
        }

        .step-connector.active {
            background-color: #22c55e;
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-800 min-h-screen">
    <header class="bg-gradient-to-l from-amber-400 via-yellow-300 to-lime-300 text-gray-900">
        <div class="container px-6 py-8 flex items-center justify-between">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold">ثبت درخواست نیروگاه خورشیدی</h1>
                <p class="mt-2 text-sm md:text-base">لطفاً اطلاعات زیر را در ۴ مرحله تکمیل کنید.</p>
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

    <main class="container px-6 py-8">
        {{-- Progress Indicator --}}
        <div class="flex items-center justify-center mb-8">
            <div class="flex items-center gap-2">
                <div id="indicator-1" class="step-indicator active w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm border-2 border-amber-500">۱</div>
                <div id="connector-1" class="step-connector w-16 h-1 bg-gray-300 rounded"></div>
                <div id="indicator-2" class="step-indicator w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm border-2 border-gray-300 text-gray-400">۲</div>
                <div id="connector-2" class="step-connector w-16 h-1 bg-gray-300 rounded"></div>
                <div id="indicator-3" class="step-indicator w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm border-2 border-gray-300 text-gray-400">۳</div>
                <div id="connector-3" class="step-connector w-16 h-1 bg-gray-300 rounded"></div>
                <div id="indicator-4" class="step-indicator w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm border-2 border-gray-300 text-gray-400">۴</div>
            </div>
        </div>

        {{-- Step Labels --}}
        <div class="flex justify-between text-xs text-center mb-6 px-4">
            <div class="w-20">اطلاعات متقاضی</div>
            <div class="w-20">محل نصب</div>
            <div class="w-20">مشخصات فنی</div>
            <div class="w-20">مدارک و تصاویر</div>
        </div>

        {{-- Error Display --}}
        @if ($errors->any())
            <div class="mb-6 rounded-lg bg-red-50 border border-red-300 text-red-800 px-4 py-4 text-sm">
                <div class="flex items-center gap-2 font-semibold mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                    </svg>
                    لطفاً موارد زیر را تصحیح کنید:
                </div>
                <ul class="list-disc pr-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Client-side Error Display --}}
        <div id="client-errors" class="hidden mb-6 rounded-lg bg-red-50 border border-red-300 text-red-800 px-4 py-4 text-sm">
            <div class="flex items-center gap-2 font-semibold mb-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
                لطفاً موارد زیر را تصحیح کنید:
            </div>
            <ul class="list-disc pr-5 space-y-1"></ul>
        </div>

        {{-- Success Message --}}
        @if (session('status'))
            <div class="mb-6 rounded-lg bg-green-100 text-green-800 px-4 py-3 text-sm">
                {{ session('status') }}
            </div>
        @endif

        <form id="multi-step-form" method="POST" action="{{ route('solar-plant-requests.store') }}" enctype="multipart/form-data" class="bg-white rounded-2xl shadow-xl p-6 md:p-8">
            @csrf

            {{-- Step 1: Applicant Info --}}
            <div id="step-1" class="step active">
                <h2 class="text-xl font-bold mb-6">اطلاعات متقاضی</h2>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-3">نوع متقاضی</label>
                    <div class="grid grid-cols-3 gap-3">
                        <label class="relative cursor-pointer">
                            <input type="radio" name="applicant_type" value="individual" class="peer sr-only" checked>
                            <div class="border-2 border-gray-200 peer-checked:border-amber-500 peer-checked:bg-amber-50 rounded-lg p-4 text-center transition">
                                <div class="font-semibold">شخص حقیقی</div>
                                <div class="text-xs text-gray-500 mt-1">انسان عادی</div>
                            </div>
                        </label>
                        <label class="relative cursor-pointer">
                            <input type="radio" name="applicant_type" value="company" class="peer sr-only">
                            <div class="border-2 border-gray-200 peer-checked:border-amber-500 peer-checked:bg-amber-50 rounded-lg p-4 text-center transition">
                                <div class="font-semibold">شخص حقوقی</div>
                                <div class="text-xs text-gray-500 mt-1">شرکت</div>
                            </div>
                        </label>
                        <label class="relative cursor-pointer">
                            <input type="radio" name="applicant_type" value="foreigner" class="peer sr-only">
                            <div class="border-2 border-gray-200 peer-checked:border-amber-500 peer-checked:bg-amber-50 rounded-lg p-4 text-center transition">
                                <div class="font-semibold">اتباع</div>
                                <div class="text-xs text-gray-500 mt-1">خارجی</div>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Individual Fields --}}
                <div id="fields-individual" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-gray-700">نام <span class="text-red-500">*</span></label>
                        <input type="text" name="first_name" value="{{ old('first_name') }}" required data-label="نام"
                            class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500 @error('first_name') border-red-500 @enderror">
                        @error('first_name') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-gray-700">نام خانوادگی <span class="text-red-500">*</span></label>
                        <input type="text" name="last_name" value="{{ old('last_name') }}" required data-label="نام خانوادگی"
                            class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500 @error('last_name') border-red-500 @enderror">
                        @error('last_name') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-gray-700">کد ملی <span class="text-red-500">*</span></label>
                        <input type="text" name="national_code" value="{{ old('national_code') }}" dir="ltr" inputmode="numeric" maxlength="10"
                            required data-label="کد ملی" data-digits-only pattern="\d{10}"
                            data-pattern-error="کد ملی باید ۱۰ رقم و به صورت عدد باشد."
                            class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500 @error('national_code') border-red-500 @enderror">
                        @error('national_code') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-gray-700">شناسه قبض برق <span class="text-red-500">*</span></label>
                        <input type="text" name="bill_identifier" value="{{ old('bill_identifier') }}" dir="ltr" inputmode="numeric" maxlength="13"
                            required data-label="شناسه قبض برق" data-digits-only pattern="\d{13}"
                            data-pattern-error="شناسه قبض باید ۱۳ رقم و به صورت عدد باشد."
                            class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500 @error('bill_identifier') border-red-500 @enderror">
                        @error('bill_identifier') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-gray-700">شماره موبایل <span class="text-red-500">*</span></label>
                        <input type="text" name="mobile" value="{{ old('mobile') }}" dir="ltr" inputmode="numeric" maxlength="11"
                            required data-label="شماره موبایل" data-digits-only pattern="\d{11}"
                            data-pattern-error="شماره تلفن باید ۱۱ رقم و به صورت عدد باشد."
                            class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500 @error('mobile') border-red-500 @enderror">
                        @error('mobile') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-gray-700">تلفن ثابت (اختیاری)</label>
                        <input type="text" name="landline" value="{{ old('landline') }}" dir="ltr" inputmode="numeric" maxlength="11"
                            data-label="تلفن ثابت" data-digits-only pattern="\d{11}"
                            data-pattern-error="تلفن ثابت باید ۱۱ رقم و به صورت عدد باشد."
                            class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500">
                    </div>
                </div>

                {{-- Company Fields (disabled by default, enabled by JS when selected) --}}
                <div id="fields-company" class="hidden grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-gray-700">نام شرکت <span class="text-red-500">*</span></label>
                        <input type="text" name="company_name" value="{{ old('company_name') }}" disabled required data-label="نام شرکت"
                            class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500 @error('company_name') border-red-500 @enderror">
                        @error('company_name') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-gray-700">شماره ثبت شرکت <span class="text-red-500">*</span></label>
                        <input type="text" name="registration_number" value="{{ old('registration_number') }}" dir="ltr" disabled required data-label="شماره ثبت شرکت"
                            class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500 @error('registration_number') border-red-500 @enderror">
                        @error('registration_number') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-gray-700">شماره موبایل مدیرعامل <span class="text-red-500">*</span></label>
                        <input type="text" name="mobile" value="{{ old('mobile') }}" dir="ltr" inputmode="numeric" maxlength="11" disabled
                            required data-label="شماره موبایل" data-digits-only pattern="\d{11}"
                            data-pattern-error="شماره تلفن باید ۱۱ رقم و به صورت عدد باشد."
                            class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500 @error('mobile') border-red-500 @enderror">
                        @error('mobile') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-gray-700">شناسه قبض برق <span class="text-red-500">*</span></label>
                        <input type="text" name="bill_identifier" value="{{ old('bill_identifier') }}" dir="ltr" inputmode="numeric" maxlength="13" disabled
                            required data-label="شناسه قبض برق" data-digits-only pattern="\d{13}"
                            data-pattern-error="شناسه قبض باید ۱۳ رقم و به صورت عدد باشد."
                            class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500 @error('bill_identifier') border-red-500 @enderror">
                        @error('bill_identifier') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-gray-700">تلفن ثابت (اختیاری)</label>
                        <input type="text" name="landline" value="{{ old('landline') }}" dir="ltr" inputmode="numeric" maxlength="11" disabled
                            data-label="تلفن ثابت" data-digits-only pattern="\d{11}"
                            data-pattern-error="تلفن ثابت باید ۱۱ رقم و به صورت عدد باشد."
                            class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500">
                    </div>
                </div>

                {{-- Foreigner Fields (disabled by default, enabled by JS when selected) --}}
                <div id="fields-foreigner" class="hidden grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-gray-700">نام <span class="text-red-500">*</span></label>
                        <input type="text" name="first_name" value="{{ old('first_name') }}" disabled required data-label="نام"
                            class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500 @error('first_name') border-red-500 @enderror">
                        @error('first_name') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-gray-700">نام خانوادگی <span class="text-red-500">*</span></label>
                        <input type="text" name="last_name" value="{{ old('last_name') }}" disabled required data-label="نام خانوادگی"
                            class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500 @error('last_name') border-red-500 @enderror">
                        @error('last_name') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-gray-700">کد اتباع <span class="text-red-500">*</span></label>
                        <input type="text" name="immigration_code" value="{{ old('immigration_code') }}" dir="ltr" inputmode="numeric" maxlength="10" disabled
                            required data-label="کد اتباع" data-digits-only pattern="\d{10}"
                            data-pattern-error="کد اتباع باید ۱۰ رقم و به صورت عدد باشد."
                            class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500 @error('immigration_code') border-red-500 @enderror">
                        @error('immigration_code') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-gray-700">شناسه قبض برق <span class="text-red-500">*</span></label>
                        <input type="text" name="bill_identifier" value="{{ old('bill_identifier') }}" dir="ltr" inputmode="numeric" maxlength="13" disabled
                            required data-label="شناسه قبض برق" data-digits-only pattern="\d{13}"
                            data-pattern-error="شناسه قبض باید ۱۳ رقم و به صورت عدد باشد."
                            class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500 @error('bill_identifier') border-red-500 @enderror">
                        @error('bill_identifier') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-gray-700">شماره موبایل <span class="text-red-500">*</span></label>
                        <input type="text" name="mobile" value="{{ old('mobile') }}" dir="ltr" inputmode="numeric" maxlength="11" disabled
                            required data-label="شماره موبایل" data-digits-only pattern="\d{11}"
                            data-pattern-error="شماره تلفن باید ۱۱ رقم و به صورت عدد باشد."
                            class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500 @error('mobile') border-red-500 @enderror">
                        @error('mobile') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-gray-700">تلفن ثابت (اختیاری)</label>
                        <input type="text" name="landline" value="{{ old('landline') }}" dir="ltr" inputmode="numeric" maxlength="11" disabled
                            data-label="تلفن ثابت" data-digits-only pattern="\d{11}"
                            data-pattern-error="تلفن ثابت باید ۱۱ رقم و به صورت عدد باشد."
                            class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500">
                    </div>
                </div>

                <div class="flex justify-end mt-8">
                    <button type="button" onclick="nextStep(2)"
                        class="bg-amber-500 text-white px-8 py-3 rounded-lg font-semibold hover:bg-amber-600 transition">
                        مرحله بعد
                    </button>
                </div>
            </div>

            {{-- Step 2: Installation Location --}}
            <div id="step-2" class="step">
                <h2 class="text-xl font-bold mb-6">محل نصب</h2>

                @php
                    $iranProvinces = \SolarPlantRequests\Http\Requests\StoreSolarPlantRequestRequest::IRAN_PROVINCES;
                @endphp
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-gray-700">استان <span class="text-red-500">*</span></label>
                        <select name="province" id="province_select" required data-label="استان"
                            class="w-full border rounded-lg px-4 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-amber-500 @error('province') border-red-500 @enderror">
                            <option value="">-- لطفاً استان را انتخاب کنید --</option>
                            @foreach ($iranProvinces as $prov)
                                <option value="{{ $prov }}" {{ old('province') === $prov ? 'selected' : '' }}>
                                    {{ $prov }}
                                </option>
                            @endforeach
                        </select>
                        @error('province') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-gray-700">شهر <span class="text-red-500">*</span></label>
                        <select name="city" id="city_select" required data-label="شهر"
                            class="w-full border rounded-lg px-4 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-amber-500 @error('city') border-red-500 @enderror">
                            <option value="">ابتدا استان را انتخاب کنید</option>
                        </select>
                        @error('city') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-gray-700">کد پستی <span class="text-red-500">*</span></label>
                        <input type="text" name="postal_code" value="{{ old('postal_code') }}" dir="ltr" inputmode="numeric" maxlength="10"
                            required data-label="کد پستی" data-digits-only pattern="\d{10}"
                            data-pattern-error="کد پستی باید ۱۰ رقم و به صورت عدد باشد."
                            class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500 @error('postal_code') border-red-500 @enderror">
                        @error('postal_code') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-1 md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">آدرس دقیق <span class="text-red-500">*</span></label>
                        <textarea name="address" rows="3" required data-label="آدرس دقیق"
                            class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500 @error('address') border-red-500 @enderror">{{ old('address') }}</textarea>
                        @error('address') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex justify-between mt-8">
                    <button type="button" onclick="prevStep(1)"
                        class="border border-gray-300 text-gray-700 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                        مرحله قبل
                    </button>
                    <button type="button" onclick="nextStep(3)"
                        class="bg-amber-500 text-white px-8 py-3 rounded-lg font-semibold hover:bg-amber-600 transition">
                        مرحله بعد
                    </button>
                </div>
            </div>

            {{-- Step 3: Technical Specs --}}
            <div id="step-3" class="step">
                <h2 class="text-xl font-bold mb-6">مشخصات فنی</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-gray-700">نوع کاربری <span class="text-red-500">*</span></label>
                        <select name="usage_type" required data-label="نوع کاربری"
                            class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500 @error('usage_type') border-red-500 @enderror">
                            <option value="">انتخاب کنید</option>
                            <option value="villa" {{ old('usage_type') === 'villa' ? 'selected' : '' }}>ویلایی</option>
                            <option value="industrial" {{ old('usage_type') === 'industrial' ? 'selected' : '' }}>صنعتی</option>
                            <option value="commercial" {{ old('usage_type') === 'commercial' ? 'selected' : '' }}>تجاری</option>
                            <option value="agriculture" {{ old('usage_type') === 'agriculture' ? 'selected' : '' }}>کشاورزی</option>
                            <option value="apartment" {{ old('usage_type') === 'apartment' ? 'selected' : '' }}>آپارتمان</option>
                        </select>
                        @error('usage_type') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-gray-700">نوع ملک مشاع؟ <span class="text-red-500">*</span></label>
                        <div class="flex gap-4 mt-2">
                            <label class="flex items-center gap-2">
                                <input type="radio" name="is_shared_property" value="1" {{ old('is_shared_property') == '1' ? 'checked' : '' }}
                                    class="text-amber-500 focus:ring-amber-500">
                                <span>بلی</span>
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="radio" name="is_shared_property" value="0" {{ old('is_shared_property', '0') == '0' ? 'checked' : '' }}
                                    class="text-amber-500 focus:ring-amber-500">
                                <span>خیر</span>
                            </label>
                        </div>
                        @error('is_shared_property') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-gray-700">مساحت تقریبی محل نصب (متر مربع)</label>
                        <input type="number" name="installation_area" value="{{ old('installation_area') }}" min="0"
                            class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500 @error('installation_area') border-red-500 @enderror">
                        @error('installation_area') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-gray-700">نوع سطح محل نصب <span class="text-red-500">*</span></label>
                        <select name="surface_type" required data-label="نوع سطح محل نصب"
                            class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500 @error('surface_type') border-red-500 @enderror">
                            <option value="">انتخاب کنید</option>
                            <option value="flat" {{ old('surface_type') === 'flat' ? 'selected' : '' }}>تخت</option>
                            <option value="sloped" {{ old('surface_type') === 'sloped' ? 'selected' : '' }}>شیبدار</option>
                            <option value="ground" {{ old('surface_type') === 'ground' ? 'selected' : '' }}>زمین</option>
                            <option value="other" {{ old('surface_type') === 'other' ? 'selected' : '' }}>سایر</option>
                        </select>
                        @error('surface_type') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-gray-700">هدف <span class="text-red-500">*</span></label>
                        <select name="purpose" required data-label="هدف"
                            class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500 @error('purpose') border-red-500 @enderror">
                            <option value="">انتخاب کنید</option>
                            <option value="off_grid" {{ old('purpose') === 'off_grid' ? 'selected' : '' }}>مصرف شخصی (Off-grid)</option>
                            <option value="on_grid" {{ old('purpose') === 'on_grid' ? 'selected' : '' }}>فروش به شبکه (On-grid)</option>
                            <option value="hybrid" {{ old('purpose') === 'hybrid' ? 'selected' : '' }}>هیبرید (Hybrid)</option>
                        </select>
                        @error('purpose') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-gray-700">ظرفیت (کیلو وات) <span class="text-red-500">*</span></label>
                        <input type="number" name="capacity_kw" value="{{ old('capacity_kw') }}" min="1" required data-label="ظرفیت (کیلو وات)"
                            class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500 @error('capacity_kw') border-red-500 @enderror">
                        @error('capacity_kw') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-gray-700">برق ۳ فاز دارید؟ <span class="text-red-500">*</span></label>
                        <div class="flex gap-4 mt-2">
                            <label class="flex items-center gap-2">
                                <input type="radio" name="has_three_phase" value="1" {{ old('has_three_phase') == '1' ? 'checked' : '' }}
                                    class="text-amber-500 focus:ring-amber-500">
                                <span>بله</span>
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="radio" name="has_three_phase" value="0" {{ old('has_three_phase', '0') == '0' ? 'checked' : '' }}
                                    class="text-amber-500 focus:ring-amber-500">
                                <span>خیر</span>
                            </label>
                        </div>
                        @error('has_three_phase') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-gray-700">تمایل به دریافت وام دارید؟ <span class="text-red-500">*</span></label>
                        <div class="flex gap-4 mt-2">
                            <label class="flex items-center gap-2">
                                <input type="radio" name="wants_loan" value="1" {{ old('wants_loan') == '1' ? 'checked' : '' }}
                                    class="text-amber-500 focus:ring-amber-500">
                                <span>بله</span>
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="radio" name="wants_loan" value="0" {{ old('wants_loan', '0') == '0' ? 'checked' : '' }}
                                    class="text-amber-500 focus:ring-amber-500">
                                <span>خیر</span>
                            </label>
                        </div>
                        @error('wants_loan') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-1 md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">توضیحات</label>
                        <textarea name="description" rows="3"
                            class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                        @error('description') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex justify-between mt-8">
                    <button type="button" onclick="prevStep(2)"
                        class="border border-gray-300 text-gray-700 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                        مرحله قبل
                    </button>
                    <button type="button" onclick="nextStep(4)"
                        class="bg-amber-500 text-white px-8 py-3 rounded-lg font-semibold hover:bg-amber-600 transition">
                        مرحله بعد
                    </button>
                </div>
            </div>

            {{-- Step 4: Documents & Images --}}
            <div id="step-4" class="step">
                <h2 class="text-xl font-bold mb-6">مدارک و تصاویر</h2>

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <h3 class="font-semibold text-blue-800 mb-2">نکات عکس‌برداری:</h3>
                    <ul class="text-sm text-blue-700 space-y-1 list-disc pr-5">
                        <li>تصاویر باید واضح و با کیفیت باشند.</li>
                        <li>از زوایای مختلف محل نصب عکس بگیرید.</li>
                        <li>فرمت‌های مجاز: JPG, PNG</li>
                        <li>حداکثر حجم هر فایل: ۵ مگابایت — حداکثر ۴ فایل</li>
                        <li>آپلود تصاویر اختیاری است.</li>
                    </ul>
                </div>

                {{-- Images section --}}
                <div class="space-y-3 mb-6">
                    <label class="block text-sm font-semibold text-gray-700">تصاویر محل نصب <span class="text-gray-400 font-normal">(اختیاری، حداکثر ۴ تصویر)</span></label>
                    <div id="images-container" class="space-y-2">
                        <div class="image-row flex items-center gap-2">
                            <input type="file" name="images[]" accept="image/jpeg,image/png"
                                class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500">
                            <span class="text-xs text-gray-400">تصویر ۱</span>
                        </div>
                    </div>
                    <button type="button" id="add-image-btn" onclick="addImageRow()"
                        class="flex items-center gap-1.5 text-sm text-amber-600 border border-amber-300 bg-amber-50 px-4 py-2 rounded-lg hover:bg-amber-100 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        افزودن تصویر دیگر
                    </button>
                </div>

                {{-- Documents section --}}
                <div class="space-y-3">
                    <label class="block text-sm font-semibold text-gray-700">مدارک هویتی <span class="text-gray-400 font-normal">(اختیاری، حداکثر ۴ فایل)</span></label>
                    <div id="documents-container" class="space-y-2">
                        <div class="doc-row flex items-center gap-2">
                            <input type="file" name="documents[]" accept="image/jpeg,image/png,application/pdf"
                                class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500">
                            <span class="text-xs text-gray-400">مدرک ۱</span>
                        </div>
                    </div>
                    <button type="button" id="add-doc-btn" onclick="addDocRow()"
                        class="flex items-center gap-1.5 text-sm text-amber-600 border border-amber-300 bg-amber-50 px-4 py-2 rounded-lg hover:bg-amber-100 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        افزودن مدرک دیگر
                    </button>
                </div>

                <div class="flex justify-between mt-8">
                    <button type="button" onclick="prevStep(3)"
                        class="border border-gray-300 text-gray-700 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                        مرحله قبل
                    </button>
                    <button type="submit"
                        class="bg-green-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-green-700 transition">
                        ثبت درخواست
                    </button>
                </div>
            </div>
        </form>
    </main>

    <footer class="bg-gray-900 text-gray-100 mt-12">
        <div class="container px-6 py-6 flex flex-col md:flex-row items-center justify-between gap-4 text-sm">
            <div>اتحادیه کشوری سوختهای جایگزین</div>
            <div class="flex gap-4">
                <!-- <span>پشتیبانی: 021-91307571</span> -->
                <span>ایمیل: info@altfuel.ir</span>
            </div>
        </div>
    </footer>

    <script>
        let currentStep = 1;
        const totalSteps = 4;

        // Map field names to their step number
        const fieldStepMap = {
            // Step 1
            applicant_type: 1,
            first_name: 1, last_name: 1, national_code: 1,
            company_name: 1, registration_number: 1,
            immigration_code: 1,
            mobile: 1, landline: 1, bill_identifier: 1,
            // Step 2
            province: 2, city: 2, postal_code: 2, address: 2,
            // Step 3
            usage_type: 3, is_shared_property: 3, installation_area: 3,
            surface_type: 3, purpose: 3, capacity_kw: 3,
            has_three_phase: 3, wants_loan: 3, description: 3,
            // Step 4
            images: 4, documents: 4,
        };

        function showStep(step) {
            document.querySelectorAll('.step').forEach(el => el.classList.remove('active'));
            document.getElementById('step-' + step).classList.add('active');

            // Update indicators
            for (let i = 1; i <= totalSteps; i++) {
                const indicator = document.getElementById('indicator-' + i);
                indicator.classList.remove('active', 'completed');

                if (i < step) {
                    indicator.classList.add('completed');
                    indicator.innerHTML = '✓';
                } else if (i === step) {
                    indicator.classList.add('active');
                    indicator.innerHTML = ['۱', '۲', '۳', '۴'][i - 1];
                } else {
                    indicator.innerHTML = ['۱', '۲', '۳', '۴'][i - 1];
                }
            }

            // Update connectors
            for (let i = 1; i < totalSteps; i++) {
                const connector = document.getElementById('connector-' + i);
                if (i < step) {
                    connector.classList.add('active');
                } else {
                    connector.classList.remove('active');
                }
            }

            // Scroll to top of form
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // ---- Client-side validation ----
        const digitChars = '۰۱۲۳۴۵۶۷۸۹٠١٢٣٤٥٦٧٨٩';

        function normalizeDigits(value) {
            return String(value || '').replace(/[۰-۹٠-٩]/g, function (d) {
                return String(digitChars.indexOf(d));
            });
        }

        // ورودی‌های عددی: حذف کاراکترهای غیرعددی به صورت زنده
        document.querySelectorAll('input[data-digits-only]').forEach(function (input) {
            input.addEventListener('input', function () {
                this.value = normalizeDigits(this.value).replace(/\D/g, '');
            });
        });

        function clientValidateContainer(container) {
            const errors = [];
            const seen = new Set();

            container.querySelectorAll('input:not([type="hidden"]):not([type="file"]):not([type="radio"]), select, textarea')
                .forEach(function (el) {
                    if (el.disabled || seen.has(el.name)) return;
                    seen.add(el.name);

                    const label = el.dataset.label || el.name;
                    const value = normalizeDigits(el.value).trim();
                    const isEmpty = !value;

                    if (el.required && isEmpty) {
                        errors.push(el.tagName === 'SELECT'
                            ? 'لطفاً ' + label + ' را انتخاب کنید.'
                            : 'وارد کردن ' + label + ' الزامی است.');
                        return;
                    }

                    if (!isEmpty && el.pattern) {
                        const re = new RegExp('^(?:' + el.pattern + ')$');
                        if (!re.test(value)) {
                            errors.push(el.dataset.patternError || (label + ' در فرمت صحیح نیست.'));
                        }
                    }
                });

            return errors;
        }

        function showClientErrors(errors) {
            const box = document.getElementById('client-errors');
            const list = box.querySelector('ul');
            list.innerHTML = '';
            errors.forEach(function (msg) {
                const li = document.createElement('li');
                li.textContent = msg;
                list.appendChild(li);
            });
            box.classList.remove('hidden');
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function hideClientErrors() {
            document.getElementById('client-errors').classList.add('hidden');
        }

        function nextStep(step) {
            const errors = clientValidateContainer(document.getElementById('step-' + currentStep));
            if (errors.length > 0) {
                showClientErrors(errors);
                return;
            }
            hideClientErrors();
            currentStep = step;
            showStep(step);
        }

        function prevStep(step) {
            hideClientErrors();
            currentStep = step;
            showStep(step);
        }

        // Applicant type toggle — also disables inputs in hidden sections
        // so duplicate field names don't conflict on form submit
        function switchApplicantType(type) {
            const sections = ['individual', 'company', 'foreigner'];
            sections.forEach(function(section) {
                const el = document.getElementById('fields-' + section);
                if (section === type) {
                    el.classList.remove('hidden');
                    el.querySelectorAll('input, select, textarea').forEach(function(input) {
                        input.disabled = false;
                    });
                } else {
                    el.classList.add('hidden');
                    el.querySelectorAll('input, select, textarea').forEach(function(input) {
                        input.disabled = true;
                    });
                }
            });
        }

        document.querySelectorAll('input[name="applicant_type"]').forEach(radio => {
            radio.addEventListener('change', function() {
                switchApplicantType(this.value);
            });
        });

        // On page load: restore applicant type and jump to first step with errors
        (function init() {
            // Restore applicant type from old input
            const selectedType = document.querySelector('input[name="applicant_type"]:checked');
            if (selectedType) {
                switchApplicantType(selectedType.value);
            }

            // If there are server-side errors, jump to the earliest step that has an error
            const errorFields = Array.from(document.querySelectorAll('[data-field-error]'))
                .map(el => el.dataset.fieldError);

            @if ($errors->any())
                const errorKeys = @json(array_keys($errors->messages()));
                let targetStep = totalSteps;
                errorKeys.forEach(function(field) {
                    const step = fieldStepMap[field];
                    if (step && step < targetStep) {
                        targetStep = step;
                    }
                });
                showStep(targetStep);
            @else
                showStep(1);
            @endif
        })();

        // ---- Dynamic file rows ----
        const MAX_FILES = 4;

        function addImageRow() {
            const container = document.getElementById('images-container');
            const count = container.querySelectorAll('.image-row').length;
            if (count >= MAX_FILES) return;

            const row = document.createElement('div');
            row.className = 'image-row flex items-center gap-2';
            row.innerHTML = `
                <input type="file" name="images[]" accept="image/jpeg,image/png"
                    class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500">
                <span class="text-xs text-gray-400">تصویر ${count + 1}</span>
                <button type="button" onclick="this.parentElement.remove(); updateAddImageBtn();"
                    class="text-red-400 hover:text-red-600 text-lg leading-none">✕</button>
            `;
            container.appendChild(row);
            updateAddImageBtn();
        }

        function updateAddImageBtn() {
            const count = document.getElementById('images-container').querySelectorAll('.image-row').length;
            document.getElementById('add-image-btn').style.display = count >= MAX_FILES ? 'none' : '';
        }

        function addDocRow() {
            const container = document.getElementById('documents-container');
            const count = container.querySelectorAll('.doc-row').length;
            if (count >= MAX_FILES) return;

            const row = document.createElement('div');
            row.className = 'doc-row flex items-center gap-2';
            row.innerHTML = `
                <input type="file" name="documents[]" accept="image/jpeg,image/png,application/pdf"
                    class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500">
                <span class="text-xs text-gray-400">مدرک ${count + 1}</span>
                <button type="button" onclick="this.parentElement.remove(); updateAddDocBtn();"
                    class="text-red-400 hover:text-red-600 text-lg leading-none">✕</button>
            `;
            container.appendChild(row);
            updateAddDocBtn();
        }

        function updateAddDocBtn() {
            const count = document.getElementById('documents-container').querySelectorAll('.doc-row').length;
            document.getElementById('add-doc-btn').style.display = count >= MAX_FILES ? 'none' : '';
        }
    </script>

    {{-- Province-City Picker (نیاز به jQuery دارد) --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="{{ url('behin/behin-js/province-city-picker.js') }}"></script>
    <script>
        var citiesData = {!! json_encode(json_decode(file_get_contents(resource_path('data/cities.json')), true), JSON_UNESCAPED_UNICODE) !!};
        $(document).ready(function() {
            initProvinceCityPicker('province_select', 'city_select', '{{ old('city') }}', citiesData);
        });
    </script>
</body>

</html>
