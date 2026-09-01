<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>تکمیل پروفایل - سامانه ساتا اصناف</title>
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

            <div class="flex justify-center mb-5">
                <div class="w-16 h-16 rounded-full flex items-center justify-center"
                     style="background:linear-gradient(135deg,#FFB74D,#FF9800);">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
            </div>

            <h1 class="text-xl font-extrabold text-center mb-1 text-amber-600">تکمیل پروفایل</h1>
            <p class="text-center text-sm text-gray-500 mb-7">لطفاً نام و نام خانوادگی خود را وارد کنید</p>

            @if($errors->any())
                <div class="rounded-lg bg-red-50 border border-red-200 text-red-700 px-4 py-3 text-sm mb-4">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('otp.setup-name.store') }}" class="flex flex-col gap-4">
                @csrf

                <label for="name" class="text-sm font-semibold text-gray-700">
                    نام و نام خانوادگی <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" id="name"
                       value="{{ old('name') }}"
                       placeholder="مثال: علی محمدی"
                       autofocus
                       class="w-full border border-gray-300 rounded-lg px-4 py-3 text-center
                              focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent
                              text-base"
                       required>

                <button type="submit"
                        class="w-full bg-gray-900 text-white py-3 rounded-lg font-bold
                               hover:bg-gray-700 transition duration-200 mt-2">
                    ذخیره و ورود به سامانه
                </button>
            </form>

        </div>
    </main>

    {{-- Footer --}}
    <footer class="bg-white text-gray-800 py-10 mt-10 border-t-4 border-amber-400">
        <div class="h-1 w-full bg-gradient-to-l from-amber-400 via-yellow-300 to-lime-300 mb-8"></div>
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="font-bold text-lg mb-2 text-amber-600">سامانه ساتا اصناف</h3>
                    <p class="text-sm text-gray-600 leading-6">سامانه رسمی اتحادیه کشوری سوخت‌های جایگزین و انرژی‌های تجدیدپذیر.</p>
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
                © تمامی حقوق متعلق به اتحادیه کشوری سوخت‌های جایگزین و انرژی‌های تجدیدپذیر است.
            </div>
        </div>
    </footer>

</body>
</html>
