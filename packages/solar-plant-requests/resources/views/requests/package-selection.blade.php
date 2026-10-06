<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" href="{{ url('behin/logo.ico') . '?' . config('app.version') }}">
    <title>انتخاب پکیج - نیروگاه خورشیدی</title>
    <script src="{{ url('behin/behin-dist/dist/js/tailwind-3.4.17.min.js') }}"></script>
    <link href="{{ url('behin/behin-dist/css/css2.css') }}?family=Vazirmatn:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        html, body { font-family: 'Vazirmatn', sans-serif; }
        .container { max-width: 900px; margin-inline: auto; }

        .pkg-card {
            cursor: pointer;
            border: 2px solid #E5E7EB;
            border-radius: 1rem;
            transition: border-color 0.15s, box-shadow 0.15s, background-color 0.15s;
        }
        .pkg-card:hover { border-color: #F59E0B; box-shadow: 0 4px 14px rgba(245,158,11,0.15); }
        .pkg-card.selected { border-color: #F59E0B; background-color: #FFFBEB; box-shadow: 0 4px 20px rgba(245,158,11,0.25); }
        .pkg-card input[type="radio"] { display: none; }

        .check-badge {
            width: 1.5rem; height: 1.5rem; border-radius: 50%;
            border: 2px solid #D1D5DB;
            display: flex; align-items: center; justify-content: center;
            transition: background-color 0.15s, border-color 0.15s;
            flex-shrink: 0;
        }
        .pkg-card.selected .check-badge { background-color: #F59E0B; border-color: #F59E0B; }
        .check-badge svg { display: none; }
        .pkg-card.selected .check-badge svg { display: block; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 min-h-screen">

{{-- Header --}}
<header class="bg-gradient-to-l from-amber-400 via-yellow-300 to-lime-300 text-gray-900">
    <div class="container px-6 py-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold">انتخاب پکیج</h1>
            <p class="mt-1 text-sm font-mono" dir="ltr">{{ $req->unique_code }}</p>
        </div>
        <a href="{{ route('solar-plant-requests.index') }}"
           class="flex items-center gap-2 bg-gray-900 text-white px-5 py-2.5 rounded-lg font-semibold text-sm hover:bg-gray-700 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            بازگشت
        </a>
    </div>
</header>

<main class="container px-6 py-8">

    @if ($errors->any())
    <div class="mb-6 rounded-xl bg-red-50 border border-red-200 text-red-800 px-4 py-3 text-sm">
        <ul class="list-disc list-inside space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- اطلاعات تقاضا --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-6 flex flex-wrap gap-6 text-sm">
        <div>
            <p class="text-gray-400 mb-0.5">متقاضی</p>
            <p class="font-semibold">
                @if ($req->applicant_type->value === 'company')
                    {{ $req->company_name }}
                @else
                    {{ $req->first_name }} {{ $req->last_name }}
                @endif
            </p>
        </div>
        <div>
            <p class="text-gray-400 mb-0.5">ظرفیت درخواستی</p>
            <p class="font-semibold">{{ $req->capacity_kw }} کیلووات</p>
        </div>
        <div>
            <p class="text-gray-400 mb-0.5">استان / شهر</p>
            <p class="font-semibold">{{ $req->province }} / {{ $req->city }}</p>
        </div>
    </div>

    {{-- راهنما --}}
    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-6 flex gap-3 text-sm text-amber-800">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p>یکی از پکیج‌های زیر را با توجه به ظرفیت و نوع تجهیزات مورد نظر انتخاب کنید. پس از انتخاب پکیج، می‌توانید پیمانکار را انتخاب کنید.</p>
    </div>

    <form method="POST"
          action="{{ route('solar-plant-requests.package-selection.store', $req) }}"
          id="packageForm">
        @csrf

        @if ($packages->isEmpty())
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 px-8 py-12 text-center text-gray-400">
            پکیجی تعریف نشده است.
        </div>
        @else

        @foreach ($packages as $capacityKw => $capacityPackages)
        <div class="mb-8">
            {{-- عنوان گروه --}}
            <div class="flex items-center gap-2 mb-4">
                <span class="inline-flex items-center gap-1.5 bg-amber-100 border border-amber-300 text-amber-800 text-sm font-bold px-3 py-1.5 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    {{ $capacityKw }} کیلووات
                </span>
                @if ($req->capacity_kw == $capacityKw)
                <span class="text-xs text-green-700 bg-green-100 px-2 py-0.5 rounded-full font-medium">ظرفیت درخواستی شما</span>
                @endif
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach ($capacityPackages as $package)
                <label class="pkg-card p-5 flex flex-col gap-3 {{ old('package_id') == $package->id ? 'selected' : '' }}"
                       for="pkg_{{ $package->id }}"
                       onclick="selectPackage(this)">
                    <input type="radio"
                           name="package_id"
                           id="pkg_{{ $package->id }}"
                           value="{{ $package->id }}"
                           {{ old('package_id') == $package->id ? 'checked' : '' }}>

                    <div class="flex items-start justify-between gap-2">
                        <p class="font-bold text-sm leading-snug">{{ $package->title }}</p>
                        <div class="check-badge shrink-0 mt-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                    </div>

                    @if ($package->description)
                    <p class="text-xs text-gray-500 leading-relaxed flex-1">{{ $package->description }}</p>
                    @endif

                    <div class="pt-3 border-t border-gray-100 flex items-center justify-between">
                        <span class="text-xs text-gray-400">قیمت</span>
                        <span class="font-bold text-sm text-amber-700" dir="ltr">
                            {{ number_format($package->price) }}
                            <span class="font-normal text-xs text-gray-500">ریال</span>
                        </span>
                    </div>
                </label>
                @endforeach
            </div>
        </div>
        @endforeach

        @endif

        @error('package_id')
        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror

        {{-- دکمه ثبت --}}
        <div class="flex items-center justify-between flex-wrap gap-4 mt-4">
            <a href="{{ route('solar-plant-requests.index') }}"
               class="inline-flex items-center gap-2 border border-gray-300 text-gray-700 px-5 py-2.5 rounded-lg text-sm font-semibold hover:bg-gray-50 transition">
                انصراف
            </a>
            <button type="submit"
                    id="submitBtn"
                    disabled
                    class="inline-flex items-center gap-2 bg-amber-500 text-white px-8 py-2.5 rounded-lg text-sm font-bold hover:bg-amber-600 transition disabled:opacity-50 disabled:cursor-not-allowed">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                ثبت پکیج و ادامه
            </button>
        </div>

    </form>
</main>

<footer class="bg-gray-900 text-gray-100 mt-12">
    <div class="container px-6 py-6 flex flex-col md:flex-row items-center justify-between gap-4 text-sm">
        <div>اتحادیه کشوری سوخت‌های جایگزین و انرژی‌های تجدیدپذیر</div>
        <div><span>ایمیل: info@altfuel.ir</span></div>
    </div>
</footer>

<script>
    function selectPackage(card) {
        document.querySelectorAll('.pkg-card').forEach(c => c.classList.remove('selected'));
        card.classList.add('selected');
        card.querySelector('input[type="radio"]').checked = true;
        document.getElementById('submitBtn').disabled = false;
    }

    document.addEventListener('DOMContentLoaded', function () {
        // در صورت وجود old() بعد از خطا
        const checked = document.querySelector('input[type="radio"]:checked');
        if (checked) {
            checked.closest('label')?.classList.add('selected');
            document.getElementById('submitBtn').disabled = false;
        }
    });
</script>
</body>
</html>
