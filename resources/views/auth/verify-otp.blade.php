<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>تأیید کد یکبار مصرف - سامانه ساتا اصناف</title>
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
            background: rgba(255, 255, 255, 0.13);
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
        .glass-input::placeholder { color: rgba(255,255,255,0.45); }
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
        .btn-resend {
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.25);
            color: rgba(255,255,255,0.7);
            transition: all 0.2s ease;
        }
        .btn-resend:not(:disabled):hover {
            background: rgba(255,255,255,0.18);
            color: #fff;
        }
        .btn-resend:disabled { opacity: 0.5; cursor: not-allowed; }

        /* OTP digit boxes */
        .otp-input {
            background: rgba(255,255,255,0.15);
            border: 1.5px solid rgba(255,255,255,0.3);
            color: #fff;
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: 0.4em;
            text-align: center;
        }
        .otp-input:focus {
            outline: none;
            border-color: #f59e0b;
            background: rgba(255,255,255,0.22);
            box-shadow: 0 0 0 3px rgba(245,158,11,0.3);
        }

        /* Progress dots */
        .step-dot { background: rgba(255,255,255,0.25); }
        .step-dot.done { background: #22c55e; }
        .step-dot.active { background: #f59e0b; }
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
        </div>
    </header>

    {{-- Main --}}
    <main class="flex-grow flex items-center justify-center px-4 py-12">
        <div class="glass-card rounded-2xl p-8 w-full max-w-md">

            {{-- Step indicator --}}
            <div class="flex items-center justify-center gap-2 mb-7">
                <div class="step-dot done w-3 h-3 rounded-full"></div>
                <div class="h-px w-8 bg-green-500/60"></div>
                <div class="step-dot active w-3 h-3 rounded-full"></div>
                <div class="h-px w-8 bg-white/20"></div>
                <div class="step-dot w-3 h-3 rounded-full"></div>
            </div>

            {{-- Icon + title --}}
            <div class="flex flex-col items-center mb-7">
                <div class="sun-badge w-14 h-14 rounded-2xl flex items-center justify-center text-2xl mb-4">
                    📱
                </div>
                <h1 class="text-white text-xl font-extrabold">تأیید کد یکبار مصرف</h1>
                <p class="text-white/60 text-sm mt-2 text-center leading-6">
                    کد ارسال‌شده به
                    <span class="font-bold text-amber-300 font-mono" dir="ltr">{{ $phone }}</span>
                    را وارد کنید
                </p>
            </div>

            {{-- پیام‌ها --}}
            @if(session('success'))
                <div class="rounded-xl bg-green-500/20 border border-green-400/40 text-green-200 px-4 py-3 text-sm mb-5">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="rounded-xl bg-red-500/20 border border-red-400/40 text-red-200 px-4 py-3 text-sm mb-5">
                    {{ session('error') }}
                </div>
            @endif
            @isset($error)
                <div class="rounded-xl bg-red-500/20 border border-red-400/40 text-red-200 px-4 py-3 text-sm mb-5">
                    {{ $error }}
                </div>
            @endisset

            {{-- فرم کد OTP --}}
            <form method="POST" action="{{ route('otp.verify') }}" class="flex flex-col gap-4">
                @csrf
                <input type="hidden" name="phone" value="{{ $phone }}">

                <div>
                    <label for="otp" class="block text-sm font-semibold text-white/80 mb-2">کد تأیید ۶ رقمی</label>
                    <input type="text" name="otp" id="otp"
                           placeholder="— — — — — —"
                           required autofocus inputmode="numeric" maxlength="6"
                           class="otp-input w-full rounded-xl px-4 py-3">
                </div>

                <button type="submit" class="btn-submit w-full text-white py-3 rounded-xl font-bold text-base">
                    تأیید و ورود
                </button>
            </form>

            {{-- ارسال مجدد --}}
            <form method="POST" action="{{ route('otp.send') }}" class="mt-3">
                @csrf
                <input type="hidden" name="phone" value="{{ $phone }}">
                <button id="resendBtn" type="submit" disabled
                        class="btn-resend w-full py-3 rounded-xl font-semibold text-sm">
                    ارسال مجدد کد
                    <span id="resendCounter" class="text-amber-400 font-bold mr-1">(60)</span>
                </button>
            </form>

            {{-- بازگشت --}}
            <div class="text-center mt-5">
                <a href="{{ route('login') }}"
                   class="text-sm text-white/45 hover:text-amber-400 transition duration-200">
                    ← بازگشت و تغییر شماره
                </a>
            </div>

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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const btn     = document.getElementById('resendBtn');
        const counter = document.getElementById('resendCounter');
        let seconds   = 60;

        const timer = setInterval(function () {
            seconds--;
            counter.textContent = '(' + seconds + ')';
            if (seconds <= 0) {
                clearInterval(timer);
                counter.textContent = '';
                btn.disabled = false;
            }
        }, 1000);
    });
</script>

</body>
</html>
