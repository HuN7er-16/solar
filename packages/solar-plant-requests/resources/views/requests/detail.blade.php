<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" href="{{ url('behin/logo.ico') . '?' . config('app.version') }}">
    <title>جزئیات درخواست - نیروگاه خورشیدی</title>
    <script src="{{ url('behin/behin-dist/dist/js/tailwind-3.4.17.min.js') }}"></script>
    <link href="{{ url('behin/behin-dist/css/css2.css') }}?family=Vazirmatn:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        html, body { font-family: 'Vazirmatn', sans-serif; }
        .container { max-width: 960px; margin-inline: auto; }
        .bg-page {
            background-image: url("{{ asset('behin/images/background.jpeg') }}");
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        .bg-overlay { background: linear-gradient(160deg,rgba(0,0,0,0.60) 0%,rgba(5,25,5,0.52) 100%); min-height:100vh; }
        .header-glass {
            background: rgba(0,0,0,0.50);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border-bottom: 1px solid rgba(255,255,255,0.10);
        }
        .footer-glass {
            background: rgba(0,0,0,0.55);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-top: 1px solid rgba(255,255,255,0.10);
        }
        .sun-badge { background: linear-gradient(135deg,#f59e0b,#f97316); }
        .btn-back {
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.25);
            transition: background 0.2s;
        }
        .btn-back:hover { background: rgba(255,255,255,0.20); }
        /* white content cards */
        .content-card { background:#fff; border-radius:1rem; box-shadow:0 2px 16px rgba(0,0,0,0.10); padding:1.5rem; }
    </style>
</head>
<body class="bg-page min-h-screen">
<div class="bg-overlay flex flex-col min-h-screen">

    @php
        use SolarPlantRequests\Enums\SolarPlantRequestStatus;
        $steps = [
            'initial_registration'   => ['label' => 'ثبت اولیه',          'icon' => '📋', 'step' => 1],
            'under_review'           => ['label' => 'بررسی کارشناسی',     'icon' => '🔍', 'step' => 2],
            'awaiting_user_approval' => ['label' => 'تایید گزارش',        'icon' => '✋', 'step' => 3],
            'package_selection'      => ['label' => 'انتخاب پکیج',        'icon' => '📦', 'step' => 4],
            'contractor_selection'   => ['label' => 'انتخاب پیمانکار',    'icon' => '👷', 'step' => 5],
            'contractor_assigned'    => ['label' => 'آماده پروژه',        'icon' => '📝', 'step' => 6],
            'equipment_installation' => ['label' => 'نصب تجهیزات',        'icon' => '⚙️', 'step' => 7],
            'inspection'             => ['label' => 'بازرسی',             'icon' => '🔎', 'step' => 8],
            'certificate_issued'     => ['label' => 'صدور گواهی',         'icon' => '✅', 'step' => 9],
        ];
        $allSteps    = array_values($steps);
        $currentStep = $steps[$req->status->value]['step'] ?? 1;
        $totalSteps  = count($allSteps);
        $badgeClass  = $req->status->badgeClass();

        $applicantLabels = ['individual' => 'شخص حقیقی', 'company' => 'شخص حقوقی', 'foreigner' => 'اتباع خارجی'];
        $usageLabels     = ['villa' => 'ویلایی', 'industrial' => 'صنعتی', 'commercial' => 'تجاری', 'agriculture' => 'کشاورزی', 'apartment' => 'آپارتمان'];
        $surfaceLabels   = ['flat' => 'تخت', 'sloped' => 'شیبدار', 'ground' => 'زمین', 'other' => 'سایر'];
        $purposeLabels   = ['off_grid' => 'مصرف شخصی (Off-grid)', 'on_grid' => 'فروش به شبکه (On-grid)', 'hybrid' => 'هیبرید (Hybrid)'];
        $logoPath = public_path('behin/images/logo-union.png');
        $logoUrl  = asset('behin/images/logo-union.png');
    @endphp

    {{-- Header --}}
    <header class="header-glass sticky top-0 z-50">
        <div class="container px-6 py-4 flex items-center justify-between gap-3">
            {{-- Logo + title --}}
            <div class="flex items-center gap-3">
                @if(file_exists($logoPath))
                    <img src="{{ $logoUrl }}" alt="لوگو اتحادیه" class="h-11 w-auto object-contain">
                @else
                    <div class="sun-badge w-9 h-9 rounded-xl flex items-center justify-center text-white font-bold text-lg flex-shrink-0">☀</div>
                @endif
                <div>
                    <p class="text-white font-bold text-sm leading-tight">جزئیات درخواست</p>
                    <p class="text-amber-300 text-xs font-mono" dir="ltr">{{ $req->unique_code }}</p>
                </div>
            </div>
            {{-- Back button --}}
            <a href="{{ url()->previous() }}"
               class="btn-back flex items-center gap-2 text-white px-4 py-2 rounded-xl text-sm font-semibold">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                بازگشت
            </a>
        </div>
    </header>

    <main class="container px-6 py-8 space-y-5 flex-grow">

        {{-- Status + stepper --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex flex-wrap items-center gap-3 mb-6">
                <span class="font-bold text-lg">وضعیت فعلی:</span>
                <span class="text-sm px-3 py-1 rounded-full {{ $badgeClass }} font-semibold">{{ $req->status_label }}</span>
                <span class="text-sm text-gray-400">تاریخ ثبت: {{ \Morilog\Jalali\Jalalian::fromDateTime($req->created_at)->format('Y/m/d') }}</span>
            </div>
            <div class="overflow-x-auto pb-1">
                <div class="flex items-center min-w-max">
                    @foreach ($allSteps as $i => $s)
                        @php $isDone = $s['step'] < $currentStep; $isCurrent = $s['step'] === $currentStep; @endphp
                        <div class="flex flex-col items-center">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold border-2 transition
                                {{ $isDone ? 'bg-green-500 border-green-500 text-white' : '' }}
                                {{ $isCurrent ? 'bg-amber-500 border-amber-500 text-white' : '' }}
                                {{ !$isDone && !$isCurrent ? 'bg-white border-gray-300 text-gray-400' : '' }}">
                                @if ($isDone) ✓ @else {{ $s['icon'] }} @endif
                            </div>
                            <span class="mt-1.5 text-xs text-center w-16 leading-tight
                                {{ $isCurrent ? 'text-amber-600 font-semibold' : ($isDone ? 'text-green-600' : 'text-gray-400') }}">
                                {{ $s['label'] }}
                            </span>
                        </div>
                        @if ($i < $totalSteps - 1)
                            <div class="w-12 h-1 mx-1 rounded mb-5 {{ $allSteps[$i+1]['step'] <= $currentStep ? 'bg-green-400' : 'bg-gray-200' }}"></div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Applicant info --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="font-bold text-base mb-4 pb-2 border-b border-gray-100">اطلاعات متقاضی</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                <div>
                    <p class="text-gray-400 mb-0.5">نوع متقاضی</p>
                    <p class="font-medium">{{ $applicantLabels[$req->applicant_type->value] ?? $req->applicant_type->value }}</p>
                </div>
                @if ($req->applicant_type->value === 'company')
                    <div>
                        <p class="text-gray-400 mb-0.5">نام شرکت</p>
                        <p class="font-medium">{{ $req->company_name ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400 mb-0.5">شماره ثبت</p>
                        <p class="font-medium" dir="ltr">{{ $req->registration_number ?? '—' }}</p>
                    </div>
                @else
                    <div>
                        <p class="text-gray-400 mb-0.5">نام و نام خانوادگی</p>
                        <p class="font-medium">{{ $req->first_name }} {{ $req->last_name }}</p>
                    </div>
                    @if ($req->applicant_type->value === 'individual')
                        <div>
                            <p class="text-gray-400 mb-0.5">کد ملی</p>
                            <p class="font-medium" dir="ltr">{{ $req->national_code ?? '—' }}</p>
                        </div>
                    @else
                        <div>
                            <p class="text-gray-400 mb-0.5">کد اتباع</p>
                            <p class="font-medium" dir="ltr">{{ $req->immigration_code ?? '—' }}</p>
                        </div>
                    @endif
                @endif
                <div>
                    <p class="text-gray-400 mb-0.5">شماره موبایل</p>
                    <p class="font-medium" dir="ltr">{{ $req->mobile }}</p>
                </div>
                @if ($req->landline)
                    <div>
                        <p class="text-gray-400 mb-0.5">تلفن ثابت</p>
                        <p class="font-medium" dir="ltr">{{ $req->landline }}</p>
                    </div>
                @endif
                @if ($req->bill_identifier)
                    <div>
                        <p class="text-gray-400 mb-0.5">شناسه قبض برق</p>
                        <p class="font-medium" dir="ltr">{{ $req->bill_identifier }}</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Location --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="font-bold text-base mb-4 pb-2 border-b border-gray-100">محل نصب</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                <div>
                    <p class="text-gray-400 mb-0.5">استان</p>
                    <p class="font-medium">{{ $req->province }}</p>
                </div>
                <div>
                    <p class="text-gray-400 mb-0.5">شهر</p>
                    <p class="font-medium">{{ $req->city }}</p>
                </div>
                <div>
                    <p class="text-gray-400 mb-0.5">کد پستی</p>
                    <p class="font-medium" dir="ltr">{{ $req->postal_code }}</p>
                </div>
                <div class="sm:col-span-2 md:col-span-3">
                    <p class="text-gray-400 mb-0.5">آدرس دقیق</p>
                    <p class="font-medium">{{ $req->address }}</p>
                </div>
            </div>
        </div>

        {{-- Technical specs --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="font-bold text-base mb-4 pb-2 border-b border-gray-100">مشخصات فنی</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                <div>
                    <p class="text-gray-400 mb-0.5">نوع کاربری</p>
                    <p class="font-medium">{{ $usageLabels[$req->usage_type->value] ?? $req->usage_type->value }}</p>
                </div>
                <div>
                    <p class="text-gray-400 mb-0.5">نوع سطح نصب</p>
                    <p class="font-medium">{{ $surfaceLabels[$req->surface_type->value] ?? $req->surface_type->value }}</p>
                </div>
                <div>
                    <p class="text-gray-400 mb-0.5">هدف</p>
                    <p class="font-medium">{{ $purposeLabels[$req->purpose->value] ?? $req->purpose->value }}</p>
                </div>
                <div>
                    <p class="text-gray-400 mb-0.5">ظرفیت</p>
                    <p class="font-medium">{{ $req->capacity_kw }} کیلو وات</p>
                </div>
                @if ($req->installation_area)
                    <div>
                        <p class="text-gray-400 mb-0.5">مساحت محل نصب</p>
                        <p class="font-medium">{{ $req->installation_area }} متر مربع</p>
                    </div>
                @endif
                <div>
                    <p class="text-gray-400 mb-0.5">ملک مشاع</p>
                    <p class="font-medium">{{ $req->is_shared_property ? 'بلی' : 'خیر' }}</p>
                </div>
                <div>
                    <p class="text-gray-400 mb-0.5">برق ۳ فاز</p>
                    <p class="font-medium">{{ $req->has_three_phase ? 'بلی' : 'خیر' }}</p>
                </div>
                <div>
                    <p class="text-gray-400 mb-0.5">تمایل به وام</p>
                    <p class="font-medium">{{ $req->wants_loan ? 'بلی' : 'خیر' }}</p>
                </div>
                @if ($req->description)
                    <div class="sm:col-span-2 md:col-span-3">
                        <p class="text-gray-400 mb-0.5">توضیحات</p>
                        <p class="font-medium leading-relaxed">{{ $req->description }}</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- پکیج و پیمانکار --}}
        @if ($req->selected_package_title || $req->contractor_name)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="font-bold text-base mb-4 pb-2 border-b border-gray-100">پکیج و پیمانکار</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                    @if ($req->selected_package_title)
                    <div class="sm:col-span-2">
                        <p class="text-gray-400 mb-0.5">پکیج انتخاب‌شده</p>
                        <p class="font-semibold">{{ $req->selected_package_title }}</p>
                    </div>
                    @endif
                    @if ($req->selected_package_price)
                    <div>
                        <p class="text-gray-400 mb-0.5">قیمت پکیج</p>
                        <p class="font-bold text-amber-700" dir="ltr">{{ number_format($req->selected_package_price) }} ریال</p>
                    </div>
                    @endif
                    @if ($req->contractor_name)
                    <div>
                        <p class="text-gray-400 mb-0.5">پیمانکار</p>
                        <p class="font-semibold">{{ $req->contractor_name }}</p>
                    </div>
                    @endif
                </div>
            </div>
        @endif

        {{-- Files --}}
        @if (!empty($req->images) || !empty($req->documents))
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="font-bold text-base mb-4 pb-2 border-b border-gray-100">مدارک و تصاویر</h2>

                @if (!empty($req->images))
                    <p class="text-sm font-semibold text-gray-600 mb-3">تصاویر محل نصب</p>
                    <div class="flex flex-wrap gap-3 mb-5">
                        @foreach ($req->images as $i => $img)
                            <div class="flex flex-col items-center gap-1.5">
                                <a href="{{ route('solar-plant-requests.file.download', ['path' => $img]) }}"
                                   download="{{ basename($img) }}"
                                   class="flex items-center gap-2 border border-gray-200 rounded-lg px-4 py-3 text-sm text-gray-700 hover:bg-amber-50 hover:border-amber-300 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                    دانلود تصویر {{ $i + 1 }}
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif

                @if (!empty($req->documents))
                    <p class="text-sm font-semibold text-gray-600 mb-3">مدارک هویتی</p>
                    <div class="flex flex-wrap gap-3">
                        @foreach ($req->documents as $i => $doc)
                            @php $ext = strtolower(pathinfo($doc, PATHINFO_EXTENSION)); @endphp
                            <a href="{{ route('solar-plant-requests.file.download', ['path' => $doc]) }}"
                               download="{{ basename($doc) }}"
                               class="flex items-center gap-2 border border-gray-200 rounded-lg px-4 py-3 text-sm text-gray-700 hover:bg-amber-50 hover:border-amber-300 transition">
                                @if ($ext === 'pdf')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                    دانلود PDF {{ $i + 1 }}
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                    دانلود مدرک {{ $i + 1 }}
                                @endif
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif

    </main>

    <footer class="footer-glass mt-8">
        <div class="container px-6 py-5 flex flex-col md:flex-row items-center justify-between gap-3 text-sm">
            <div class="text-white/65">© اتحادیه کشوری سوخت‌های جایگزین</div>
            <div class="text-white/55">ایمیل: info@altfuel.ir</div>
        </div>
    </footer>

</div>
</body>
</html>
