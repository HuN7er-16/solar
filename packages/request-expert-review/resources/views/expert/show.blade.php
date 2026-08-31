@extends('behin-layouts.app')

@section('content')
<div class="container-fluid" style="direction: rtl; text-align: right;">

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert"
             style="border-radius:12px;border:none;background:linear-gradient(135deg,#C8E6C9,#A5D6A7);color:#1B5E20;">
            <i class="fa fa-check-circle ms-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" style="float:left;"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert"
             style="border-radius:12px;border:none;background:linear-gradient(135deg,#FFCDD2,#EF9A9A);color:#B71C1C;">
            <i class="fa fa-exclamation-circle ms-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" style="float:left;"></button>
        </div>
    @endif

    {{-- Header --}}
    <div class="mb-4 p-4 text-white"
         style="background:linear-gradient(135deg,#7986CB 0%,#5C6BC0 100%);border-radius:12px;box-shadow:0 4px 20px rgba(92,107,192,0.25);">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h3 class="mb-1 fw-bold"><i class="fa fa-file-alt ms-2"></i>بررسی تقاضا</h3>
                <p class="mb-0 opacity-90">کد پیگیری: <span style="font-family:monospace;font-size:15px;">{{ $solarPlantRequest->unique_code }}</span></p>
            </div>
            <a href="{{ route('request-expert-review.expert.index') }}"
               class="btn btn-light"
               style="border-radius:10px;color:#3949AB;font-weight:600;">
                <i class="fa fa-arrow-right ms-1"></i> بازگشت به لیست
            </a>
            {{-- دکمه ثبت بازدید اولیه --}}
            @php
                $existingVisit = null;
                if (class_exists(\ExpertInitialVisit\Models\ExpertInitialVisit::class)) {
                    try {
                        $existingVisit = \ExpertInitialVisit\Models\ExpertInitialVisit::query()
                            ->where('solar_plant_request_id', $solarPlantRequest->id)
                            ->first();
                    } catch (\Throwable $e) {
                        $existingVisit = null;
                    }
                }
            @endphp
            @if($existingVisit)
                <a href="{{ route('expert-initial-visit.show', $existingVisit) }}"
                   class="btn text-white"
                   style="background:linear-gradient(135deg,#4CAF50,#2E7D32);border-radius:10px;font-weight:600;">
                    <i class="fa fa-eye ms-1"></i> مشاهده گزارش بازدید
                </a>
            @elseif(class_exists(\ExpertInitialVisit\Models\ExpertInitialVisit::class))
                <a href="{{ route('expert-initial-visit.create', ['request_id' => $solarPlantRequest->id]) }}"
                   class="btn text-white"
                   style="background:linear-gradient(135deg,#5C6BC0,#3949AB);border-radius:10px;font-weight:600;">
                    <i class="fa fa-clipboard-check ms-1"></i> ثبت گزارش بازدید اولیه
                </a>
            @endif
        </div>
    </div>


    {{-- اطلاعات شخصی متقاضی (فقط نمایش) --}}
    <div class="card mb-4" style="border-radius:12px;border:none;box-shadow:0 4px 20px rgba(0,0,0,0.08);">
        <div class="card-header d-flex align-items-center gap-3"
             style="background:linear-gradient(135deg,#FFF3E0,#FFE0B2);border-radius:12px 12px 0 0;border:none;">
            <div style="width:40px;height:40px;background:linear-gradient(135deg,#FFB74D,#FF9800);border-radius:10px;display:flex;align-items:center;justify-content:center;">
                <i class="fa fa-user text-white"></i>
            </div>
            <div>
                <h5 class="mb-0 fw-bold" style="color:#E65100;">اطلاعات متقاضی</h5>
                <small class="text-muted">این اطلاعات فقط قابل مشاهده هستند و قابل ویرایش نیستند</small>
            </div>
        </div>
        <div class="card-body p-4">
            @php
                $applicantLabels = ['individual'=>'شخص حقیقی','company'=>'شخص حقوقی','foreigner'=>'اتباع خارجی'];
            @endphp
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label text-muted small mb-1">نوع متقاضی</label>
                    <div class="fw-semibold">{{ $applicantLabels[$solarPlantRequest->applicant_type?->value] ?? '-' }}</div>
                </div>
                @if ($solarPlantRequest->applicant_type?->value === 'company')
                    <div class="col-md-3">
                        <label class="form-label text-muted small mb-1">نام شرکت</label>
                        <div class="fw-semibold">{{ $solarPlantRequest->company_name ?? '-' }}</div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-muted small mb-1">شماره ثبت</label>
                        <div class="fw-semibold" dir="ltr">{{ $solarPlantRequest->registration_number ?? '-' }}</div>
                    </div>
                @else
                    <div class="col-md-3">
                        <label class="form-label text-muted small mb-1">نام و نام خانوادگی</label>
                        <div class="fw-semibold">{{ $solarPlantRequest->first_name }} {{ $solarPlantRequest->last_name }}</div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-muted small mb-1">کد ملی</label>
                        <div class="fw-semibold" dir="ltr">{{ $solarPlantRequest->national_code ?? '-' }}</div>
                    </div>
                @endif
                <div class="col-md-3">
                    <label class="form-label text-muted small mb-1">شماره موبایل</label>
                    <div class="fw-semibold" dir="ltr">{{ $solarPlantRequest->mobile }}</div>
                </div>
                @if ($solarPlantRequest->landline)
                    <div class="col-md-3">
                        <label class="form-label text-muted small mb-1">تلفن ثابت</label>
                        <div class="fw-semibold" dir="ltr">{{ $solarPlantRequest->landline }}</div>
                    </div>
                @endif
                <div class="col-md-3">
                    <label class="form-label text-muted small mb-1">استان</label>
                    <div class="fw-semibold">{{ $solarPlantRequest->province }}</div>
                </div>
                <div class="col-md-3">
                    <label class="form-label text-muted small mb-1">شهر</label>
                    <div class="fw-semibold">{{ $solarPlantRequest->city }}</div>
                </div>
                <div class="col-md-3">
                    <label class="form-label text-muted small mb-1">کد پستی</label>
                    <div class="fw-semibold" dir="ltr">{{ $solarPlantRequest->postal_code }}</div>
                </div>
                <div class="col-12">
                    <label class="form-label text-muted small mb-1">آدرس</label>
                    <div class="fw-semibold">{{ $solarPlantRequest->address }}</div>
                </div>
            </div>
        </div>
    </div>


    {{-- فرم ویرایش فیلدهای فنی --}}
    <form method="POST" action="{{ route('request-expert-review.expert.update', $solarPlantRequest) }}">
        @csrf
        @method('PUT')

        <div class="card mb-4" style="border-radius:12px;border:none;box-shadow:0 4px 20px rgba(0,0,0,0.08);">
            <div class="card-header d-flex align-items-center gap-3"
                 style="background:linear-gradient(135deg,#E8EAF6,#C5CAE9);border-radius:12px 12px 0 0;border:none;">
                <div style="width:40px;height:40px;background:linear-gradient(135deg,#7986CB,#5C6BC0);border-radius:10px;display:flex;align-items:center;justify-content:center;">
                    <i class="fa fa-tools text-white"></i>
                </div>
                <div>
                    <h5 class="mb-0 fw-bold" style="color:#283593;">مشخصات فنی تقاضا</h5>
                    <small class="text-muted">این فیلدها توسط کارشناس قابل بررسی و ویرایش هستند</small>
                </div>
            </div>
            <div class="card-body p-4">
                @php
                    $usageOptions    = ['villa'=>'ویلایی','industrial'=>'صنعتی','commercial'=>'تجاری','agriculture'=>'کشاورزی','apartment'=>'آپارتمان'];
                    $surfaceOptions  = ['flat'=>'تخت','sloped'=>'شیبدار','ground'=>'زمین','other'=>'سایر'];
                    $purposeOptions  = ['off_grid'=>'مصرف شخصی (Off-grid)','on_grid'=>'فروش به شبکه (On-grid)','hybrid'=>'هیبرید (Hybrid)'];
                @endphp
                <div class="row g-4">

                    <div class="col-md-3">
                        <label class="form-label fw-semibold mb-2" style="color:#37474F;">شناسه قبض برق</label>
                        <input type="text" name="bill_identifier"
                               class="form-control @error('bill_identifier') is-invalid @enderror"
                               value="{{ old('bill_identifier', $solarPlantRequest->bill_identifier) }}"
                               style="border-radius:8px;border:2px solid #E0E0E0;padding:10px 14px;">
                        @error('bill_identifier') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold mb-2" style="color:#37474F;">متراژ (متر مربع)</label>
                        <input type="number" name="area" min="0"
                               class="form-control @error('area') is-invalid @enderror"
                               value="{{ old('area', $solarPlantRequest->area) }}"
                               style="border-radius:8px;border:2px solid #E0E0E0;padding:10px 14px;">
                        @error('area') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold mb-2" style="color:#37474F;">مساحت محل نصب (متر مربع)</label>
                        <input type="number" name="installation_area" min="0"
                               class="form-control @error('installation_area') is-invalid @enderror"
                               value="{{ old('installation_area', $solarPlantRequest->installation_area) }}"
                               style="border-radius:8px;border:2px solid #E0E0E0;padding:10px 14px;">
                        @error('installation_area') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold mb-2" style="color:#37474F;">ظرفیت (کیلو وات)</label>
                        <input type="number" name="capacity_kw" min="1"
                               class="form-control @error('capacity_kw') is-invalid @enderror"
                               value="{{ old('capacity_kw', $solarPlantRequest->capacity_kw) }}"
                               style="border-radius:8px;border:2px solid #E0E0E0;padding:10px 14px;">
                        @error('capacity_kw') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold mb-2" style="color:#37474F;">نوع کاربری</label>
                        <select name="usage_type" class="form-control @error('usage_type') is-invalid @enderror"
                                style="border-radius:8px;border:2px solid #E0E0E0;padding:10px 14px;">
                            <option value="">-- انتخاب کنید --</option>
                            @foreach ($usageOptions as $val => $label)
                                <option value="{{ $val }}"
                                    {{ old('usage_type', $solarPlantRequest->usage_type?->value) == $val ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('usage_type') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold mb-2" style="color:#37474F;">نوع سطح نصب</label>
                        <select name="surface_type" class="form-control @error('surface_type') is-invalid @enderror"
                                style="border-radius:8px;border:2px solid #E0E0E0;padding:10px 14px;">
                            <option value="">-- انتخاب کنید --</option>
                            @foreach ($surfaceOptions as $val => $label)
                                <option value="{{ $val }}"
                                    {{ old('surface_type', $solarPlantRequest->surface_type?->value) == $val ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('surface_type') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold mb-2" style="color:#37474F;">هدف</label>
                        <select name="purpose" class="form-control @error('purpose') is-invalid @enderror"
                                style="border-radius:8px;border:2px solid #E0E0E0;padding:10px 14px;">
                            <option value="">-- انتخاب کنید --</option>
                            @foreach ($purposeOptions as $val => $label)
                                <option value="{{ $val }}"
                                    {{ old('purpose', $solarPlantRequest->purpose?->value) == $val ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('purpose') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold mb-2" style="color:#37474F;">ملک مشاع</label>
                        <div class="d-flex gap-4 p-3" style="background:#F5F5F5;border-radius:8px;">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="is_shared_property"
                                       id="shared_yes" value="1"
                                       {{ old('is_shared_property', $solarPlantRequest->is_shared_property ? '1' : '0') == '1' ? 'checked' : '' }}
                                       style="width:18px;height:18px;cursor:pointer;">
                                <label class="form-check-label fw-semibold me-2" for="shared_yes" style="color:#2E7D32;cursor:pointer;">بلی</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="is_shared_property"
                                       id="shared_no" value="0"
                                       {{ old('is_shared_property', $solarPlantRequest->is_shared_property ? '1' : '0') == '0' ? 'checked' : '' }}
                                       style="width:18px;height:18px;cursor:pointer;">
                                <label class="form-check-label fw-semibold me-2" for="shared_no" style="color:#C62828;cursor:pointer;">خیر</label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold mb-2" style="color:#37474F;">برق سه فاز</label>
                        <div class="d-flex gap-4 p-3" style="background:#F5F5F5;border-radius:8px;">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="has_three_phase"
                                       id="three_yes" value="1"
                                       {{ old('has_three_phase', $solarPlantRequest->has_three_phase ? '1' : '0') == '1' ? 'checked' : '' }}
                                       style="width:18px;height:18px;cursor:pointer;">
                                <label class="form-check-label fw-semibold me-2" for="three_yes" style="color:#2E7D32;cursor:pointer;">بلی</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="has_three_phase"
                                       id="three_no" value="0"
                                       {{ old('has_three_phase', $solarPlantRequest->has_three_phase ? '1' : '0') == '0' ? 'checked' : '' }}
                                       style="width:18px;height:18px;cursor:pointer;">
                                <label class="form-check-label fw-semibold me-2" for="three_no" style="color:#C62828;cursor:pointer;">خیر</label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold mb-2" style="color:#37474F;">تمایل به وام</label>
                        <div class="d-flex gap-4 p-3" style="background:#F5F5F5;border-radius:8px;">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="wants_loan"
                                       id="loan_yes" value="1"
                                       {{ old('wants_loan', $solarPlantRequest->wants_loan ? '1' : '0') == '1' ? 'checked' : '' }}
                                       style="width:18px;height:18px;cursor:pointer;">
                                <label class="form-check-label fw-semibold me-2" for="loan_yes" style="color:#2E7D32;cursor:pointer;">بلی</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="wants_loan"
                                       id="loan_no" value="0"
                                       {{ old('wants_loan', $solarPlantRequest->wants_loan ? '1' : '0') == '0' ? 'checked' : '' }}
                                       style="width:18px;height:18px;cursor:pointer;">
                                <label class="form-check-label fw-semibold me-2" for="loan_no" style="color:#C62828;cursor:pointer;">خیر</label>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold mb-2" style="color:#37474F;">توضیحات</label>
                        <textarea name="description" rows="4"
                                  class="form-control @error('description') is-invalid @enderror"
                                  style="border-radius:8px;border:2px solid #E0E0E0;padding:10px 14px;resize:vertical;">{{ old('description', $solarPlantRequest->description) }}</textarea>
                        @error('description') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                </div>

                {{-- دکمه ذخیره --}}
                <div class="mt-4 d-flex justify-content-end">
                    <button type="submit" class="btn btn-lg text-white"
                            style="background:linear-gradient(135deg,#7986CB,#5C6BC0);border-radius:10px;font-weight:700;padding:12px 36px;box-shadow:0 4px 15px rgba(92,107,192,0.3);">
                        <i class="fa fa-save ms-1"></i> ذخیره تغییرات
                    </button>
                </div>
            </div>
        </div>

    </form>


    {{-- مدارک و تصاویر --}}
    @if (!empty($solarPlantRequest->images) || !empty($solarPlantRequest->documents))
        <div class="card mb-4" style="border-radius:12px;border:none;box-shadow:0 4px 20px rgba(0,0,0,0.08);">
            <div class="card-header d-flex align-items-center gap-3"
                 style="background:linear-gradient(135deg,#E8F5E9,#C8E6C9);border-radius:12px 12px 0 0;border:none;">
                <div style="width:40px;height:40px;background:linear-gradient(135deg,#81C784,#4CAF50);border-radius:10px;display:flex;align-items:center;justify-content:center;">
                    <i class="fa fa-paperclip text-white"></i>
                </div>
                <h5 class="mb-0 fw-bold" style="color:#1B5E20;">مدارک و تصاویر</h5>
            </div>
            <div class="card-body p-4">
                @if (!empty($solarPlantRequest->images))
                    <p class="fw-semibold text-muted mb-3">تصاویر محل نصب</p>
                    <div class="d-flex flex-wrap gap-3 mb-4">
                        @foreach ($solarPlantRequest->images as $i => $img)
                            <a href="{{ route('solar-plant-requests.file.download', ['path' => $img]) }}"
                               download="{{ basename($img) }}"
                               class="btn btn-sm"
                               style="border:1px solid #A5D6A7;border-radius:8px;color:#2E7D32;background:#F1F8E9;padding:8px 16px;">
                                <i class="fa fa-image ms-1"></i> تصویر {{ $i + 1 }}
                            </a>
                        @endforeach
                    </div>
                @endif
                @if (!empty($solarPlantRequest->documents))
                    <p class="fw-semibold text-muted mb-3">مدارک هویتی</p>
                    <div class="d-flex flex-wrap gap-3">
                        @foreach ($solarPlantRequest->documents as $i => $doc)
                            <a href="{{ route('solar-plant-requests.file.download', ['path' => $doc]) }}"
                               download="{{ basename($doc) }}"
                               class="btn btn-sm"
                               style="border:1px solid #FFCC80;border-radius:8px;color:#E65100;background:#FFF8E1;padding:8px 16px;">
                                <i class="fa fa-file ms-1"></i> مدرک {{ $i + 1 }}
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    @endif

</div>
@endsection
