<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>تأیید کد یکبار مصرف - سامانه ساتا اصناف</title>
    <script src="{{ url('behin/behin-dist/dist/js/tailwind-3.4.17.min.js') }}"></script>
    <link href="{{ url('behin/behin-dist/css/css2.css') }}?family=Vazirmatn:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        html, body { font-family: 'Vazirmatn', sans-serif; }
    </style>
</head>

<body class="bg-gray-50 text-gray-800 min-h-screen flex flex-col">

    {{-- Header --}}
    <header class="bg-gradient-to-l from-amber-400 via-yellow-300 to-lime-300 text-gray-900">
        <div class="container mx-auto px-6 py-8 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="text-center md:text-right">
                <h1 class="text-2xl md:text-3xl font-bold">سامانه جامع انرژی‌های تجدیدپذیر و خورشیدی اصناف (ساتا اصناف)</h1>
            </div>
            @php
                $logoPath = public_path('behin/images/logo-union.png');
                $logoUrl  = asset('behin/images/logo-union.png');
            @endphp
            @if(file_exists($logoPath))
                <img src="{{ $logoUrl }}" alt="لوگو اتحادیه" class="h-20 w-auto object-contain">
            @endif
        </div>
    </header>

    {{-- Main --}}
    <main class="flex-grow flex items-center justify-center px-4 py-12">
        <div class="bg-white w-full max-w-md p-8 rounded-2xl shadow-xl text-gray-800">

            <h1 class="text-xl md:text-2xl font-extrabold text-center mb-1 text-amber-600">تأیید کد یکبار مصرف</h1>
            <p class="text-center text-sm text-gray-500 mb-7">
                کد ارسال‌شده به
                <span class="font-bold text-gray-700" dir="ltr">{{ $phone }}</span>
                را وارد کنید
            </p>

            {{-- پیام‌ها --}}
            @if(session('success'))
                <div class="rounded-lg bg-green-50 border border-green-200 text-green-800 px-4 py-3 text-sm mb-4">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="rounded-lg bg-red-50 border border-red-200 text-red-700 px-4 py-3 text-sm mb-4">
                    {{ session('error') }}
                </div>
            @endif
            @isset($error)
                <div class="rounded-lg bg-red-50 border border-red-200 text-red-700 px-4 py-3 text-sm mb-4">
                    {{ $error }}
                </div>
            @endisset

            {{-- فرم کد OTP --}}
            <form method="POST" action="{{ route('otp.verify') }}" class="flex flex-col gap-4">
                @csrf
                <input type="hidden" name="phone" value="{{ $phone }}">

                <label for="otp" class="text-sm font-semibold text-gray-700">کد تأیید</label>
                <input type="text" name="otp" id="otp"
                       placeholder="کد ۶ رقمی را وارد کنید"
                       required autofocus inputmode="numeric"
                       class="w-full border border-gray-300 rounded-lg px-4 py-3 text-center
                              tracking-[0.4em] text-lg font-bold
                              focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent">

                <button type="submit"
                        class="w-full bg-gray-900 text-white py-3 rounded-lg font-bold
                               hover:bg-gray-700 transition duration-200">
                    تأیید و ورود
                </button>
            </form>

            {{-- ارسال مجدد --}}
            <form method="POST" action="{{ route('otp.send') }}" class="mt-3">
                @csrf
                <input type="hidden" name="phone" value="{{ $phone }}">
                <button id="resendBtn" type="submit" disabled
                        class="w-full border border-gray-300 text-gray-500 py-3 rounded-lg
                               font-semibold disabled:opacity-50 disabled:cursor-not-allowed
                               hover:enabled:bg-gray-50 transition duration-200">
                    ارسال مجدد کد
                    <span id="resendCounter" class="text-amber-600 font-bold">(60)</span>
                </button>
            </form>

            {{-- بازگشت به لاگین --}}
            <div class="text-center mt-5">
                <a href="{{ route('login') }}"
                   class="text-sm text-gray-500 hover:text-amber-600 transition duration-200">
                    ← بازگشت و تغییر شماره
                </a>
            </div>

        </div>
    </main>

    {{-- Footer --}}
    <footer class="bg-white text-gray-800 py-10 mt-10 border-t-4 border-amber-400">
        <div class="h-1 w-full bg-gradient-to-l from-amber-400 via-yellow-300 to-lime-300 mb-8"></div>
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="font-bold text-lg mb-2 text-amber-600">سامانه ساتا اصناف</h3>
                    <p class="text-sm text-gray-600 leading-6">سامانه رسمی اتحادیه کشوری سوخت‌های جایگزین و خدمات وابسته.</p>
                </div>
                <div>
                    <h4 class="font-semibold mb-2 text-amber-600">تماس</h4>
                    <p class="text-sm text-gray-600 leading-6">تلفن: 02191013791</p>
                </div>
                <div class="flex justify-center md:justify-end items-center">
                    @if(file_exists($logoPath))
                        <img src="{{ $logoUrl }}" alt="لوگو اتحادیه" class="h-20 w-auto object-contain">
                    @endif
                </div>
            </div>
            <div class="text-center text-sm text-gray-500 mt-8 pt-6 border-t border-amber-100">
                © تمامی حقوق متعلق به اتحادیه کشوری سوخت‌های جایگزین و خدمات وابسته است.
            </div>
        </div>
    </footer>

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
                    btn.classList.remove('text-gray-500');
                    btn.classList.add('text-gray-800');
                }
            }, 1000);
        });
    </script>

</body>
</html>
