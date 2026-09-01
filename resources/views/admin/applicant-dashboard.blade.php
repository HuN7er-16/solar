<!doctype html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ url('behin/logo.ico') . '?' . config('app.version') }}">
    <title>داشبورد متقاضی</title>
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

        .action-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .action-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.10);
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-800 min-h-screen flex flex-col">

    {{-- Header --}}
    <header class="bg-gradient-to-l from-amber-400 via-yellow-300 to-lime-300 text-gray-900">
        <div class="container px-6 py-8 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="text-center md:text-right">
                <h1 class="text-2xl md:text-3xl font-bold">داشبورد متقاضی</h1>
                <p class="mt-2 text-sm md:text-base">سامانه جامع انرژی‌های تجدیدپذیر و خورشیدی اصناف (ساتا اصناف)</p>
            </div>
            <div class="flex items-center gap-3">
                @php
                    $logoPath = public_path('behin/images/logo-union.png');
                    $logoUrl = asset('behin/images/logo-union.png');
                @endphp
                @if (file_exists($logoPath))
                    <img src="{{ $logoUrl }}" alt="لوگو اتحادیه کشوری سوخت‌های جایگزین" class="h-16 w-auto object-contain hidden sm:block">
                @endif
                <span class="bg-white/70 text-gray-800 px-4 py-2 rounded-lg text-sm font-semibold">
                    {{ auth()->user()->name }}
                </span>
                <a href="{{ route('logout') }}"
                    class="flex items-center gap-2 bg-gray-900 text-white px-5 py-2.5 rounded-lg font-semibold text-sm hover:bg-gray-700 transition">
                    خروج
                </a>
            </div>
        </div>
    </header>

    <main class="container px-6 py-8 flex-grow">

        {{-- Welcome --}}
        <div class="rounded-2xl shadow-xl p-8 mb-8 text-white relative overflow-hidden"
            style="background: linear-gradient(135deg, #FFB74D 0%, #FF9800 100%);">
            <div class="absolute -top-16 -left-16 w-56 h-56 bg-white/10 rounded-full"></div>
            <div class="absolute -bottom-20 -right-10 w-48 h-48 bg-white/10 rounded-full"></div>
            <h2 class="text-xl md:text-2xl font-extrabold mb-2">خوش آمدید، {{ auth()->user()->name }}</h2>
            <p class="text-sm md:text-base opacity-90">از این بخش می‌توانید درخواست نیروگاه خورشیدی خود را ثبت کنید و روند بررسی آن را پیگیری نمایید.</p>
        </div>

        {{-- Actions --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- My requests --}}
            <div class="action-card bg-white rounded-2xl shadow-sm border border-gray-100 p-8 text-center">
                <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-amber-50 border border-amber-200 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0119 9.414V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h3 class="font-bold text-lg mb-2">درخواست‌های من</h3>
                <p class="text-sm text-gray-500 mb-6">مشاهده وضعیت و جزئیات درخواست‌های ثبت‌شده شما</p>
                <a href="{{ route('solar-plant-requests.index') }}"
                    class="inline-flex items-center justify-center gap-2 bg-amber-500 text-white px-8 py-3 rounded-lg font-semibold hover:bg-amber-600 transition">
                    رفتن به درخواست‌ها
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
            </div>

            {{-- New request --}}
            <div class="action-card bg-white rounded-2xl shadow-sm border border-gray-100 p-8 text-center">
                <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-lime-50 border border-lime-200 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-lime-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                </div>
                <h3 class="font-bold text-lg mb-2">ثبت درخواست جدید</h3>
                <p class="text-sm text-gray-500 mb-6">ثبت درخواست احداث نیروگاه خورشیدی در ۴ مرحله ساده</p>
                <a href="{{ route('solar-plant-requests.apply') }}"
                    class="inline-flex items-center justify-center gap-2 bg-gray-900 text-white px-8 py-3 rounded-lg font-semibold hover:bg-gray-700 transition">
                    ثبت درخواست نیروگاه
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
            </div>
        </div>
    </main>

    <footer class="bg-gray-900 text-gray-100 mt-12">
        <div class="container px-6 py-6 flex flex-col md:flex-row items-center justify-between gap-4 text-sm">
            <div>اتحادیه کشوری سوخت‌های جایگزین و خدمات وابسته</div>
            <div class="flex gap-4">
                <span>ایمیل: info@altfuel.ir</span>
            </div>
        </div>
    </footer>

</body>

</html>
