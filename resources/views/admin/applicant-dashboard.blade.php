<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ url('behin/logo.ico') . '?' . config('app.version') }}">
    <title>داشبورد متقاضی - ساتا اصناف</title>
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
        /* Welcome banner */
        .welcome-banner {
            background: linear-gradient(135deg, rgba(245,158,11,0.85) 0%, rgba(249,115,22,0.85) 100%);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid rgba(255,255,255,0.2);
        }
        /* Colorful action cards */
        .action-card {
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255,255,255,0.18);
            box-shadow: 0 6px 30px rgba(0,0,0,0.30);
            transition: transform 0.28s ease, box-shadow 0.28s ease;
        }
        .action-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 16px 48px rgba(0,0,0,0.40);
        }
        /* card 1 — amber/orange gradient (درخواست‌های من) */
        .action-card-amber {
            background: linear-gradient(145deg, #f59e0b 0%, #f97316 60%, #ea580c 100%);
        }
        /* card 2 — teal/green gradient (ثبت درخواست جدید) */
        .action-card-teal {
            background: linear-gradient(145deg, #0d9488 0%, #059669 60%, #047857 100%);
        }
        /* decorative circle */
        .card-circle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255,255,255,0.10);
            pointer-events: none;
        }
        /* icon wrapper on coloured card */
        .card-icon-wrap {
            width: 3.5rem;
            height: 3.5rem;
            border-radius: 1rem;
            background: rgba(255,255,255,0.20);
            backdrop-filter: blur(4px);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.25rem;
            flex-shrink: 0;
        }
        /* Stats glass chips */
        .stat-chip {
            background: rgba(255,255,255,0.12);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid rgba(255,255,255,0.2);
        }
        .btn-card-amber {
            background: rgba(0,0,0,0.22);
            border: 1.5px solid rgba(255,255,255,0.35);
            color: #fff;
            transition: all 0.2s ease;
        }
        .btn-card-amber:hover {
            background: rgba(0,0,0,0.38);
            border-color: rgba(255,255,255,0.6);
            transform: translateY(-1px);
        }
        .btn-card-teal {
            background: rgba(0,0,0,0.22);
            border: 1.5px solid rgba(255,255,255,0.35);
            color: #fff;
            transition: all 0.2s ease;
        }
        .btn-card-teal:hover {
            background: rgba(0,0,0,0.38);
            border-color: rgba(255,255,255,0.6);
            transform: translateY(-1px);
        }
        /* kept for compat */
        .btn-primary {
            background: linear-gradient(135deg, #f59e0b, #f97316);
            box-shadow: 0 3px 12px rgba(245,158,11,0.35);
            transition: all 0.2s ease;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #d97706, #ea580c);
            transform: translateY(-1px);
            box-shadow: 0 5px 16px rgba(245,158,11,0.5);
        }
        .btn-dark {
            background: rgba(17,24,39,0.85);
            border: 1px solid rgba(255,255,255,0.1);
            transition: all 0.2s ease;
        }
        .btn-dark:hover {
            background: rgba(17,24,39,1);
            transform: translateY(-1px);
        }
        .user-chip {
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255,255,255,0.25);
        }
    </style>
</head>

<body class="bg-page min-h-screen flex flex-col">
<div class="bg-overlay flex flex-col">

    {{-- Header --}}
    <header class="header-glass sticky top-0 z-50">
        <div class="max-w-5xl mx-auto px-6 py-4 flex items-center justify-between gap-4">
            {{-- Logo + title --}}
            <div class="flex items-center gap-3">
                @php
                    $logoPath = public_path('behin/images/logo-union.png');
                    $logoUrl  = asset('behin/images/logo-union.png');
                @endphp
                @if(file_exists($logoPath))
                    <img src="{{ $logoUrl }}" alt="لوگو اتحادیه" class="h-11 w-auto object-contain hidden sm:block">
                @else
                    <div class="sun-badge w-10 h-10 rounded-xl flex items-center justify-center text-white font-bold text-lg flex-shrink-0">☀</div>
                @endif
                <div class="hidden md:block">
                    <p class="text-white font-bold text-sm leading-tight">ساتا اصناف</p>
                    <p class="text-amber-300 text-xs">سامانه جامع انرژی‌های تجدیدپذیر</p>
                </div>
            </div>

            {{-- User + logout --}}
            <div class="flex items-center gap-3">
                <div class="user-chip flex items-center gap-2 px-3 py-2 rounded-xl">
                    <div class="w-7 h-7 rounded-full bg-amber-400 flex items-center justify-center text-xs font-bold text-white flex-shrink-0">
                        {{ mb_substr(auth()->user()->name ?? '؟', 0, 1) }}
                    </div>
                    <span class="text-white text-sm font-medium hidden sm:block">{{ auth()->user()->name }}</span>
                </div>
                <a href="{{ route('logout') }}"
                   class="btn-dark flex items-center gap-2 text-white px-4 py-2 rounded-xl text-sm font-semibold">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    <span class="hidden sm:inline">خروج</span>
                </a>
            </div>
        </div>
    </header>

    <main class="max-w-5xl mx-auto w-full px-6 py-10 flex-grow">

        {{-- Welcome banner --}}
        <div class="welcome-banner rounded-2xl p-7 mb-8 relative overflow-hidden">
            <div class="absolute -top-12 -left-12 w-48 h-48 bg-white/10 rounded-full pointer-events-none"></div>
            <div class="absolute -bottom-16 -right-8 w-40 h-40 bg-white/10 rounded-full pointer-events-none"></div>
            <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-extrabold text-white mb-1">
                        خوش آمدید، {{ auth()->user()->name }} 👋
                    </h2>
                    <p class="text-white/85 text-sm leading-7 max-w-xl">
                        از این بخش می‌توانید درخواست نیروگاه خورشیدی خود را ثبت کنید و روند بررسی آن را پیگیری نمایید.
                    </p>
                </div>
                <div class="flex gap-3 flex-shrink-0">
                    <div class="stat-chip rounded-xl px-4 py-3 text-center">
                        <div class="text-white text-xl font-bold">☀</div>
                        <div class="text-white/70 text-xs mt-1">نیروگاه</div>
                    </div>
                    <div class="stat-chip rounded-xl px-4 py-3 text-center">
                        <div class="text-white text-xl font-bold">⚡</div>
                        <div class="text-white/70 text-xs mt-1">انرژی</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Action cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- My requests — amber/orange --}}
            <div class="action-card action-card-amber rounded-2xl p-8 flex flex-col items-center text-center">
                {{-- decorative circles --}}
                <div class="card-circle w-40 h-40 -top-10 -right-10"></div>
                <div class="card-circle w-24 h-24 -bottom-8 -left-6"></div>

                <div class="card-icon-wrap relative z-10">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0119 9.414V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h3 class="relative z-10 font-extrabold text-white text-xl mb-2">درخواست‌های من</h3>
                <p class="relative z-10 text-white/80 text-sm mb-7 leading-6">مشاهده وضعیت و جزئیات<br>درخواست‌های ثبت‌شده شما</p>
                <a href="{{ route('solar-plant-requests.index') }}"
                    class="btn-card-amber relative z-10 inline-flex items-center justify-center gap-2 px-8 py-3 rounded-xl font-bold w-full text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    رفتن به درخواست‌ها
                </a>
            </div>

            {{-- New request — teal/green --}}
            <div class="action-card action-card-teal rounded-2xl p-8 flex flex-col items-center text-center">
                {{-- decorative circles --}}
                <div class="card-circle w-44 h-44 -top-12 -left-12"></div>
                <div class="card-circle w-20 h-20 -bottom-6 -right-4"></div>

                <div class="card-icon-wrap relative z-10">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                </div>
                <h3 class="relative z-10 font-extrabold text-white text-xl mb-2">ثبت درخواست جدید</h3>
                <p class="relative z-10 text-white/80 text-sm mb-7 leading-6">ثبت درخواست احداث نیروگاه<br>خورشیدی در ۴ مرحله ساده</p>
                <a href="{{ route('solar-plant-requests.apply') }}"
                    class="btn-card-teal relative z-10 inline-flex items-center justify-center gap-2 px-8 py-3 rounded-xl font-bold w-full text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    ثبت درخواست نیروگاه
                </a>
            </div>

        </div>

        {{-- Info strip --}}
        <div class="mt-8 grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="stat-chip rounded-xl px-5 py-4 flex items-center gap-3">
                <span class="text-2xl">🔍</span>
                <div>
                    <p class="text-white text-sm font-semibold">بررسی تخصصی</p>
                    <p class="text-white/55 text-xs mt-0.5">کارشناسی مداوم پروژه</p>
                </div>
            </div>
            <div class="stat-chip rounded-xl px-5 py-4 flex items-center gap-3">
                <span class="text-2xl">👷</span>
                <div>
                    <p class="text-white text-sm font-semibold">پیمانکاران معتبر</p>
                    <p class="text-white/55 text-xs mt-0.5">شبکه سراسری کشور</p>
                </div>
            </div>
            <div class="stat-chip rounded-xl px-5 py-4 flex items-center gap-3">
                <span class="text-2xl">📦</span>
                <div>
                    <p class="text-white text-sm font-semibold">پکیج‌های اختصاصی</p>
                    <p class="text-white/55 text-xs mt-0.5">متناسب با نیاز شما</p>
                </div>
            </div>
        </div>

    </main>

    {{-- Footer --}}
    <footer class="footer-glass">
        <div class="max-w-5xl mx-auto px-6 py-5 flex flex-col md:flex-row items-center justify-between gap-3 text-sm">
            <div class="text-white/65">© اتحادیه کشوری سوخت‌های جایگزین و انرژی‌های تجدیدپذیر</div>
            <div class="flex gap-6 text-white/55">
                <span>ایمیل: info@altfuel.ir</span>
            </div>
        </div>
    </footer>

</div>
</body>
</html>
