@extends('behin-layouts.app')

@section('title', 'ویرایش پروژه #' . $project->id)

@section('style')
<style>
    .solar-card {
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border: none;
        margin-bottom: 1.5rem;
        overflow: hidden;
    }
    .solar-card-header {
        background: linear-gradient(135deg, #FFB74D 0%, #FF9800 100%);
        color: #fff;
        padding: 1rem 1.25rem;
        border: none;
    }
    .solar-card-header .card-title {
        color: #fff;
        font-weight: 700;
        margin: 0;
    }
    .solar-card-body {
        padding: 1.5rem;
        background: #fff;
    }
    .section-card {
        border-right: 4px solid #FF9800;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        margin-bottom: 1.5rem;
        background: #fff;
    }
    .section-title {
        color: #FF9800;
        font-weight: 700;
        font-size: 1.05rem;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
    }
    .section-title i {
        margin-left: 0.5rem;
    }
    .select2-container--default .select2-selection--single,
    .select2-container--default .select2-selection--multiple {
        min-height: 38px;
        border: 1px solid #ced4da;
        border-radius: 8px;
    }
    .select2-container--default .select2-selection--multiple {
        padding: 2px 4px;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background: linear-gradient(135deg, #FFB74D 0%, #FF9800 100%);
        border-color: #F57C00;
        color: #fff;
        border-radius: 6px;
        padding: 2px 10px;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: #fff;
        margin-left: 4px;
        opacity: 0.85;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
        opacity: 1;
    }
    .select2-container--default.select2-container--focus .select2-selection--single,
    .select2-container--default.select2-container--focus .select2-selection--multiple {
        border-color: #FF9800;
        box-shadow: 0 0 0 0.2rem rgba(255, 152, 0, 0.15);
    }
    .form-control,
    .custom-select {
        border-radius: 8px;
        transition: all 0.2s ease;
    }
    .form-control:focus,
    .custom-select:focus {
        border-color: #FF9800;
        box-shadow: 0 0 0 0.2rem rgba(255, 152, 0, 0.15);
    }
    .form-group label {
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
    }

    /* Workflow Steps */
    .workflow-container {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        padding: 1.75rem 1rem;
        margin-bottom: 1.5rem;
    }
    .workflow-steps {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        position: relative;
        flex-wrap: wrap;
    }
    .workflow-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        flex: 1;
        min-width: 140px;
        position: relative;
        z-index: 2;
    }
    .workflow-step-circle {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: linear-gradient(135deg, #FFB74D 0%, #FF9800 100%);
        color: #fff;
        font-weight: 700;
        font-size: 1.25rem;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 6px rgba(255, 152, 0, 0.35);
        margin-bottom: 0.6rem;
        border: 3px solid #fff;
    }
    .workflow-step-label {
        font-weight: 700;
        color: #FF9800;
        font-size: 0.9rem;
        text-align: center;
    }
    .workflow-step-sub {
        font-size: 0.78rem;
        color: #6B7280;
        text-align: center;
        margin-top: 0.2rem;
    }
    .workflow-connector {
        flex: 0 0 auto;
        width: 80px;
        padding-top: 22px;
    }
    .workflow-connector-line {
        border-top: 3px dashed #FFB74D;
        width: 100%;
    }

    /* Buttons */
    .btn-solar-primary {
        background: linear-gradient(135deg, #FFC107 0%, #FF9800 100%);
        color: #fff;
        border: none;
        font-weight: 700;
        padding: 0.75rem 2.5rem;
        font-size: 1rem;
        border-radius: 10px;
        box-shadow: 0 3px 8px rgba(255, 152, 0, 0.3);
        transition: all 0.2s ease;
    }
    .btn-solar-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 5px 14px rgba(255, 152, 0, 0.4);
        color: #fff;
    }
    .btn-solar-secondary {
        background: #fff;
        color: #6B7280;
        border: 1px solid #D1D5DB;
        font-weight: 600;
        padding: 0.75rem 1.75rem;
        border-radius: 10px;
        transition: all 0.2s ease;
    }
    .btn-solar-secondary:hover {
        background: #F9FAFB;
        border-color: #FF9800;
        color: #FF9800;
    }
    .btn-back {
        background: #fff;
        color: #2196F3;
        border: 1px solid #2196F3;
        font-weight: 600;
        padding: 0.5rem 1.25rem;
        border-radius: 8px;
        transition: all 0.2s ease;
    }
    .btn-back:hover {
        background: #2196F3;
        color: #fff;
    }

    /* Alert */
    .solar-alert-danger {
        border-right: 4px solid #E53935;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    }
    .solar-alert-danger h5 {
        color: #E53935;
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-12">

        {{-- Header Card --}}
        <div class="solar-card">
            <div class="solar-card-header d-flex align-items-center justify-content-between">
                <h3 class="card-title">
                    <i class="fa fa-pencil-square-o ml-2"></i>
                    ویرایش پروژه <span class="badge badge-light" style="background: rgba(255,255,255,0.25); color: #fff;">#{{ $project->id }}</span>
                </h3>
                <a href="{{ route('solar-plant-equipment.projects.index') }}"
                   class="btn-back">
                    <i class="fa fa-arrow-right ml-1"></i> بازگشت
                </a>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger solar-alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <h5><i class="icon fa fa-exclamation-circle ml-1"></i> خطا در ویرایش اطلاعات</h5>
                <ul class="mb-0 pr-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Workflow Steps --}}
        <div class="workflow-container">
            <div class="workflow-steps">
                <div class="workflow-step">
                    <div class="workflow-step-circle">۱</div>
                    <div class="workflow-step-label">اطلاعات پایه</div>
                    <div class="workflow-step-sub">درخواست · پیمانکار · بازرس</div>
                </div>
                <div class="workflow-connector">
                    <div class="workflow-connector-line"></div>
                </div>
                <div class="workflow-step">
                    <div class="workflow-step-circle">۲</div>
                    <div class="workflow-step-label">زمانبندی و موقعیت</div>
                    <div class="workflow-step-sub">تاریخ‌ها · مختصات · ساتبا</div>
                </div>
                <div class="workflow-connector">
                    <div class="workflow-connector-line"></div>
                </div>
                <div class="workflow-step">
                    <div class="workflow-step-circle">۳</div>
                    <div class="workflow-step-label">گواهی و توضیحات</div>
                    <div class="workflow-step-sub">گواهی سلامت · توضیحات</div>
                </div>
            </div>
        </div>

        <form method="POST"
              action="{{ route('solar-plant-equipment.projects.update', $project) }}"
              id="projectForm">
            @csrf
            @method('PUT')

            {{-- =============== STEP 1: اطلاعات پایه =============== --}}
            <div class="row">
                <div class="col-md-6">

                    {{-- درخواست --}}
                    <div class="section-card">
                        <div class="solar-card-body">
                            <div class="section-title">
                                <i class="fa fa-file-text-o ml-1"></i> درخواست مرتبط
                            </div>
                            <div class="form-group mb-0">
                                <label for="request_id">انتخاب درخواست ثبت‌شده</label>
                                <select name="request_id" id="request_id"
                                        class="form-control select2-single @error('request_id') is-invalid @enderror">
                                    <option value="">— بدون درخواست —</option>
                                    @foreach ($requests as $req)
                                        <option value="{{ $req->id }}"
                                            {{ old('request_id', $project->request_id) == $req->id ? 'selected' : '' }}>
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
                                @error('request_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- پیمانکار --}}
                    <div class="section-card">
                        <div class="solar-card-body">
                            <div class="section-title">
                                <i class="fa fa-building-o ml-1"></i> پیمانکار
                            </div>
                            <div class="form-group mb-0">
                                <label for="contractor_id">انتخاب پیمانکار</label>
                                <select name="contractor_id" id="contractor_id"
                                        class="form-control select2-single @error('contractor_id') is-invalid @enderror">
                                    <option value="">— بدون پیمانکار —</option>
                                    @foreach ($contractors as $contractor)
                                        <option value="{{ $contractor->id }}"
                                            {{ old('contractor_id', $project->contractor_id) == $contractor->id ? 'selected' : '' }}>
                                            {{ $contractor->company_name }}
                                            @if ($contractor->license_number)
                                                (lic: {{ $contractor->license_number }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('contractor_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- بازرس --}}
                    <div class="section-card">
                        <div class="solar-card-body">
                            <div class="section-title">
                                <i class="fa fa-user-search ml-1"></i> بازرس
                            </div>
                            <div class="form-group mb-0">
                                <label for="inspector_id">انتخاب بازرس</label>
                                <select name="inspector_id" id="inspector_id"
                                        class="form-control select2-single @error('inspector_id') is-invalid @enderror">
                                    <option value="">— بدون بازرس —</option>
                                    @foreach ($inspectors as $inspector)
                                        <option value="{{ $inspector->id }}"
                                            {{ old('inspector_id', $project->inspector_id) == $inspector->id ? 'selected' : '' }}>
                                            {{ $inspector->name }}
                                            @if ($inspector->phone)
                                                — {{ $inspector->phone }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('inspector_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                <small class="form-text text-muted">
                                    فقط کاربرانی با نقش بازرس نمایش داده می‌شوند.
                                </small>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="col-md-6">

                    {{-- تاریخ‌ها و وضعیت --}}
                    <div class="section-card">
                        <div class="solar-card-body">
                            <div class="section-title">
                                <i class="fa fa-calendar-check-o ml-1"></i> تاریخ‌ها و وضعیت پروژه
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="installation_start_date">تاریخ شروع نصب</label>
                                        <input type="text" name="installation_start_date" id="installation_start_date"
                                               class="form-control persian-date @error('installation_start_date') is-invalid @enderror"
                                               placeholder="مثال: ۱۴۰۳/۰۱/۰۱"
                                               value="{{ old('installation_start_date', $project->installation_start_date ? toJalaliFormatted($project->installation_start_date, 'Y/m/d') : '') }}">
                                        @error('installation_start_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="installation_end_date">تاریخ پایان نصب</label>
                                        <input type="text" name="installation_end_date" id="installation_end_date"
                                               class="form-control persian-date @error('installation_end_date') is-invalid @enderror"
                                               placeholder="مثال: ۱۴۰۳/۰۶/۳۱"
                                               value="{{ old('installation_end_date', $project->installation_end_date ? toJalaliFormatted($project->installation_end_date, 'Y/m/d') : '') }}">
                                        @error('installation_end_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="commissioning_date">تاریخ بهره برداری</label>
                                        <input type="text" name="commissioning_date" id="commissioning_date"
                                               class="form-control persian-date @error('commissioning_date') is-invalid @enderror"
                                               placeholder="مثال: ۱۴۰۳/۰۷/۰۱"
                                               value="{{ old('commissioning_date', $project->commissioning_date ? toJalaliFormatted($project->commissioning_date, 'Y/m/d') : '') }}">
                                        @error('commissioning_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-0">
                                        <label for="status">وضعیت پروژه</label>
                                        <select name="status" id="status"
                                                class="form-control select2-single @error('status') is-invalid @enderror">
                                            @foreach (\SolarPlantEquipment\Models\SolarProject::getStatuses() as $key => $label)
                                                <option value="{{ $key }}"
                                                    {{ old('status', $project->status ?? 'in_progress') == $key ? 'selected' : '' }}>
                                                    {{ $label }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- مختصات جغرافیایی و قرارداد ساتبا --}}
                    <div class="section-card">
                        <div class="solar-card-body">
                            <div class="section-title">
                                <i class="fa fa-map-marker ml-1"></i> موقعیت جغرافیایی و قرارداد
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="latitude">عرض جغرافیایی (Latitude)</label>
                                        <input type="number" step="any" name="latitude" id="latitude"
                                               class="form-control @error('latitude') is-invalid @enderror"
                                               placeholder="مثلاً 35.6892000"
                                               value="{{ old('latitude', $project->latitude) }}">
                                        @error('latitude') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="longitude">طول جغرافیایی (Longitude)</label>
                                        <input type="number" step="any" name="longitude" id="longitude"
                                               class="form-control @error('longitude') is-invalid @enderror"
                                               placeholder="مثلاً 51.3890000"
                                               value="{{ old('longitude', $project->longitude) }}">
                                        @error('longitude') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-0">
                                <label for="satba_contract_number">شماره قرارداد ساتبا <span class="text-muted small">(در صورت وجود)</span></label>
                                <input type="text" name="satba_contract_number" id="satba_contract_number"
                                       class="form-control @error('satba_contract_number') is-invalid @enderror"
                                       placeholder="شماره قرارداد ساتبا"
                                       value="{{ old('satba_contract_number', $project->satba_contract_number) }}">
                                @error('satba_contract_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- =============== STEP 3: گواهی سلامت و توضیحات =============== --}}
            <div class="section-card">
                <div class="solar-card-header">
                    <div class="section-title mb-0" style="color: #fff;">
                        <i class="fa fa-id-card-o ml-1"></i> گواهی سلامت و توضیحات پروژه
                    </div>
                </div>
                <div class="solar-card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="health_card_no">شماره گواهی سلامت</label>
                                <input type="text" name="health_card_no" id="health_card_no"
                                       class="form-control @error('health_card_no') is-invalid @enderror"
                                       placeholder="شماره گواهی سلامت"
                                       value="{{ old('health_card_no', $project->health_card_no) }}">
                                @error('health_card_no') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="health_card_issue_date">تاریخ صدور گواهی سلامت</label>
                                <input type="text" name="health_card_issue_date" id="health_card_issue_date"
                                       class="form-control persian-date @error('health_card_issue_date') is-invalid @enderror"
                                       placeholder="مثال: ۱۴۰۳/۰۱/۰۱"
                                       value="{{ old('health_card_issue_date', $project->health_card_issue_date ? toJalaliFormatted($project->health_card_issue_date, 'Y/m/d') : '') }}">
                                @error('health_card_issue_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="health_card_expiry_date">تاریخ انقضای گواهی سلامت</label>
                                <input type="text" name="health_card_expiry_date" id="health_card_expiry_date"
                                       class="form-control persian-date @error('health_card_expiry_date') is-invalid @enderror"
                                       placeholder="مثال: ۱۴۰۵/۰۱/۰۱"
                                       value="{{ old('health_card_expiry_date', $project->health_card_expiry_date ? toJalaliFormatted($project->health_card_expiry_date, 'Y/m/d') : '') }}">
                                @error('health_card_expiry_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-0">
                        <label for="description">توضیحات</label>
                        <textarea name="description" id="description" rows="4"
                                  class="form-control @error('description') is-invalid @enderror"
                                  placeholder="توضیحات تکمیلی مربوط به پروژه...">{{ old('description', $project->description) }}</textarea>
                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="solar-card mb-4">
                <div class="solar-card-body d-flex flex-wrap justify-content-between align-items-center" style="gap: 1rem;">
                    <button type="submit" class="btn-solar-primary">
                        <i class="fa fa-check-circle ml-2"></i> ذخیره تغییرات
                    </button>
                    <a href="{{ route('solar-plant-equipment.projects.show', $project) }}"
                       class="btn-solar-secondary">
                        <i class="fa fa-times ml-1"></i> انصراف و بازگشت
                    </a>
                </div>
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
});
</script>
@endsection
