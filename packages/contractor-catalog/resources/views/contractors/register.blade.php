<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>ثبت‌نام پیمانکار - سامانه سازا اصناف</title>
    <script src="{{ url('behin/behin-dist/dist/js/tailwind-3.4.17.min.js') }}"></script>
    <link href="{{ url('behin/behin-dist/css/css2.css') }}?family=Vazirmatn:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        html, body { font-family: 'Vazirmatn', sans-serif; }
        .bg-page {
            background-image: url("{{ asset('behin/images/background.jpeg') }}");
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        .bg-overlay {
            background: linear-gradient(135deg, rgba(0,0,0,0.65) 0%, rgba(10,30,10,0.58) 100%);
        }
        .glass-card {
            background: rgba(255,255,255,0.11);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            border: 1px solid rgba(255,255,255,0.22);
            box-shadow: 0 8px 40px rgba(0,0,0,0.38);
        }
        .glass-input {
            background: rgba(255,255,255,0.14);
            border: 1px solid rgba(255,255,255,0.28);
            color: #fff;
            transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
        }
        .glass-input::placeholder { color: rgba(255,255,255,0.38); }
        .glass-input:focus {
            outline: none;
            border-color: #f59e0b;
            background: rgba(255,255,255,0.20);
            box-shadow: 0 0 0 3px rgba(245,158,11,0.22);
        }
        .glass-input.is-invalid {
            border-color: rgba(239,68,68,0.75);
            background: rgba(239,68,68,0.08);
        }
        select.glass-input option { background: #1a2a1a; color: #fff; }
        .header-glass {
            background: rgba(0,0,0,0.45);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .footer-glass {
            background: rgba(0,0,0,0.55);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-top: 1px solid rgba(255,255,255,0.1);
        }
        .sun-badge {
            background: linear-gradient(135deg, #f59e0b, #f97316);
            box-shadow: 0 4px 20px rgba(245,158,11,0.5);
        }
        .btn-submit {
            background: linear-gradient(135deg, #f59e0b, #f97316);
            box-shadow: 0 4px 15px rgba(245,158,11,0.4);
            transition: all 0.25s ease;
        }
        .btn-submit:hover {
            background: linear-gradient(135deg, #d97706, #ea580c);
            box-shadow: 0 6px 20px rgba(245,158,11,0.55);
            transform: translateY(-1px);
        }
        .btn-submit:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }
        .section-divider {
            border-color: rgba(255,255,255,0.15);
        }
        .section-title {
            color: #fbbf24;
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.03em;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .field-label {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            color: rgba(255,255,255,0.78);
            margin-bottom: 0.35rem;
        }
        .error-msg {
            color: #fca5a5;
            font-size: 0.72rem;
            margin-top: 0.3rem;
        }
        /* تقویم جلالی */
        .persian-datepicker-container { position: relative; }
    </style>
</head>
<body class="bg-page min-h-screen flex flex-col">
<div class="bg-overlay min-h-screen flex flex-col">

    {{-- Header --}}
    <header class="header-glass">
        <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                @php
                    $logoPath = public_path('behin/images/logo-union.png');
                    $logoUrl  = asset('behin/images/logo-union.png');
                @endphp
                @if(file_exists($logoPath))
                    <img src="{{ $logoUrl }}" alt="لوگو اتحادیه" class="h-12 w-auto object-contain">
                @else
                    <div class="sun-badge w-10 h-10 rounded-full flex items-center justify-center text-white font-bold text-lg">☀</div>
                @endif
                <div>
                    <p class="text-white font-bold text-sm leading-tight">سامانه جامع انرژی‌های تجدیدپذیر</p>
                    <p class="text-amber-300 text-xs">اتحادیه کشوری سوخت‌های جایگزین</p>
                </div>
            </div>
            <a href="{{ route('login') }}" class="text-white/60 text-sm hover:text-amber-400 transition duration-200">
                قبلاً ثبت‌نام کرده‌اید؟ <span class="text-amber-400 font-semibold">ورود</span>
            </a>
        </div>
    </header>

    {{-- Main --}}
    <main class="flex-grow px-4 py-10">
        <div class="max-w-3xl mx-auto">

            {{-- عنوان صفحه --}}
            <div class="text-center mb-8">
                <div class="sun-badge w-16 h-16 rounded-2xl flex items-center justify-center text-white text-3xl mx-auto mb-4">👷</div>
                <h1 class="text-white text-2xl font-extrabold">ثبت‌نام پیمانکار</h1>
                <p class="text-white/55 text-sm mt-2">لطفاً اطلاعات شرکت و مشخصات خود را با دقت وارد کنید</p>
            </div>

            {{-- پیام موفقیت --}}
            @if(session('success'))
                <div class="rounded-xl bg-green-500/20 border border-green-400/40 text-green-200 px-5 py-4 text-sm mb-6 flex items-center gap-3">
                    <span class="text-xl">✅</span>
                    {{ session('success') }}
                </div>
            @endif

            {{-- خطاهای کلی --}}
            @if($errors->any())
                <div class="rounded-xl bg-red-500/20 border border-red-400/40 text-red-200 px-5 py-4 text-sm mb-6">
                    <p class="font-semibold mb-2">لطفاً خطاهای زیر را برطرف کنید:</p>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('contractor.register.submit') }}" id="registerForm">
                @csrf

                {{-- ━━━ ۱. اطلاعات حساب کاربری ━━━━━━━━━━━━━━━━━━━━━━━━━━━ --}}
                <div class="glass-card rounded-2xl p-6 mb-5">
                    <p class="section-title mb-5">
                        <span>👤</span> اطلاعات حساب کاربری
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        {{-- نام --}}
                        <div>
                            <label class="field-label" for="name">نام و نام خانوادگی <span class="text-red-400">*</span></label>
                            <input type="text" name="name" id="name"
                                   value="{{ old('name') }}"
                                   placeholder="علی محمدی"
                                   class="glass-input w-full rounded-xl px-4 py-2.5 text-sm {{ $errors->has('name') ? 'is-invalid' : '' }}">
                            @error('name')<p class="error-msg">{{ $message }}</p>@enderror
                        </div>
                        {{-- شماره موبایل --}}
                        <div>
                            <label class="field-label" for="phone">شماره موبایل <span class="text-red-400">*</span></label>
                            <input type="text" name="phone" id="phone"
                                   value="{{ old('phone') }}"
                                   placeholder="09123456789"
                                   dir="ltr" inputmode="numeric" maxlength="11"
                                   class="glass-input w-full rounded-xl px-4 py-2.5 text-sm font-mono text-center {{ $errors->has('phone') ? 'is-invalid' : '' }}">
                            <p class="text-white/40 text-xs mt-1">این شماره برای ورود به سامانه استفاده می‌شود</p>
                            @error('phone')<p class="error-msg">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                {{-- ━━━ ۲. اطلاعات شرکت ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ --}}
                <div class="glass-card rounded-2xl p-6 mb-5">
                    <p class="section-title mb-5">
                        <span>🏢</span> اطلاعات شرکت
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        {{-- نام شرکت --}}
                        <div>
                            <label class="field-label" for="company_name">نام شرکت <span class="text-red-400">*</span></label>
                            <input type="text" name="company_name" id="company_name"
                                   value="{{ old('company_name') }}"
                                   placeholder="شرکت انرژی خورشیدی پارس"
                                   class="glass-input w-full rounded-xl px-4 py-2.5 text-sm {{ $errors->has('company_name') ? 'is-invalid' : '' }}">
                            @error('company_name')<p class="error-msg">{{ $message }}</p>@enderror
                        </div>
                        {{-- شناسه ملی --}}
                        <div>
                            <label class="field-label" for="national_id">شناسه ملی شرکت <span class="text-red-400">*</span></label>
                            <input type="text" name="national_id" id="national_id"
                                   value="{{ old('national_id') }}"
                                   placeholder="10320012345"
                                   dir="ltr" inputmode="numeric" maxlength="11"
                                   class="glass-input w-full rounded-xl px-4 py-2.5 text-sm font-mono text-center {{ $errors->has('national_id') ? 'is-invalid' : '' }}">
                            <p class="text-white/40 text-xs mt-1">۱۱ رقم</p>
                            @error('national_id')<p class="error-msg">{{ $message }}</p>@enderror
                        </div>
                        {{-- تلفن شرکت --}}
                        <div>
                            <label class="field-label" for="company_phone">تلفن شرکت</label>
                            <input type="text" name="company_phone" id="company_phone"
                                   value="{{ old('company_phone') }}"
                                   placeholder="02112345678"
                                   dir="ltr" inputmode="numeric" maxlength="11"
                                   class="glass-input w-full rounded-xl px-4 py-2.5 text-sm font-mono text-center {{ $errors->has('company_phone') ? 'is-invalid' : '' }}">
                            @error('company_phone')<p class="error-msg">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                {{-- ━━━ ۳. اطلاعات مدیر عامل ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ --}}
                <div class="glass-card rounded-2xl p-6 mb-5">
                    <p class="section-title mb-5">
                        <span>🧑‍💼</span> اطلاعات مدیر عامل
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        {{-- نام مدیر عامل --}}
                        <div>
                            <label class="field-label" for="ceo_name">نام مدیر عامل <span class="text-red-400">*</span></label>
                            <input type="text" name="ceo_name" id="ceo_name"
                                   value="{{ old('ceo_name') }}"
                                   placeholder="محمد رضایی"
                                   class="glass-input w-full rounded-xl px-4 py-2.5 text-sm {{ $errors->has('ceo_name') ? 'is-invalid' : '' }}">
                            @error('ceo_name')<p class="error-msg">{{ $message }}</p>@enderror
                        </div>
                        {{-- کد ملی مدیر عامل --}}
                        <div>
                            <label class="field-label" for="ceo_national_code">کد ملی <span class="text-red-400">*</span></label>
                            <input type="text" name="ceo_national_code" id="ceo_national_code"
                                   value="{{ old('ceo_national_code') }}"
                                   placeholder="0012345678"
                                   dir="ltr" inputmode="numeric" maxlength="10"
                                   class="glass-input w-full rounded-xl px-4 py-2.5 text-sm font-mono text-center {{ $errors->has('ceo_national_code') ? 'is-invalid' : '' }}">
                            <p class="text-white/40 text-xs mt-1">۱۰ رقم</p>
                            @error('ceo_national_code')<p class="error-msg">{{ $message }}</p>@enderror
                        </div>
                        {{-- موبایل مدیر عامل --}}
                        <div>
                            <label class="field-label" for="ceo_mobile">موبایل <span class="text-red-400">*</span></label>
                            <input type="text" name="ceo_mobile" id="ceo_mobile"
                                   value="{{ old('ceo_mobile') }}"
                                   placeholder="09123456789"
                                   dir="ltr" inputmode="numeric" maxlength="11"
                                   class="glass-input w-full rounded-xl px-4 py-2.5 text-sm font-mono text-center {{ $errors->has('ceo_mobile') ? 'is-invalid' : '' }}">
                            @error('ceo_mobile')<p class="error-msg">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                {{-- ━━━ ۴. شخص رابط ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ --}}
                <div class="glass-card rounded-2xl p-6 mb-5">
                    <p class="section-title mb-5">
                        <span>📞</span> شخص رابط
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        {{-- نام شخص رابط --}}
                        <div>
                            <label class="field-label" for="contact_person_name">نام شخص رابط <span class="text-red-400">*</span></label>
                            <input type="text" name="contact_person_name" id="contact_person_name"
                                   value="{{ old('contact_person_name') }}"
                                   placeholder="سارا احمدی"
                                   class="glass-input w-full rounded-xl px-4 py-2.5 text-sm {{ $errors->has('contact_person_name') ? 'is-invalid' : '' }}">
                            @error('contact_person_name')<p class="error-msg">{{ $message }}</p>@enderror
                        </div>
                        {{-- موبایل شخص رابط --}}
                        <div>
                            <label class="field-label" for="contact_person_mobile">موبایل شخص رابط <span class="text-red-400">*</span></label>
                            <input type="text" name="contact_person_mobile" id="contact_person_mobile"
                                   value="{{ old('contact_person_mobile') }}"
                                   placeholder="09123456789"
                                   dir="ltr" inputmode="numeric" maxlength="11"
                                   class="glass-input w-full rounded-xl px-4 py-2.5 text-sm font-mono text-center {{ $errors->has('contact_person_mobile') ? 'is-invalid' : '' }}">
                            @error('contact_person_mobile')<p class="error-msg">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                {{-- ━━━ ۵. موقعیت ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ --}}
                <div class="glass-card rounded-2xl p-6 mb-5">
                    <p class="section-title mb-5">
                        <span>📍</span> موقعیت جغرافیایی
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                        {{-- استان --}}
                        <div>
                            <label class="field-label" for="province">استان <span class="text-red-400">*</span></label>
                            <select name="province" id="province"
                                    class="glass-input w-full rounded-xl px-4 py-2.5 text-sm {{ $errors->has('province') ? 'is-invalid' : '' }}">
                                <option value="">— انتخاب استان —</option>
                                @foreach($provinces as $prov)
                                    <option value="{{ $prov }}" {{ old('province') === $prov ? 'selected' : '' }}>
                                        {{ $prov }}
                                    </option>
                                @endforeach
                            </select>
                            @error('province')<p class="error-msg">{{ $message }}</p>@enderror
                        </div>
                        {{-- شهر --}}
                        <div>
                            <label class="field-label" for="city">شهر <span class="text-red-400">*</span></label>
                            <input type="text" name="city" id="city"
                                   value="{{ old('city') }}"
                                   placeholder="تهران"
                                   class="glass-input w-full rounded-xl px-4 py-2.5 text-sm {{ $errors->has('city') ? 'is-invalid' : '' }}">
                            @error('city')<p class="error-msg">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    {{-- آدرس --}}
                    <div>
                        <label class="field-label" for="address">آدرس کامل <span class="text-red-400">*</span></label>
                        <textarea name="address" id="address" rows="3"
                                  placeholder="خیابان، کوچه، پلاک..."
                                  class="glass-input w-full rounded-xl px-4 py-2.5 text-sm resize-none {{ $errors->has('address') ? 'is-invalid' : '' }}">{{ old('address') }}</textarea>
                        @error('address')<p class="error-msg">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- ━━━ ۶. پروانه کسب ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━ --}}
                <div class="glass-card rounded-2xl p-6 mb-7">
                    <p class="section-title mb-5">
                        <span>📄</span> پروانه کسب
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-5">
                        {{-- شماره پروانه --}}
                        <div>
                            <label class="field-label" for="license_number">شماره پروانه <span class="text-red-400">*</span></label>
                            <input type="text" name="license_number" id="license_number"
                                   value="{{ old('license_number') }}"
                                   placeholder="PR-12345"
                                   dir="ltr"
                                   class="glass-input w-full rounded-xl px-4 py-2.5 text-sm font-mono {{ $errors->has('license_number') ? 'is-invalid' : '' }}">
                            @error('license_number')<p class="error-msg">{{ $message }}</p>@enderror
                        </div>
                        {{-- تاریخ صدور --}}
                        <div>
                            <label class="field-label" for="license_issue_date">تاریخ صدور <span class="text-red-400">*</span></label>
                            <input type="text" name="license_issue_date" id="license_issue_date"
                                   value="{{ old('license_issue_date') }}"
                                   placeholder="1402/01/01"
                                   dir="ltr" inputmode="numeric"
                                   data-jdp
                                   autocomplete="off"
                                   class="glass-input w-full rounded-xl px-4 py-2.5 text-sm font-mono text-center {{ $errors->has('license_issue_date') ? 'is-invalid' : '' }}">
                            @error('license_issue_date')<p class="error-msg">{{ $message }}</p>@enderror
                        </div>
                        {{-- تاریخ انقضا --}}
                        <div>
                            <label class="field-label" for="license_expiry_date">تاریخ انقضا <span class="text-red-400">*</span></label>
                            <input type="text" name="license_expiry_date" id="license_expiry_date"
                                   value="{{ old('license_expiry_date') }}"
                                   placeholder="1405/01/01"
                                   dir="ltr" inputmode="numeric"
                                   data-jdp
                                   autocomplete="off"
                                   class="glass-input w-full rounded-xl px-4 py-2.5 text-sm font-mono text-center {{ $errors->has('license_expiry_date') ? 'is-invalid' : '' }}">
                            @error('license_expiry_date')<p class="error-msg">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    {{-- تعداد پروژه‌های ثبت‌شده --}}
                    <div class="max-w-xs">
                        <label class="field-label" for="registered_projects_count">تعداد پروژه‌های ثبت‌شده</label>
                        <input type="number" name="registered_projects_count" id="registered_projects_count"
                               value="{{ old('registered_projects_count', 0) }}"
                               min="0" placeholder="0"
                               dir="ltr"
                               class="glass-input w-full rounded-xl px-4 py-2.5 text-sm font-mono text-center {{ $errors->has('registered_projects_count') ? 'is-invalid' : '' }}">
                        @error('registered_projects_count')<p class="error-msg">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- دکمه ثبت‌نام --}}
                <button type="submit" id="submitBtn"
                        class="btn-submit w-full text-white py-3.5 rounded-2xl font-bold text-base">
                    ثبت‌نام پیمانکار
                </button>

                <p class="text-center text-white/35 text-xs mt-4 leading-6">
                    با ثبت‌نام، قوانین و مقررات سامانه را می‌پذیرید.
                </p>
            </form>
        </div>
    </main>

    {{-- Footer --}}
    <footer class="footer-glass">
        <div class="max-w-6xl mx-auto px-6 py-5 flex flex-col md:flex-row items-center justify-between gap-3 text-sm">
            <div class="text-white/65">© اتحادیه کشوری سوخت‌های جایگزین و انرژی‌های تجدیدپذیر</div>
            <div class="flex gap-6 text-white/55">
                <span>تلفن: ۰۲۱۹۱۰۱۳۷۹۱</span>
                <span>ایمیل: info@altfuel.ir</span>
            </div>
        </div>
    </footer>

</div>

{{-- تقویم جلالی --}}
@if(file_exists(public_path('behin/behin-dist/dist/js/jalalidatepicker.min.js')))
    <script src="{{ url('behin/behin-dist/dist/js/jalalidatepicker.min.js') }}"></script>
    <link rel="stylesheet" href="{{ url('behin/behin-dist/dist/css/jalalidatepicker.min.css') }}">
    <script>
        jalaliDatepicker.startWatch({
            minDate: false,
            maxDate: false,
            theme: 'dark',
        });
    </script>
@else
    {{-- fallback: راهنمای فرمت دستی --}}
    <script>
        ['license_issue_date','license_expiry_date'].forEach(function(id){
            const el = document.getElementById(id);
            if (!el) return;
            el.addEventListener('blur', function(){
                const v = el.value.trim();
                if (v && !/^\d{4}\/\d{2}\/\d{2}$/.test(v)) {
                    el.style.borderColor = 'rgba(239,68,68,0.75)';
                } else {
                    el.style.borderColor = '';
                }
            });
        });
    </script>
@endif

<script>
    // ارسال فرم با نمایش loading
    document.getElementById('registerForm').addEventListener('submit', function () {
        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.textContent = 'در حال ثبت‌نام...';
    });

    // فقط ارقام برای فیلدهای عددی
    ['phone','national_id','ceo_national_code','ceo_mobile','contact_person_mobile'].forEach(function(id){
        const el = document.getElementById(id);
        if (!el) return;
        el.addEventListener('input', function(){
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    });
</script>
</body>
</html>
