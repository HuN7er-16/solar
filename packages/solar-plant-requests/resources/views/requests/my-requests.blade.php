<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" href="{{ url('behin/logo.ico') . '?' . config('app.version') }}">
    <title>درخواست‌های من - نیروگاه خورشیدی</title>
    <script src="{{ url('behin/behin-dist/dist/js/tailwind-3.4.17.min.js') }}"></script>
    <link href="{{ url('behin/behin-dist/css/css2.css') }}?family=Vazirmatn:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        html, body { font-family: 'Vazirmatn', sans-serif; }
        .container { max-width: 960px; margin-inline: auto; }

        .bg-page {
            background-image: url("{{ asset('behin/images/background.jpeg') }}");
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        .bg-overlay {
            background: linear-gradient(160deg, rgba(0,0,0,0.60) 0%, rgba(5,25,5,0.52) 100%);
            min-height: 100vh;
        }
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
        .sun-badge {
            background: linear-gradient(135deg, #f59e0b, #f97316);
            box-shadow: 0 4px 20px rgba(245,158,11,0.5);
        }
        .btn-header {
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.22);
            transition: all 0.2s;
        }
        .btn-header:hover { background: rgba(255,255,255,0.20); }
        .btn-new {
            background: linear-gradient(135deg, #f59e0b, #f97316);
            box-shadow: 0 3px 12px rgba(245,158,11,0.35);
            transition: all 0.2s;
        }
        .btn-new:hover { background: linear-gradient(135deg, #d97706, #ea580c); transform: translateY(-1px); }
        /* White solid request cards */
        .req-card {
            background: #ffffff;
            box-shadow: 0 2px 16px rgba(0,0,0,0.14);
            transition: box-shadow 0.2s, transform 0.2s;
        }
        .req-card:hover {
            box-shadow: 0 6px 28px rgba(0,0,0,0.22);
            transform: translateY(-2px);
        }
        /* Empty state glass */
        .empty-glass {
            background: rgba(255,255,255,0.90);
            box-shadow: 0 4px 24px rgba(0,0,0,0.18);
        }
        /* alert glass */
        .alert-success {
            background: rgba(34,197,94,0.15);
            border: 1px solid rgba(34,197,94,0.35);
            color: #dcfce7;
        }
    </style>
</head>

<body class="bg-page min-h-screen">
<div class="bg-overlay flex flex-col min-h-screen">

@php
use SolarPlantRequests\Enums\SolarPlantRequestStatus;

$stepsMap = [
    'initial_registration'   => ['label' => 'ثبت اولیه',           'icon' => '📋', 'step' => 1],
    'under_review'           => ['label' => 'بررسی کارشناسی',      'icon' => '🔍', 'step' => 2],
    'awaiting_user_approval' => ['label' => 'تایید گزارش',         'icon' => '✋', 'step' => 3],
    'package_selection'      => ['label' => 'انتخاب پکیج',         'icon' => '📦', 'step' => 4],
    'contractor_selection'   => ['label' => 'انتخاب پیمانکار',     'icon' => '👷', 'step' => 5],
    'contractor_assigned'    => ['label' => 'آماده پروژه',         'icon' => '📝', 'step' => 6],
    'equipment_installation' => ['label' => 'نصب تجهیزات',         'icon' => '⚙️', 'step' => 7],
    'inspection'             => ['label' => 'بازرسی',              'icon' => '🔎', 'step' => 8],
    'certificate_issued'     => ['label' => 'صدور گواهی',          'icon' => '✅', 'step' => 9],
];
$allSteps = array_values($stepsMap);
@endphp

{{-- Header --}}
<header class="header-glass sticky top-0 z-50">
    <div class="container px-6 py-4 flex items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            @php
                $logoPath = public_path('behin/images/logo-union.png');
                $logoUrl  = asset('behin/images/logo-union.png');
            @endphp
            @if(file_exists($logoPath))
                <img src="{{ $logoUrl }}" alt="لوگو" class="h-10 w-auto object-contain hidden sm:block">
            @else
                <div class="sun-badge w-9 h-9 rounded-xl flex items-center justify-center text-white font-bold flex-shrink-0">☀</div>
            @endif
            <div>
                <h1 class="text-white font-bold text-base leading-tight">درخواست‌های من</h1>
                <p class="text-amber-300 text-xs">پیگیری وضعیت نیروگاه خورشیدی</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.dashboard') }}"
               class="btn-header flex items-center gap-1.5 text-white px-3 py-2 rounded-xl text-sm font-semibold">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span class="hidden sm:inline">صفحه اصلی</span>
            </a>
            <a href="{{ route('solar-plant-requests.apply') }}"
               class="btn-new flex items-center gap-1.5 text-white px-4 py-2 rounded-xl text-sm font-bold">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                درخواست جدید
            </a>
        </div>
    </div>
</header>

<main class="container px-6 py-8 flex-grow">

    @if (session('status'))
    <div class="alert-success mb-6 rounded-xl px-4 py-3 text-sm flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        {{ session('status') }}
    </div>
    @endif

    @if ($requests->isEmpty())
    <div class="empty-glass rounded-2xl px-8 py-16 text-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-16 w-16 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0119 9.414V19a2 2 0 01-2 2z"/>
        </svg>
        <p class="text-gray-500 text-lg mb-6">هنوز درخواستی ثبت نکرده‌اید.</p>
        <a href="{{ route('solar-plant-requests.apply') }}"
           class="inline-flex items-center gap-2 bg-amber-500 text-white px-6 py-3 rounded-xl font-semibold hover:bg-amber-600 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            ثبت اولین درخواست
        </a>
    </div>
    @else
    <div class="space-y-4">
        @foreach ($requests as $req)
        @php
            $currentStep = $stepsMap[$req->status->value]['step'] ?? 1;
            $totalSteps  = count($allSteps);
            $badgeClass  = $req->status->badgeClass();
        @endphp

        <div class="req-card rounded-2xl p-6">

            {{-- Card header --}}
            <div class="flex flex-wrap items-start justify-between gap-3 mb-5">
                <div>
                    <div class="flex items-center flex-wrap gap-2 mb-1">
                        <span class="font-bold text-lg text-gray-800">
                            @if ($req->applicant_type->value === 'company')
                                {{ $req->company_name }}
                            @else
                                {{ $req->first_name }} {{ $req->last_name }}
                            @endif
                        </span>
                        <span class="text-xs px-2.5 py-0.5 rounded-full {{ $badgeClass }} font-semibold">
                            {{ $req->status_label }}
                        </span>
                        @if ($req->status->requiresUserAction())
                        <span class="inline-flex items-center gap-1 text-xs bg-red-100 text-red-700 px-2 py-0.5 rounded-full font-semibold animate-pulse">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            نیاز به اقدام
                        </span>
                        @endif
                    </div>
                    <div class="text-sm text-gray-500 flex flex-wrap gap-x-4 gap-y-1">
                        <span>کد پیگیری: <span class="font-mono font-semibold text-gray-700" dir="ltr">{{ $req->unique_code }}</span></span>
                        <span>تاریخ ثبت: {{ \Morilog\Jalali\Jalalian::fromDateTime($req->created_at)->format('Y/m/d') }}</span>
                        @if ($req->city)
                        <span>{{ $req->province }} / {{ $req->city }}</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Progress stepper --}}
            <div class="overflow-x-auto pb-1">
                <div class="flex items-center min-w-max">
                    @foreach ($allSteps as $i => $s)
                    @php
                        $isDone    = $s['step'] < $currentStep;
                        $isCurrent = $s['step'] === $currentStep;
                    @endphp
                    <div class="flex flex-col items-center">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold border-2 transition
                            {{ $isDone    ? 'bg-green-500 border-green-500 text-white' : '' }}
                            {{ $isCurrent ? 'bg-amber-500 border-amber-500 text-white' : '' }}
                            {{ !$isDone && !$isCurrent ? 'bg-white border-gray-300 text-gray-400' : '' }}">
                            @if ($isDone) ✓ @else {{ $s['icon'] }} @endif
                        </div>
                        <span class="mt-1 text-xs text-center w-14 leading-tight
                            {{ $isCurrent ? 'text-amber-600 font-semibold' : ($isDone ? 'text-green-600' : 'text-gray-400') }}">
                            {{ $s['label'] }}
                        </span>
                    </div>
                    @if ($i < $totalSteps - 1)
                    <div class="w-8 h-1 mx-0.5 rounded mb-5
                        {{ $allSteps[$i + 1]['step'] <= $currentStep ? 'bg-green-400' : 'bg-gray-200' }}">
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>

            {{-- اطلاعات تکمیلی --}}
            @if ($req->contractor_name || $req->selected_package_title)
            <div class="mt-4 pt-3 border-t border-gray-100 flex flex-wrap gap-x-6 gap-y-1.5 text-sm text-gray-600">
                @if ($req->contractor_name)
                <span class="flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    پیمانکار: <span class="font-medium text-gray-800">{{ $req->contractor_name }}</span>
                </span>
                @endif
                @if ($req->selected_package_title)
                <span class="flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                    </svg>
                    پکیج: <span class="font-medium text-gray-800">{{ $req->selected_package_title }}</span>
                    @if ($req->selected_package_price)
                    — <span class="font-semibold text-amber-700" dir="ltr">{{ number_format($req->selected_package_price) }} ریال</span>
                    @endif
                </span>
                @endif
            </div>
            @endif

            {{-- Footer actions --}}
            <div class="mt-4 pt-4 border-t border-gray-100 flex flex-wrap items-center gap-3">
                <a href="{{ route('solar-plant-requests.detail', $req) }}"
                   class="inline-flex items-center gap-1.5 bg-amber-50 border border-amber-300 text-amber-700 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-amber-100 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    جزئیات
                </a>

                @if ($req->status === SolarPlantRequestStatus::AWAITING_USER_APPROVAL)
                <a href="{{ route('solar-plant-requests.expert-report.show', $req) }}"
                   class="inline-flex items-center gap-1.5 bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-indigo-700 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0119 9.414V19a2 2 0 01-2 2z"/>
                    </svg>
                    بررسی گزارش کارشناسی
                </a>
                @endif

                @if ($req->status === SolarPlantRequestStatus::PACKAGE_SELECTION)
                <a href="{{ route('solar-plant-requests.package-selection', $req) }}"
                   class="inline-flex items-center gap-1.5 bg-violet-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-violet-700 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                    </svg>
                    انتخاب پکیج
                </a>
                @endif

                @if ($req->status === SolarPlantRequestStatus::CONTRACTOR_SELECTION)
                <a href="{{ route('solar-plant-requests.contractor-selection', $req) }}"
                   class="inline-flex items-center gap-1.5 bg-purple-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-purple-700 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    انتخاب پیمانکار
                </a>
                @endif

                @if ($req->status === SolarPlantRequestStatus::CERTIFICATE_ISSUED)
                <a href="{{ route('solar-plant-requests.show', $req) }}"
                   class="inline-flex items-center gap-1.5 bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-green-700 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    مشاهده گواهی سلامت
                </a>
                @endif
            </div>

        </div>{{-- /req-card --}}
        @endforeach
    </div>
    @endif

</main>

<footer class="footer-glass mt-8">
    <div class="container px-6 py-5 flex flex-col md:flex-row items-center justify-between gap-3 text-sm">
        <div class="text-white/65">© اتحادیه کشوری سوخت‌های جایگزین و انرژی‌های تجدیدپذیر</div>
        <div class="text-white/55">ایمیل: info@altfuel.ir</div>
    </div>
</footer>

</div>
</body>
</html>
