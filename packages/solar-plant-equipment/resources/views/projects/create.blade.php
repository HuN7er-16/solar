@extends('behin-layouts.app')

@section('title', 'ثبت پروژه جدید')

@section('style')
<style>
    body {
        direction: rtl;
        text-align: right;
    }
    .solar-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(255, 152, 0, 0.08), 0 2px 8px rgba(0, 0, 0, 0.04);
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    .solar-card-header {
        background: linear-gradient(90deg, #FFB74D 0%, #FF9800 100%);
        color: #fff;
        padding: 1rem 1.25rem;
        border: none;
    }
    .solar-card-header h6 {
        margin: 0;
        font-weight: 700;
        font-size: 0.95rem;
    }
    .solar-card-header i {
        margin-left: 0.5rem;
    }
    .solar-card-body {
        padding: 1.5rem;
        background: #fff;
    }
    .section-card {
        border: none;
        border-right: 4px solid #FF9800;
        border-radius: 12px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
        background: #fff;
        margin-bottom: 1.5rem;
    }
    .section-title {
        color: #FF9800;
        font-weight: 700;
        font-size: 1rem;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
    }
    .section-title i {
        margin-left: 0.5rem;
    }
    .form-group {
        margin-bottom: 1.25rem;
    }
    .form-group label {
        font-weight: 600;
        color: #424242;
        margin-bottom: 0.5rem;
        display: inline-block;
    }
    .form-control {
        border-radius: 8px;
        border: 1px solid #e0e0e0;
        padding: 0.55rem 0.85rem;
        transition: all 0.2s ease;
    }
    .form-control:focus {
        border-color: #FF9800;
        box-shadow: 0 0 0 3px rgba(255, 152, 0, 0.15);
        outline: none;
    }
    .form-control.is-invalid {
        border-color: #e53935;
    }
    .form-control.is-invalid:focus {
        box-shadow: 0 0 0 3px rgba(229, 57, 53, 0.15);
    }
    .select2-container--default .select2-selection--single,
    .select2-container--default .select2-selection--multiple {
        border-radius: 8px !important;
        border: 1px solid #e0e0e0 !important;
        min-height: 38px;
        transition: all 0.2s ease;
    }
    .select2-container--default .select2-selection--single:focus,
    .select2-container--default.select2-container--focus .select2-selection--multiple,
    .select2-container--default.select2-container--open .select2-selection--single {
        border-color: #FF9800 !important;
        box-shadow: 0 0 0 3px rgba(255, 152, 0, 0.15) !important;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #FF9800;
        border-color: #F57C00;
        color: #fff;
        border-radius: 6px;
        padding: 2px 10px;
        margin-top: 4px;
        margin-right: 4px;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: #fff;
        margin-left: 6px;
        float: right;
    }
    .select2-container {
        width: 100% !important;
    }
    .wizard-container {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1.5rem 0 1rem;
        margin-bottom: 1rem;
    }
    .wizard-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        z-index: 2;
    }
    .wizard-circle {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: linear-gradient(135deg, #FFB74D 0%, #FF9800 100%);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 1.1rem;
        box-shadow: 0 4px 12px rgba(255, 152, 0, 0.35);
        border: 3px solid #fff;
    }
    .wizard-label {
        margin-top: 0.6rem;
        font-size: 0.85rem;
        font-weight: 700;
        color: #616161;
        white-space: nowrap;
    }
    .wizard-line {
        flex: 1;
        max-width: 120px;
        height: 3px;
        border-top: 3px dashed #FFB74D;
        margin: 0 0.75rem;
        margin-bottom: 2.5rem;
    }
    .btn-solar {
        background: linear-gradient(90deg, #FF9800 0%, #F57C00 100%);
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 0.65rem 2rem;
        font-weight: 700;
        font-size: 0.95rem;
        box-shadow: 0 4px 14px rgba(255, 152, 0, 0.35);
        transition: all 0.2s ease;
    }
    .btn-solar:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(255, 152, 0, 0.45);
        color: #fff;
    }
    .btn-solar:active {
        transform: translateY(0);
    }
    .btn-cancel {
        border-radius: 8px;
        padding: 0.65rem 1.5rem;
        font-weight: 600;
        color: #757575;
        border: 1px solid #e0e0e0;
        background: #fff;
        transition: all 0.2s ease;
    }
    .btn-cancel:hover {
        background: #fafafa;
        color: #424242;
        border-color: #bdbdbd;
    }
    .page-header-card {
        border: none;
        border-radius: 12px;
        background: linear-gradient(135deg, #FFF3E0 0%, #FFE0B2 100%);
        box-shadow: 0 2px 12px rgba(255, 152, 0, 0.1);
        margin-bottom: 1.5rem;
    }
    .page-header-card .card-body {
        padding: 1.25rem 1.5rem;
    }
    .alert-danger {
        border-radius: 12px;
        border-right: 4px solid #e53935;
    }
    textarea.form-control {
        resize: vertical;
    }
    .text-muted-small {
        font-size: 0.8rem;
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-12">

        <div class="page-header-card">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <div style="width:48px;height:48px;border-radius:12px;background:linear-gradient(135deg,#FFB74D,#FF9800);display:flex;align-items:center;justify-content:center;margin-left:1rem;">
                        <i class="fa fa-sun-o text-white" style="font-size:1.4rem;"></i>
                    </div>
                    <div>
                        <h3 class="card-title mb-0" style="color:#E65100;font-weight:800;font-size:1.2rem;">
                            ثبت پروژه جدید نیروگاه خورشیدی
                        </h3>
                        <p class="mb-0 mt-1" style="color:#EF6C00;font-size:0.85rem;">
                            اطلاعات پایه پروژه را در فرم زیر وارد کنید
                        </p>
                    </div>
                </div>
                <a href="{{ route('solar-plant-equipment.projects.index') }}"
                   class="btn btn-cancel btn-sm">
                    <i class="fa fa-list ml-1"></i> لیست پروژه‌ها
                </a>
            </div>
        </div>

        <div class="wizard-container">
            <div class="wizard-step">
                <div class="wizard-circle">۱</div>
                <div class="wizard-label">اطلاعات پایه</div>
            </div>
            <div class="wizard-line"></div>
            <div class="wizard-step">
                <div class="wizard-circle">۲</div>
                <div class="wizard-label">زمانبندی و موقعیت</div>
            </div>
            <div class="wizard-line"></div>
            <div class="wizard-step">
                <div class="wizard-circle">۳</div>
                <div class="wizard-label">گواهی و توضیحات</div>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <h5><i class="icon fa fa-ban ml-1"></i> خطا در ثبت اطلاعات</h5>
                <ul class="mb-0 pr-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('solar-plant-equipment.projects.store') }}" id="projectForm">
            @csrf

            <div class="row">

                <div class="col-md-6">

                    <div class="section-card">
                        <div class="card-body p-4">
                            <div class="section-title">
                                <i class="fa fa-file-text-o"></i> درخواست مرتبط
                            </div>
                            <div class="form-group mb-0">
                                <label for="request_id">انتخاب درخواست ثبت‌شده</label>
                                <select name="request_id" id="request_id"
                                        class="form-control select2 @error('request_id') is-invalid @enderror">
                                    <option value="">— بدون درخواست —</option>
                                    @foreach ($requests as $req)
                                        <option value="{{ $req->id }}"
                                            {{ old('request_id') == $req->id ? 'selected' : '' }}>
                                            {{ $req->unique_code }}
                                            —
                                            @if ($req->applicant_type->value === 'company')
                                                {{ $req->company_name }}
                                            @else
                                                {{ $req->first_name }} {{ $req->last_name }}
                                            @endif
                                            ({{ $req->province }} / {{ $req->city }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('request_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted text-muted-small mt-2">
                                    می‌توانید پروژه را بدون درخواست ثبت کنید و بعداً تخصیص دهید.
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="section-card">
                        <div class="card-body p-4">
                            <div class="section-title">
                                <i class="fa fa-building"></i> پیمانکار
                            </div>
                            <div class="form-group mb-0">
                                <label for="contractor_id">انتخاب پیمانکار</label>
                                <select name="contractor_id" id="contractor_id"
                                        class="form-control select2 @error('contractor_id') is-invalid @enderror">
                                    <option value="">— بدون پیمانکار —</option>
                                    @foreach ($contractors as $contractor)
                                        <option value="{{ $contractor->id }}"
                                            {{ old('contractor_id') == $contractor->id ? 'selected' : '' }}>
                                            {{ $contractor->company_name }}
                                            @if ($contractor->license_number)
                                                (lic: {{ $contractor->license_number }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('contractor_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="section-card">
                        <div class="card-body p-4">
                            <div class="section-title">
                                <i class="fa fa-search"></i> بازرس
                            </div>
                            <div class="form-group mb-0">
                                <label for="inspector_id">انتخاب بازرس</label>
                                <select name="inspector_id" id="inspector_id"
                                        class="form-control select2 @error('inspector_id') is-invalid @enderror">
                                    <option value="">— بدون بازرس —</option>
                                    @foreach ($inspectors as $inspector)
                                        <option value="{{ $inspector->id }}"
                                            {{ old('inspector_id') == $inspector->id ? 'selected' : '' }}>
                                            {{ $inspector->name }}
                                            @if ($inspector->phone)
                                                — {{ $inspector->phone }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('inspector_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted text-muted-small mt-2">
                                    فقط کاربرانی با نقش بازرس نمایش داده می‌شوند.
                                </small>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="col-md-6">

                    <div class="section-card">
                        <div class="card-body p-4">
                            <div class="section-title">
                                <i class="fa fa-calendar"></i> تاریخ‌ها و وضعیت پروژه
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="installation_start_date">تاریخ شروع نصب</label>
                                        <input type="text" name="installation_start_date" id="installation_start_date"
                                               class="form-control persian-date @error('installation_start_date') is-invalid @enderror"
                                               placeholder="مثال: ۱۴۰۳/۰۱/۰۱"
                                               value="{{ old('installation_start_date') }}">
                                        @error('installation_start_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="installation_end_date">تاریخ پایان نصب</label>
                                        <input type="text" name="installation_end_date" id="installation_end_date"
                                               class="form-control persian-date @error('installation_end_date') is-invalid @enderror"
                                               placeholder="مثال: ۱۴۰۳/۰۶/۳۱"
                                               value="{{ old('installation_end_date') }}">
                                        @error('installation_end_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-0">
                                        <label for="commissioning_date">تاریخ بهره برداری</label>
                                        <input type="text" name="commissioning_date" id="commissioning_date"
                                               class="form-control persian-date @error('commissioning_date') is-invalid @enderror"
                                               placeholder="مثال: ۱۴۰۳/۰۷/۰۱"
                                               value="{{ old('commissioning_date') }}">
                                        @error('commissioning_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-0">
                                        <label for="status">وضعیت پروژه</label>
                                        <select name="status" id="status"
                                                class="form-control select2-single @error('status') is-invalid @enderror">
                                            @foreach (\SolarPlantEquipment\Models\SolarProject::getStatuses() as $key => $label)
                                                <option value="{{ $key }}"
                                                    {{ old('status', 'in_progress') == $key ? 'selected' : '' }}>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="section-card">
                        <div class="card-body p-4">
                            <div class="section-title">
                                <i class="fa fa-map-marker"></i> موقعیت جغرافیایی و قرارداد
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="latitude">عرض جغرافیایی (Latitude)</label>
                                        <input type="number" step="any" name="latitude" id="latitude"
                                               class="form-control @error('latitude') is-invalid @enderror"
                                               placeholder="مثلاً 35.6892000"
                                               value="{{ old('latitude') }}">
                                        @error('latitude')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="longitude">طول جغرافیایی (Longitude)</label>
                                        <input type="number" step="any" name="longitude" id="longitude"
                                               class="form-control @error('longitude') is-invalid @enderror"
                                               placeholder="مثلاً 51.3890000"
                                               value="{{ old('longitude') }}">
                                        @error('longitude')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-0">
                                <label for="satba_contract_number">شماره قرارداد ساتبا <span class="text-muted text-muted-small">(در صورت وجود)</span></label>
                                <input type="text" name="satba_contract_number" id="satba_contract_number"
                                       class="form-control @error('satba_contract_number') is-invalid @enderror"
                                       placeholder="شماره قرارداد ساتبا"
                                       value="{{ old('satba_contract_number') }}">
                                @error('satba_contract_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="section-card mb-4">
                <div class="card-body p-4">
                    <div class="section-title">
                        <i class="fa fa-id-card-o"></i> گواهی سلامت و توضیحات پروژه
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="health_card_no">شماره گواهی سلامت</label>
                                <input type="text" name="health_card_no" id="health_card_no"
                                       class="form-control @error('health_card_no') is-invalid @enderror"
                                       placeholder="شماره گواهی سلامت"
                                       value="{{ old('health_card_no') }}">
                                @error('health_card_no')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="health_card_issue_date">تاریخ صدور گواهی سلامت</label>
                                <input type="text" name="health_card_issue_date" id="health_card_issue_date"
                                       class="form-control persian-date @error('health_card_issue_date') is-invalid @enderror"
                                       placeholder="مثال: ۱۴۰۳/۰۱/۰۱"
                                       value="{{ old('health_card_issue_date') }}">
                                @error('health_card_issue_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="health_card_expiry_date">تاریخ انقضای گواهی سلامت</label>
                                <input type="text" name="health_card_expiry_date" id="health_card_expiry_date"
                                       class="form-control persian-date @error('health_card_expiry_date') is-invalid @enderror"
                                       placeholder="مثال: ۱۴۰۵/۰۱/۰۱"
                                       value="{{ old('health_card_expiry_date') }}">
                                @error('health_card_expiry_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-0">
                        <label for="description">توضیحات</label>
                        <textarea name="description" id="description" rows="4"
                                  class="form-control @error('description') is-invalid @enderror"
                                  placeholder="توضیحات تکمیلی مربوط به پروژه...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mb-5 d-flex align-items-center justify-content-start">
                <button type="submit" class="btn-solar ml-3">
                    <i class="fa fa-save ml-2"></i> ثبت پروژه
                </button>
                <a href="{{ route('solar-plant-equipment.projects.index') }}"
                   class="btn-cancel">
                    <i class="fa fa-times ml-1"></i> انصراف
                </a>
            </div>

        </form>

    </div>
</div>
@endsection

@section('script')
<script>
$(document).ready(function () {

    $('#request_id, #contractor_id, #inspector_id, #status, .select2-single').select2({
        placeholder: 'جستجو کنید...',
        allowClear: true,
        width: '100%',
        language: { noResults: function () { return 'نتیجه‌ای یافت نشد'; } }
    });

    $('.select2-multi').select2({
        placeholder: 'انتخاب کنید...',
        allowClear: true,
        width: '100%',
        language: { noResults: function () { return 'نتیجه‌ای یافت نشد'; } }
    });

});
</script>
@endsection
