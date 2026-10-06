<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" href="{{ url('behin/logo.ico') . '?' . config('app.version') }}">
    <title>انتخاب پیمانکار و پکیج - نیروگاه خورشیدی</title>
    <script src="{{ url('behin/behin-dist/dist/js/tailwind-3.4.17.min.js') }}"></script>
    <link href="{{ url('behin/behin-dist/css/css2.css') }}?family=Vazirmatn:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        html, body { font-family: 'Vazirmatn', sans-serif; }
        .container { max-width: 900px; margin-inline: auto; }

        /* کارت انتخابی */
        .selectable-card {
            cursor: pointer;
            border: 2px solid #E5E7EB;
            border-radius: 1rem;
            transition: border-color 0.15s, box-shadow 0.15s, background-color 0.15s;
        }
        .selectable-card:hover {
            border-color: #F59E0B;
            box-shadow: 0 4px 14px rgba(245,158,11,0.15);
        }
        .selectable-card.selected {
            border-color: #F59E0B;
            background-color: #FFFBEB;
            box-shadow: 0 4px 20px rgba(245,158,11,0.25);
        }

        /* radio مخفی */
        .selectable-card input[type="radio"] { display: none; }

        /* نشانگر انتخاب */
        .check-badge {
            width: 1.5rem; height: 1.5rem;
            border-radius: 50%;
            border: 2px solid #D1D5DB;
            display: flex; align-items: center; justify-content: center;
            transition: background-color 0.15s, border-color 0.15s;
            flex-shrink: 0;
        }
        .selectable-card.selected .check-badge {
            background-color: #F59E0B;
            border-color: #F59E0B;
        }
        .check-badge svg { display: none; }
        .selectable-card.selected .check-badge svg { display: block; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 min-h-screen">

    {{-- Header --}}
    <header class="bg-gradient-to-l from-amber-400 via-yellow-300 to-lime-300 text-gray-900">
        <div class="container px-6 py-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold">انتخاب پیمانکار و پکیج</h1>
                <p class="mt-1 text-sm">
                    درخواست:
                    <span class="font-mono font-semibold" dir="ltr">{{ $req->unique_code }}</span>
                </p>
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
        <div class="mb-6 rounded-lg bg-red-50 border border-red-300 text-red-800 px-4 py-3 text-sm">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- اطلاعات درخواست --}}
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
            <div>
                <p class="text-gray-400 mb-0.5">وضعیت</p>
                <span class="inline-block bg-purple-100 text-purple-700 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                    {{ $req->status_label }}
                </span>
            </div>
        </div>

        <form method="POST"
              action="{{ route('solar-plant-requests.select-contractor-package.store', $req) }}"
              id="selectionForm">
            @csrf

            {{-- ────────────── انتخاب پیمانکار ────────────── --}}
            <section class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center text-xl">👷</div>
                    <div>
                        <h2 class="font-bold text-lg">انتخاب پیمانکار</h2>
                        <p class="text-sm text-gray-500">یکی از پیمانکاران دارای مجوز را انتخاب کنید</p>
                    </div>
                </div>

                @if ($contractors->isEmpty())
                    <div class="text-center py-8 text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 mb-3 opacity-40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/>
                        </svg>
                        <p>پیمانکاری در سیستم ثبت نشده است.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3" id="contractorList">
                        @foreach ($contractors as $contractor)
                        <label class="selectable-card p-4 flex items-center gap-3"
                               for="contractor_{{ $contractor->id }}"
                               onclick="selectCard(this, 'contractor')">
                            <input type="radio"
                                   name="contractor_id"
                                   id="contractor_{{ $contractor->id }}"
                                   value="{{ $contractor->id }}"
                                   {{ old('contractor_id') == $contractor->id ? 'checked' : '' }}>
                            <div class="check-badge">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-sm truncate">{{ $contractor->name }}</p>
                                @if (!empty($contractor->phone))
                                    <p class="text-xs text-gray-400 mt-0.5" dir="ltr">{{ $contractor->phone }}</p>
                                @endif
                            </div>
                            <div class="w-9 h-9 rounded-full bg-amber-100 flex items-center justify-center text-amber-600 font-bold text-sm shrink-0">
                                {{ mb_substr($contractor->name, 0, 1) }}
                            </div>
                        </label>
                        @endforeach
                    </div>
                @endif

                @error('contractor_id')
                    <p class="mt-3 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </section>

            {{-- ────────────── انتخاب پکیج ────────────── --}}
            <section class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 rounded-xl bg-green-100 flex items-center justify-center text-xl">📦</div>
                    <div>
                        <h2 class="font-bold text-lg">انتخاب پکیج</h2>
                        <p class="text-sm text-gray-500">پکیج مناسب با ظرفیت و نوع تجهیزات مورد نظر را انتخاب کنید</p>
                    </div>
                </div>

                @if ($packages->isEmpty())
                    <div class="text-center py-8 text-gray-400">
                        پکیجی تعریف نشده است.
                    </div>
                @else
                    @foreach ($packages as $capacityKw => $capacityPackages)
                    <div class="mb-6">
                        {{-- عنوان گروه ظرفیت --}}
                        <div class="flex items-center gap-2 mb-3">
                            <span class="inline-flex items-center gap-1.5 bg-amber-50 border border-amber-200 text-amber-800 text-sm font-bold px-3 py-1 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                {{ $capacityKw }} کیلووات
                            </span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            @foreach ($capacityPackages as $package)
                            <label class="selectable-card p-4 flex flex-col gap-3"
                                   for="package_{{ $package->id }}"
                                   onclick="selectCard(this, 'package')">
                                <input type="radio"
                                       name="package_id"
                                       id="package_{{ $package->id }}"
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
                                <p class="text-xs text-gray-500 leading-relaxed">{{ $package->description }}</p>
                                @endif

                                <div class="mt-auto pt-3 border-t border-gray-100 flex items-center justify-between">
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
            </section>

            {{-- دکمه ثبت --}}
            <div class="flex items-center justify-between flex-wrap gap-4">
                <a href="{{ route('solar-plant-requests.index') }}"
                   class="inline-flex items-center gap-2 border border-gray-300 text-gray-700 px-5 py-2.5 rounded-lg text-sm font-semibold hover:bg-gray-50 transition">
                    انصراف
                </a>
                <button type="submit"
                        id="submitBtn"
                        class="inline-flex items-center gap-2 bg-amber-500 text-white px-8 py-2.5 rounded-lg text-sm font-bold hover:bg-amber-600 transition disabled:opacity-50 disabled:cursor-not-allowed"
                        disabled>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    ثبت انتخاب و ادامه
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
        /**
         * انتخاب کارت و به‌روزرسانی وضعیت دکمه ثبت
         * @param {HTMLElement} card - عنصر label کلیک‌شده
         * @param {string} group - 'contractor' یا 'package'
         */
        function selectCard(card, group) {
            // برداشتن کلاس selected از سایر کارت‌های همگروه
            const radio = card.querySelector('input[type="radio"]');
            const allCards = document.querySelectorAll(
                group === 'contractor' ? '#contractorList .selectable-card' : '.selectable-card[for^="package_"]'
            );
            // روش ساده‌تر: روی همه label هایی که radio همنام دارند
            document.querySelectorAll(`input[name="${radio.name}"]`).forEach(r => {
                r.closest('label')?.classList.remove('selected');
            });

            card.classList.add('selected');
            radio.checked = true;
            checkSubmitReady();
        }

        function checkSubmitReady() {
            const contractorSelected = document.querySelector('input[name="contractor_id"]:checked');
            const packageSelected    = document.querySelector('input[name="package_id"]:checked');
            document.getElementById('submitBtn').disabled = !(contractorSelected && packageSelected);
        }

        // اعمال وضعیت اولیه در صورت وجود old() (بعد از validation error)
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('input[type="radio"]:checked').forEach(radio => {
                radio.closest('label')?.classList.add('selected');
            });
            checkSubmitReady();
        });
    </script>

</body>
</html>
