<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>سامانه جامع انرژی‌های تجدیدپذیر و خورشیدی اصناف (ساتا اصناف)</title>
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
            background: linear-gradient(135deg, rgba(0,0,0,0.62) 0%, rgba(10,30,10,0.55) 100%);
        }
        .glass-card {
            background: rgba(255,255,255,0.13);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            border: 1px solid rgba(255,255,255,0.25);
            box-shadow: 0 8px 40px rgba(0,0,0,0.35);
        }
        .glass-input {
            background: rgba(255,255,255,0.18);
            border: 1px solid rgba(255,255,255,0.35);
            color: #fff;
        }
        .glass-input::placeholder { color: rgba(255,255,255,0.55); }
        .glass-input:focus {
            outline: none;
            border-color: #f59e0b;
            background: rgba(255,255,255,0.22);
            box-shadow: 0 0 0 3px rgba(245,158,11,0.25);
        }
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

        /* --- collapse toggle button --- */
        .collapse-btn {
            background: rgba(255,255,255,0.10);
            border: 1px solid rgba(255,255,255,0.22);
            color: rgba(255,255,255,0.90);
            transition: background 0.2s, border-color 0.2s;
            cursor: pointer;
            width: 100%;
            text-align: right;
        }
        .collapse-btn:hover {
            background: rgba(255,255,255,0.16);
            border-color: rgba(245,158,11,0.55);
        }
        .collapse-btn.open {
            background: rgba(245,158,11,0.18);
            border-color: rgba(245,158,11,0.55);
        }
        .collapse-btn .chevron {
            transition: transform 0.25s ease;
            flex-shrink: 0;
        }
        .collapse-btn.open .chevron { transform: rotate(180deg); }

        /* --- collapse content panel --- */
        .collapse-panel {
            display: none;
        }
        .collapse-panel.open {
            display: block;
        }

        /* --- scrollable box that holds the list --- */
        .collapse-box {
            background: rgba(0,0,0,0.30);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 0.875rem;
            padding: 0.5rem;
            max-height: 260px;
            overflow-y: auto;
            overflow-x: hidden;
            scrollbar-width: thin;
            scrollbar-color: rgba(255,255,255,0.2) transparent;
        }
        .collapse-box::-webkit-scrollbar { width: 4px; }
        .collapse-box::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.2); border-radius: 4px; }

        /* --- contractor / package list items --- */
        .list-item {
            background: rgba(255,255,255,0.07);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 0.75rem;
            padding: 0.75rem 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .list-item:hover { background: rgba(255,255,255,0.12); }
        .list-avatar {
            width: 2.25rem;
            height: 2.25rem;
            border-radius: 50%;
            background: linear-gradient(135deg, #f59e0b, #f97316);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.85rem;
            color: #fff;
            flex-shrink: 0;
        }
        .pkg-badge {
            background: linear-gradient(135deg, #10b981, #059669);
            color: #fff;
            border-radius: 9999px;
            padding: 0.15rem 0.65rem;
            font-size: 0.7rem;
            font-weight: 700;
            white-space: nowrap;
        }
        .price-tag {
            color: #fcd34d;
            font-size: 0.75rem;
            font-weight: 700;
            font-family: monospace;
            white-space: nowrap;
            margin-right: auto;
        }
        /* scrollable inner list — no longer used, kept for safety */
    </style>
</head>

<body class="bg-page min-h-screen flex flex-col">
<div class="bg-overlay min-h-screen flex flex-col">

    {{-- ─── Header ─────────────────────────────────────────── --}}
    <header class="header-glass">
        <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="sun-badge w-10 h-10 rounded-full flex items-center justify-center text-white font-bold text-lg flex-shrink-0">☀</div>
                <div>
                    <p class="text-white font-bold text-sm leading-tight">سامانه جامع انرژی‌های تجدیدپذیر</p>
                    <p class="text-amber-300 text-xs">اتحادیه کشوری سوخت‌های جایگزین</p>
                </div>
            </div>
            <div class="text-white/70 text-xs hidden md:block">ساتا اصناف</div>
        </div>
    </header>

    {{-- ─── Load data from DB ───────────────────────────────── --}}
    @php
        // پیمانکاران
        $contractors = collect();
        if (class_exists(\ContractorCatalog\Models\Contractor::class)) {
            $contractors = \ContractorCatalog\Models\Contractor::query()
                ->select(['id','company_name','ceo_name','province','city','license_expiry_date'])
                ->orderBy('company_name')
                ->get();
        }

        // پکیج‌ها
        $packages = collect();
        if (class_exists(\SolarPlantRequests\Models\SolarPlantPackage::class)) {
            $packages = \SolarPlantRequests\Models\SolarPlantPackage::active()->get();
        }
    @endphp

    {{-- ─── Main ───────────────────────────────────────────── --}}
    <main class="flex-grow flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-5xl grid grid-cols-1 lg:grid-cols-2 gap-10 items-start">

            {{-- ── Left side: info + collapse panels ── --}}
            <div class="text-white hidden lg:flex flex-col gap-4">

                <div>
                    <h1 class="text-3xl xl:text-4xl font-extrabold leading-tight mb-3">
                        سامانه جامع انرژی‌های<br>
                        <span class="text-amber-400">تجدیدپذیر خورشیدی</span><br>
                        اصناف
                    </h1>
                    <p class="text-white/70 text-sm leading-7">
                        به سامانه رسمی اتحادیه کشوری خوش آمدید.<br>
                        برای ورود شماره موبایل خود را وارد کنید.
                    </p>
                </div>

                {{-- ── Collapse: Contractors ── --}}
                <div>
                    <button type="button" class="collapse-btn rounded-xl px-4 py-3 flex items-center gap-3"
                            onclick="toggleCollapse('contractors-panel', this)">
                        <span class="text-xl">👷</span>
                        <span class="font-semibold text-sm flex-1">پیمانکاران معتبر کشوری</span>
                        @if($contractors->count())
                            <span class="text-xs bg-amber-500/30 text-amber-300 rounded-full px-2 py-0.5 font-bold">
                                {{ $contractors->count() }}
                            </span>
                        @endif
                        <svg class="chevron h-4 w-4 text-white/60" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div id="contractors-panel" class="collapse-panel mt-2">
                        <div class="collapse-box space-y-1.5">
                            @forelse($contractors as $c)
                                <div class="list-item">
                                    <div class="list-avatar">
                                        {{ mb_substr($c->company_name, 0, 1) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-white text-sm font-semibold truncate">{{ $c->company_name }}</p>
                                        <p class="text-white/55 text-xs truncate">
                                            {{ $c->ceo_name }}
                                            @if($c->province) — {{ $c->province }}@if($c->city) / {{ $c->city }}@endif @endif
                                        </p>
                                    </div>
                                    @if($c->license_expiry_date)
                                        @php $valid = $c->license_expiry_date->isFuture(); @endphp
                                        <span class="text-xs px-2 py-0.5 rounded-full font-semibold flex-shrink-0
                                            {{ $valid ? 'bg-green-500/25 text-green-300' : 'bg-red-500/25 text-red-300' }}">
                                            {{ $valid ? 'معتبر' : 'منقضی' }}
                                        </span>
                                    @endif
                                </div>
                            @empty
                                <p class="text-white/40 text-sm text-center py-4">پیمانکاری ثبت نشده است.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- ── Collapse: Packages ── --}}
                <div>
                    <button type="button" class="collapse-btn rounded-xl px-4 py-3 flex items-center gap-3"
                            onclick="toggleCollapse('packages-panel', this)">
                        <span class="text-xl">📦</span>
                        <span class="font-semibold text-sm flex-1">پکیج‌های متنوع و اختصاصی</span>
                        @if($packages->count())
                            <span class="text-xs bg-amber-500/30 text-amber-300 rounded-full px-2 py-0.5 font-bold">
                                {{ $packages->count() }}
                            </span>
                        @endif
                        <svg class="chevron h-4 w-4 text-white/60" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div id="packages-panel" class="collapse-panel mt-2">
                        <div class="collapse-box space-y-1.5">
                            @forelse($packages as $p)
                                <div class="list-item">
                                    <div class="list-avatar" style="background: linear-gradient(135deg,#10b981,#059669);">
                                        ☀
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-white text-sm font-semibold truncate">{{ $p->title }}</p>
                                        @if($p->description)
                                            <p class="text-white/50 text-xs truncate">{{ $p->description }}</p>
                                        @endif
                                    </div>
                                    <div class="flex flex-col items-end gap-1 flex-shrink-0">
                                        <span class="pkg-badge">{{ $p->capacity_kw }} kW</span>
                                        <span class="price-tag">{{ number_format($p->price) }} ریال</span>
                                    </div>
                                </div>
                            @empty
                                <p class="text-white/40 text-sm text-center py-4">پکیجی ثبت نشده است.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- ── Static info chip ── --}}
                <div style="background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.15);"
                     class="rounded-xl px-4 py-3 flex items-center gap-3">
                    <span class="text-xl">🔍</span>
                    <span class="text-sm text-white/80 font-medium">بررسی تخصصی و مداوم پروژه‌ها در تمام مراحل</span>
                </div>

            </div>

            {{-- ── Right side: login card ── --}}
            <div class="glass-card rounded-2xl p-8 w-full max-w-md mx-auto">

                <div class="flex flex-col items-center mb-7">
                    <div class="sun-badge w-14 h-14 rounded-2xl flex items-center justify-center text-white text-2xl mb-4">☀</div>
                    <h2 class="text-white text-xl font-extrabold">ورود به سامانه</h2>
                    <p class="text-white/60 text-sm mt-1">شماره موبایل خود را وارد کنید</p>
                </div>

                @if(session('error'))
                    <div class="rounded-xl bg-red-500/20 border border-red-400/40 text-red-200 px-4 py-3 text-sm mb-5">
                        {{ session('error') }}
                    </div>
                @endif
                @if($errors->any())
                    <div class="rounded-xl bg-red-500/20 border border-red-400/40 text-red-200 px-4 py-3 text-sm mb-5">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('otp.send') }}" class="flex flex-col gap-4">
                    @csrf
                    <div>
                        <label for="phone" class="block text-sm font-semibold text-white/85 mb-2">شماره موبایل</label>
                        <input type="text" name="phone" id="phone" dir="ltr" inputmode="numeric"
                            placeholder="09123456789"
                            class="glass-input w-full rounded-xl px-4 py-3 text-center text-base tracking-widest font-mono"
                            required>
                    </div>
                    <button type="submit" class="btn-submit w-full text-white py-3 rounded-xl font-bold text-base mt-1">
                        دریافت کد تأیید
                    </button>
                </form>

                {{-- Mobile-only: collapse toggles inside login card --}}
                <div class="lg:hidden mt-6 space-y-3 border-t border-white/10 pt-5">
                    <p class="text-white/50 text-xs text-center mb-3">اطلاعات بیشتر</p>

                    {{-- contractors mobile --}}
                    <button type="button"
                            class="collapse-btn rounded-xl px-4 py-2.5 flex items-center gap-3 w-full"
                            onclick="toggleCollapse('contractors-panel-mobile', this)">
                        <span>👷</span>
                        <span class="text-sm font-semibold flex-1">پیمانکاران معتبر</span>
                        @if($contractors->count())
                            <span class="text-xs bg-amber-500/30 text-amber-300 rounded-full px-2 py-0.5 font-bold">
                                {{ $contractors->count() }}
                            </span>
                        @endif
                        <svg class="chevron h-4 w-4 text-white/60" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div id="contractors-panel-mobile" class="collapse-panel">
                        <div class="collapse-box space-y-1.5 mt-2">
                            @forelse($contractors as $c)
                                <div class="list-item">
                                    <div class="list-avatar">{{ mb_substr($c->company_name, 0, 1) }}</div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-white text-xs font-semibold truncate">{{ $c->company_name }}</p>
                                        <p class="text-white/50 text-xs truncate">{{ $c->province }}{{ $c->city ? ' / '.$c->city : '' }}</p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-white/40 text-xs text-center py-3">پیمانکاری ثبت نشده است.</p>
                            @endforelse
                        </div>
                    </div>

                    {{-- packages mobile --}}
                    <button type="button"
                            class="collapse-btn rounded-xl px-4 py-2.5 flex items-center gap-3 w-full"
                            onclick="toggleCollapse('packages-panel-mobile', this)">
                        <span>📦</span>
                        <span class="text-sm font-semibold flex-1">پکیج‌های اختصاصی</span>
                        @if($packages->count())
                            <span class="text-xs bg-amber-500/30 text-amber-300 rounded-full px-2 py-0.5 font-bold">
                                {{ $packages->count() }}
                            </span>
                        </span>
                        @endif
                        <svg class="chevron h-4 w-4 text-white/60" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div id="packages-panel-mobile" class="collapse-panel">
                        <div class="collapse-box space-y-1.5 mt-2">
                            @forelse($packages as $p)
                                <div class="list-item">
                                    <div class="list-avatar" style="background:linear-gradient(135deg,#10b981,#059669);">☀</div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-white text-xs font-semibold truncate">{{ $p->title }}</p>
                                    </div>
                                    <span class="pkg-badge">{{ $p->capacity_kw }} kW</span>
                                </div>
                            @empty
                                <p class="text-white/40 text-xs text-center py-3">پکیجی ثبت نشده است.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <p class="text-center text-white/40 text-xs mt-6 leading-6">
                    با ورود، قوانین و مقررات سامانه را می‌پذیرید.
                </p>
            </div>

        </div>
    </main>

    {{-- ─── Footer ──────────────────────────────────────────── --}}
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

<script>
    function toggleCollapse(panelId, btn) {
        const panel = document.getElementById(panelId);
        const isOpen = panel.classList.contains('open');

        panel.classList.toggle('open', !isOpen);
        btn.classList.toggle('open', !isOpen);
    }
</script>

</body>
</html>
