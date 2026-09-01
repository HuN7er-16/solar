<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>سامانه جامع انرژی‌های تجدیدپذیر و خورشیدی اصناف (ساتا اصناف)</title>
    <script src="{{ url('behin/behin-dist/dist/js/tailwind-3.4.17.min.js') }}"></script>
    <link href="{{ url('behin/behin-dist/css/css2.css') }}?family=Vazirmatn:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        html, body { font-family: 'Vazirmatn', sans-serif; }
    </style>
</head>

<body class="bg-gray-50 text-gray-800 min-h-screen flex flex-col">

    <!-- Header inspired by request apply page -->
    <header class="bg-gradient-to-l from-amber-400 via-yellow-300 to-lime-300 text-gray-900">
        <div class="container mx-auto px-6 py-8 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="text-center md:text-right">
                <h1 class="text-2xl md:text-3xl font-bold">سامانه جامع انرژی‌های تجدیدپذیر و خورشیدی اصناف (ساتا اصناف)</h1>
            </div>
            @php
                $logoPath = public_path('behin/images/logo-union.png');
                $logoUrl = asset('behin/images/logo-union.png');
            @endphp
            @if(file_exists($logoPath))
                <img src="{{ $logoUrl }}" alt="لوگو اتحادیه کشوری سوخت‌های جایگزین" class="h-20 w-auto object-contain">
            @endif
        </div>
    </header>

    <!-- Center Login Box -->
    <main class="flex-grow flex items-center justify-center px-4 py-12">
        <div class="bg-white w-full max-w-md p-8 rounded-2xl shadow-xl text-gray-800">

            <h1 class="text-xl md:text-2xl font-extrabold text-center mb-2 text-amber-600">ورود به سامانه</h1>
            <h2 class="text-center text-sm text-gray-600 mb-8 tracking-wide">برای ادامه شماره موبایل خود را وارد کنید.</h2>

            <form method="POST" action="{{ route('otp.send') }}" class="flex flex-col gap-4">
                @csrf
                <label for="phone" class="text-sm font-semibold text-gray-700">شماره موبایل</label>
                <input type="text" name="phone" id="phone" dir="ltr" inputmode="numeric"
                    placeholder="مثال: 09123456789"
                    class="w-full border rounded-lg px-4 py-3 text-center tracking-wide focus:outline-none focus:ring-2 focus:ring-amber-500" required>

                <button
                    class="bg-gray-900 text-white py-3 rounded-lg font-bold hover:bg-gray-700 transition duration-200">
                    دریافت کد تأیید
                </button>
            </form>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white text-gray-800 py-10 mt-10 border-t-4 border-amber-400 shadow-[0_-10px_30px_rgba(245,158,11,0.08)]">
        <div class="h-1 w-full bg-gradient-to-l from-amber-400 via-yellow-300 to-lime-300 mb-8"></div>
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

                <div>
                    <h3 class="font-bold text-lg mb-2 text-amber-600">سامانه جامع انرژی‌های تجدیدپذیر و خورشیدی اصناف (ساتا اصناف)</h3>
                    <p class="text-sm text-gray-600 leading-6">سامانه رسمی اتحادیه کشوری سوخت‌های جایگزین و انرژی‌های تجدیدپذیر.</p>
                </div>

                <div>
                    <h4 class="font-semibold mb-2 text-amber-600">تماس</h4>
                    <p class="text-sm text-gray-600 leading-6">تلفن: 02191013791</p>
                </div>

                <div class="flex justify-center md:justify-end items-center">
                    @php
                        $logoPath = public_path('behin/images/logo-union.png');
                        $logoUrl = asset('behin/images/logo-union.png');
                    @endphp
                    @if(file_exists($logoPath))
                        <img src="{{ $logoUrl }}" alt="لوگو اتحادیه کشوری سوخت‌های جایگزین" class="h-20 w-auto object-contain">
                    @endif
                </div>
            </div>

            <div class="text-center text-sm text-gray-500 mt-8 pt-6 border-t border-amber-100">© تمامی حقوق متعلق به اتحادیه کشوری سوخت‌های جایگزین و انرژی‌های تجدیدپذیر است.</div>
        </div>
    </footer>

</body>
</html>
