<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" href="{{ url('behin/logo.ico') . '?' . config('app.version') }}">
    <title>همه درخواست‌ها - نیروگاه خورشیدی</title>
    <script src="{{ url('behin/behin-dist/dist/js/tailwind-3.4.17.min.js') }}"></script>
    <link href="{{ url('behin/behin-dist/css/css2.css') }}?family=Vazirmatn:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        html, body { font-family: 'Vazirmatn', sans-serif; }
        .container { max-width: 1100px; margin-inline: auto; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 min-h-screen">

@php
use SolarPlantRequests\Enums\SolarPlantRequestStatus;

$stepsMap = [
    'initial_registration'   => ['label' => 'ثبت اولیه',        'icon' => '📋', 'step' => 1],
    'under_review'           => ['label' => 'بررسی کارشناسی',   'icon' => '🔍', 'step' => 2],
    'awaiting_user_approval' => ['label' => 'تایید گزارش',      'icon' => '✋', 'step' => 3],
    'package_selection'      => ['label' => 'انتخاب پکیج',      'icon' => '📦', 'step' => 4],
    'contractor_selection'   => ['label' => 'انتخاب پیمانکار',  'icon' => '👷', 'step' => 5],
    'contractor_assigned'    => ['label' => 'آماده پروژه',      'icon' => '📝', 'step' => 6],
    'equipment_installation' => ['label' => 'نصب تجهیزات',      'icon' => '⚙️', 'step' => 7],
    'inspection'             => ['label' => 'بازرسی',           'icon' => '🔎', 'step' => 8],
    'certificate_issued'     => ['label' => 'صدور گواهی',       'icon' => '✅', 'step' => 9],
];
$allSteps = array_values($stepsMap);
@endphp

{{-- Header --}}
<header class="bg-gradient-to-l from-amber-400 via-yellow-300 to-lime-300 text-gray-900">
    <div class="container px-6 py-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold">همه درخواست‌ها</h1>
            <p class="mt-1 text-sm">مدیریت و پیگیری درخواست‌های نیروگاه خورشیدی</p>
        </div>
        <a href="{{ route('admin.dashboard') }}"
           class="flex items-center gap-2 bg-gray-900 text-white px-5 py-2.5 rounded-lg font-semibold text-sm hover:bg-gray-700 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            صفحه اصلی
        </a>
    </div>
</header>

<main class="container px-6 py-8">

    @if (session('success'))
    <div class="mb-6 rounded-xl bg-green-50 border border-green-300 text-green-800 px-4 py-3 text-sm flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
        {{ session('success') }}
    </div>
    @endif
    @if (session('error'))
    <div class="mb-6 rounded-xl bg-red-50 border border-red-300 text-red-800 px-4 py-3 text-sm">
        {{ session('error') }}
    </div>
    @endif

    {{-- فیلترها --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
        <div class="flex items-center gap-2 mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
            </svg>
            <h2 class="font-bold text-lg">فیلتر درخواست‌ها</h2>
        </div>
        <form method="GET" action="{{ route('solar-plant-requests.all-requests.index') }}">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">وضعیت</label>
                    <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-amber-400">
                        <option value="">همه وضعیت‌ها</option>
                        @foreach (SolarPlantRequestStatus::cases() as $s)
                        <option value="{{ $s->value }}" {{ request('status') === $s->value ? 'selected' : '' }}>{{ $s->label() }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">نام متقاضی</label>
                    <input type="text" name="name" value="{{ request('name') }}" placeholder="نام / نام خانوادگی / شرکت"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">شماره / کد</label>
                    <input type="text" name="number" value="{{ request('number') }}" dir="ltr" placeholder="کد پیگیری / کد ملی / موبایل"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 text-left">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">از تاریخ (شمسی)</label>
                    <input type="text" name="date_from" value="{{ request('date_from') }}" dir="ltr" inputmode="numeric" placeholder="1404/01/01"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 text-left">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">تا تاریخ (شمسی)</label>
                    <input type="text" name="date_to" value="{{ request('date_to') }}" dir="ltr" inputmode="numeric" placeholder="1404/06/30"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-amber-400 text-left">
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-3 mt-4 pt-4 border-t border-gray-100">
                <button type="submit" class="inline-flex items-center gap-1.5 bg-amber-500 text-white px-6 py-2 rounded-lg text-sm font-semibold hover:bg-amber-600 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    جستجو
                </button>
                @if (request()->anyFilled(['status','name','number','date_from','date_to']))
                <a href="{{ route('solar-plant-requests.all-requests.index') }}"
                   class="inline-flex items-center gap-1.5 border border-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-50 transition">
                    حذف فیلترها
                </a>
                @endif
                <span class="text-sm text-gray-500 mr-auto">{{ $requests->count() }} درخواست یافت شد</span>
            </div>
        </form>
    </div>

    {{-- لیست درخواست‌ها --}}
    @if ($requests->isEmpty())
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 px-8 py-16 text-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-16 w-16 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0119 9.414V19a2 2 0 01-2 2z"/>
        </svg>
        <p class="text-gray-500 text-lg">درخواستی برای نمایش وجود ندارد.</p>
    </div>
    @else
    <div class="space-y-4">
        @foreach ($requests as $req)
        @php
            $currentStep = $stepsMap[$req->status->value]['step'] ?? 1;
            $totalSteps  = count($allSteps);
            $badgeClass  = $req->status->badgeClass();
        @endphp

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

            {{-- Card header --}}
            <div class="flex flex-wrap items-start justify-between gap-3 mb-5">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="font-bold text-lg">
                            @if ($req->applicant_type->value === 'company')
                                {{ $req->company_name }}
                            @else
                                {{ $req->first_name }} {{ $req->last_name }}
                            @endif
                        </span>
                        <span class="text-xs px-2.5 py-0.5 rounded-full {{ $badgeClass }} font-semibold">
                            {{ $req->status_label }}
                        </span>
                    </div>
                    <div class="text-sm text-gray-500 flex flex-wrap gap-x-4 gap-y-1">
                        <span>کد: <span class="font-mono font-semibold text-gray-700" dir="ltr">{{ $req->unique_code }}</span></span>
                        <span>{{ \Morilog\Jalali\Jalalian::fromDateTime($req->created_at)->format('Y/m/d') }}</span>
                        <span dir="ltr">{{ $req->mobile }}</span>
                        @if ($req->city)
                        <span>{{ $req->province }} / {{ $req->city }}</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Progress stepper --}}
            <div class="overflow-x-auto pb-1 mb-5">
                <div class="flex items-center min-w-max">
                    @foreach ($allSteps as $i => $s)
                    @php $isDone = $s['step'] < $currentStep; $isCurrent = $s['step'] === $currentStep; @endphp
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
                    <div class="w-8 h-1 mx-0.5 rounded mb-5 {{ $allSteps[$i+1]['step'] <= $currentStep ? 'bg-green-400' : 'bg-gray-200' }}"></div>
                    @endif
                    @endforeach
                </div>
            </div>

            {{-- اطلاعات تکمیلی: کارشناس، پکیج، پیمانکار --}}
            @if ($req->expert_name || $req->contractor_name || $req->selected_package_title)
            <div class="mb-4 flex flex-wrap gap-x-6 gap-y-1.5 text-sm text-gray-600">
                @if ($req->expert_name)
                <span class="flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    کارشناس: <span class="font-medium text-gray-800">{{ $req->expert_name }}</span>
                </span>
                @endif
                @if ($req->contractor_name)
                <span class="flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-purple-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    پیمانکار: <span class="font-medium text-gray-800">{{ $req->contractor_name }}</span>
                </span>
                @endif
                @if ($req->selected_package_title)
                <span class="flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-amber-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
            <div class="pt-4 border-t border-gray-100 flex flex-wrap items-center gap-3">

                {{-- جزئیات (همیشه) --}}
                <a href="{{ route('solar-plant-requests.detail', $req) }}"
                   class="inline-flex items-center gap-1.5 bg-amber-50 border border-amber-300 text-amber-700 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-amber-100 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    جزئیات
                </a>

                {{-- ─── تخصیص کارشناس (وضعیت: ثبت اولیه) ─── --}}
                @if ($req->status === SolarPlantRequestStatus::INITIAL)
                <button type="button"
                        onclick="openExpertModal({{ $req->id }}, '{{ addslashes($req->first_name . ' ' . $req->last_name ?: $req->company_name) }}')"
                        class="inline-flex items-center gap-1.5 bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-700 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    تخصیص کارشناس
                </button>
                @endif

                {{-- ─── ساخت پروژه (وضعیت: آماده پروژه) ─── --}}
                @if ($req->status === SolarPlantRequestStatus::CONTRACTOR_ASSIGNED)
                @php
                    $projectUrl = route('solar-plant-equipment.projects.create', ['request_id' => $req->id]);
                @endphp
                <a href="{{ $projectUrl }}"
                   class="inline-flex items-center gap-1.5 bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-green-700 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    ساخت پروژه
                </a>
                @endif

                {{-- ─── بازرسی (وضعیت: بازرسی) ─── --}}
                @if ($req->status === SolarPlantRequestStatus::INSPECTION)
                <a href="{{ route('solar-plant-requests.inspection.show', $req) }}"
                   class="inline-flex items-center gap-1.5 bg-yellow-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-yellow-600 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    ثبت نتیجه بازرسی
                </a>
                @endif

            </div>

        </div>
        @endforeach
    </div>
    @endif

</main>

{{-- ─── Modal تخصیص کارشناس ─── --}}
<div id="expertModal"
     class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6">
        <h3 class="font-bold text-lg mb-1">تخصیص کارشناس</h3>
        <p id="expertModalDesc" class="text-sm text-gray-500 mb-4"></p>

        <form id="expertForm" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">انتخاب کارشناس</label>
                <select name="expert_user_id"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <option value="">-- انتخاب کنید --</option>
                    @foreach ($experts as $expert)
                    <option value="{{ $expert->id }}">{{ $expert->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-3">
                <button type="submit"
                        class="flex-1 bg-blue-600 text-white px-4 py-2.5 rounded-lg font-bold text-sm hover:bg-blue-700 transition">
                    تخصیص
                </button>
                <button type="button"
                        onclick="closeExpertModal()"
                        class="flex-1 border border-gray-300 text-gray-700 px-4 py-2.5 rounded-lg font-semibold text-sm hover:bg-gray-50 transition">
                    انصراف
                </button>
            </div>
        </form>
    </div>
</div>

<footer class="bg-gray-900 text-gray-100 mt-12">
    <div class="container px-6 py-6 flex flex-col md:flex-row items-center justify-between gap-4 text-sm">
        <div>اتحادیه کشوری سوخت‌های جایگزین و انرژی‌های تجدیدپذیر</div>
        <div><span>ایمیل: info@altfuel.ir</span></div>
    </div>
</footer>

<script>
    function openExpertModal(requestId, applicantName) {
        document.getElementById('expertModalDesc').textContent = 'متقاضی: ' + applicantName;
        document.getElementById('expertForm').action =
            '/admin/request-expert-review/' + requestId + '/assign-expert';
        document.getElementById('expertModal').classList.remove('hidden');
    }

    function closeExpertModal() {
        document.getElementById('expertModal').classList.add('hidden');
    }

    // بستن با کلیک بیرون modal
    document.getElementById('expertModal').addEventListener('click', function(e) {
        if (e.target === this) closeExpertModal();
    });
</script>

</body>
</html>
