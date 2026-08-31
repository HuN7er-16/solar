@extends('behin-layouts.app')

@section('title', 'ثبت بازرسی پروژه')

@section('style')
<style>
    .green-gradient-header {
        background: linear-gradient(135deg, #66BB6A 0%, #43A047 100%);
        color: #fff;
        border-radius: 12px;
        padding: 1.25rem 1.75rem;
        box-shadow: 0 6px 20px rgba(67, 160, 71, 0.28);
    }
    .btn-white-green {
        background: #fff;
        color: #2E7D32;
        border: none;
        font-weight: 700;
        padding: .5rem 1.1rem;
        border-radius: 10px;
        box-shadow: 0 3px 10px rgba(0,0,0,.1);
        transition: all .2s;
    }
    .btn-white-green:hover {
        transform: translateY(-1px);
        box-shadow: 0 5px 14px rgba(0,0,0,.15);
        color: #1B5E20;
    }
    .workflow-bar {
        display: flex;
        align-items: flex-start;
        justify-content: center;
        padding: 1rem 0.5rem 1.75rem;
        flex-wrap: wrap;
        gap: 0;
    }
    .workflow-step {
        display: flex;
        align-items: center;
        flex-direction: column;
        position: relative;
        min-width: 58px;
    }
    .workflow-circle {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 0.85rem;
        color: #fff;
        box-shadow: 0 3px 8px rgba(0,0,0,.15);
    }
    .wc-info      { background: linear-gradient(135deg, #4FC3F7, #0288D1); }
    .wc-panel     { background: linear-gradient(135deg, #FFD54F, #FFA000); color:#5D4037; }
    .wc-structure { background: linear-gradient(135deg, #BA68C8, #7B1FA2); }
    .wc-cable     { background: linear-gradient(135deg, #FFB74D, #F57C00); }
    .wc-inverter  { background: linear-gradient(135deg, #5C9EFF, #1565C0); }
    .wc-battery   { background: linear-gradient(135deg, #81C784, #388E3C); }
    .wc-grounding { background: linear-gradient(135deg, #EF5350, #C62828); }
    .wc-panelbox  { background: linear-gradient(135deg, #90A4AE, #546E7A); }
    .wc-perf      { background: linear-gradient(135deg, #4DB6AC, #00897B); }
    .wc-safety    { background: linear-gradient(135deg, #546E7A, #263238); }
    .workflow-label {
        font-size: 0.66rem;
        color: #546E7A;
        margin-top: 6px;
        white-space: nowrap;
        font-weight: 700;
    }
    .workflow-line {
        flex: 0 0 24px;
        border-top: 2px dashed #CFD8DC;
        margin-top: 15px;
    }

    .section-card {
        border-right: 5px solid #43A047;
        margin-bottom: 1.25rem;
        border-radius: 12px;
        box-shadow: 0 3px 12px rgba(0,0,0,.05);
        border-top: 1px solid #f0f2f4;
        border-left: 1px solid #f0f2f4;
        border-bottom: 1px solid #f0f2f4;
        background: #fff;
        overflow: hidden;
    }
    .section-card .card-body { padding: 1.1rem 1.1rem; }
    .section-card.section-info     { border-right-color: #0288D1; }
    .section-card.section-panel    { border-right-color: #FFA000; }
    .section-card.section-structure{ border-right-color: #7B1FA2; }
    .section-card.section-cable    { border-right-color: #F57C00; }
    .section-card.section-inverter { border-right-color: #1565C0; }
    .section-card.section-battery  { border-right-color: #388E3C; }
    .section-card.section-grounding{ border-right-color: #C62828; }
    .section-card.section-panelbox { border-right-color: #546E7A; }
    .section-card.section-perf     { border-right-color: #00897B; }
    .section-card.section-safety   { border-right-color: #263238; }

    .section-title {
        font-weight: 800;
        font-size: 1rem;
        margin-bottom: 1rem;
        padding-bottom: 0.55rem;
        border-bottom: 2px dashed #ECEFF1;
        display: flex;
        align-items: center;
        gap: 7px;
    }
    .section-info     .section-title { color: #0277BD; }
    .section-panel    .section-title { color: #E65100; }
    .section-structure .section-title { color: #6A1B9A; }
    .section-cable    .section-title { color: #E65100; }
    .section-inverter .section-title { color: #0D47A1; }
    .section-battery  .section-title { color: #2E7D32; }
    .section-grounding .section-title { color: #B71C1C; }
    .section-panelbox .section-title { color: #37474F; }
    .section-perf     .section-title { color: #00695C; }
    .section-safety   .section-title { color: #263238; }

    .check-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 11px 15px;
        border: 1px solid #ECEFF1;
        border-radius: 10px;
        margin-bottom: 9px;
        background: #FAFCFD;
        transition: all .2s;
        gap: 12px;
    }
    .check-item:hover {
        background: #F4F8FB;
        border-color: #CFD8DC;
        transform: translateX(-2px);
    }
    .check-item label {
        margin: 0;
        cursor: pointer;
        flex: 1;
        font-size: 0.92rem;
        color: #37474F;
        font-weight: 500;
    }
    .check-item input[type="checkbox"] {
        width: 22px;
        height: 22px;
        cursor: pointer;
        accent-color: #43A047;
        flex-shrink: 0;
    }
    .project-info-box {
        background: linear-gradient(135deg, #E8F5E9 0%, #C8E6C9 100%);
        border-radius: 12px;
        padding: 1.35rem;
        margin-bottom: 1.5rem;
        border: 1px solid rgba(67, 160, 71, 0.15);
        box-shadow: 0 3px 12px rgba(67, 160, 71, 0.08);
    }
    .project-info-box h6 {
        color: #1B5E20;
    }
    .sticky-submit {
        position: sticky;
        bottom: 0;
        z-index: 100;
        background: linear-gradient(180deg, rgba(255,255,255,.92), rgba(255,255,255,1));
        border-top: 2px solid #C8E6C9;
        padding: 18px 0;
        margin: 25px -25px -25px;
        padding-left: 25px;
        padding-right: 25px;
        backdrop-filter: blur(10px);
    }
    .btn-green-gradient {
        background: linear-gradient(135deg, #66BB6A 0%, #43A047 100%);
        color: #fff;
        border: none;
        box-shadow: 0 4px 14px rgba(67, 160, 71, 0.38);
        transition: all .25s;
        font-weight: 700;
        border-radius: 10px;
    }
    .btn-green-gradient:hover {
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 7px 20px rgba(67, 160, 71, 0.48);
    }
    .notes-textarea {
        border-radius: 10px;
        border: 1px dashed #CFD8DC;
        font-size: 0.88rem;
        background: #FAFCFD;
    }
    .notes-textarea:focus {
        border-color: #81C784;
        box-shadow: 0 0 0 3px rgba(129,199,132,0.18);
        background: #fff;
    }
    .main-wrapper {
        background: #FAFCFD;
        border-radius: 14px;
        padding: 1.25rem;
        border: 1px solid #ECEFF1;
    }
    .info-line-item {
        font-size: .86rem;
        color: #2E7D32;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .info-line-item i { color: #43A047; }
    .rejection-box-wrapper {
        background: #FFF5F5;
        border: 1px solid #FFCDD2;
        border-right: 4px solid #E53935;
        border-radius: 10px;
        padding: 14px 16px;
    }
    .rejection-box-wrapper label {
        color: #B71C1C;
    }
    .form-input-custom {
        border-radius: 10px;
        border: 1px solid #CFD8DC;
        transition: all .2s;
    }
    .form-input-custom:focus {
        border-color: #81C784;
        box-shadow: 0 0 0 3px rgba(129,199,132,0.18);
    }
    .error-alert-custom {
        border-radius: 12px;
        border-right: 5px solid #E53935;
        background: #FFF8F8;
    }
    .battery-present-item {
        background: linear-gradient(135deg, #E8F5E9, #C8E6C9);
        border-color: #A5D6A7;
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-12">

        <div class="mb-4" style="border-radius:12px; overflow:hidden;">
            <div class="green-gradient-header d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div>
                    <h3 class="card-title mb-0" style="color:#fff; font-weight:800; font-size:1.35rem;">
                        <i class="fa fa-clipboard-check ml-2" style="text-shadow:0 0 12px rgba(255,255,255,.45);"></i>
                        فرم بازرسی پروژه نیروگاه خورشیدی
                    </h3>
                    <small style="color:rgba(255,255,255,.88);" class="mr-4 d-inline-block mt-2">
                        <i class="fa fa-leaf ml-1"></i> تکمیل کلیه ۱۰ بخش زیر الزامی می‌باشد
                    </small>
                </div>
                <a href="{{ route('project-inspection.inspections.index') }}"
                   class="btn btn-white-green btn-sm">
                    <i class="fa fa-list ml-1"></i> بازگشت به لیست
                </a>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible mb-4 error-alert-custom">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <h5 class="font-weight-bold text-danger"><i class="icon fa fa-ban ml-2"></i> خطا در ثبت اطلاعات</h5>
                <ul class="mb-0 pr-3 text-danger">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('project-inspection.inspections.store') }}" id="inspectionForm">
            @csrf

            <div class="main-wrapper">

                <div class="workflow-bar">
                    <div class="workflow-step">
                        <div class="workflow-circle wc-info">۱</div>
                        <div class="workflow-label">اطلاعات</div>
                    </div>
                    <div class="workflow-line"></div>
                    <div class="workflow-step">
                        <div class="workflow-circle wc-panel">۲</div>
                        <div class="workflow-label">پنل</div>
                    </div>
                    <div class="workflow-line"></div>
                    <div class="workflow-step">
                        <div class="workflow-circle wc-structure">۳</div>
                        <div class="workflow-label">سازه</div>
                    </div>
                    <div class="workflow-line"></div>
                    <div class="workflow-step">
                        <div class="workflow-circle wc-cable">۴</div>
                        <div class="workflow-label">کابل</div>
                    </div>
                    <div class="workflow-line"></div>
                    <div class="workflow-step">
                        <div class="workflow-circle wc-inverter">۵</div>
                        <div class="workflow-label">اینورتر</div>
                    </div>
                    <div class="workflow-line"></div>
                    <div class="workflow-step">
                        <div class="workflow-circle wc-battery">۶</div>
                        <div class="workflow-label">باتری</div>
                    </div>
                    <div class="workflow-line"></div>
                    <div class="workflow-step">
                        <div class="workflow-circle wc-grounding">۷</div>
                        <div class="workflow-label">ارت</div>
                    </div>
                    <div class="workflow-line"></div>
                    <div class="workflow-step">
                        <div class="workflow-circle wc-panelbox">۸</div>
                        <div class="workflow-label">تابلو</div>
                    </div>
                    <div class="workflow-line"></div>
                    <div class="workflow-step">
                        <div class="workflow-circle wc-perf">۹</div>
                        <div class="workflow-label">کارکرد</div>
                    </div>
                    <div class="workflow-line"></div>
                    <div class="workflow-step">
                        <div class="workflow-circle wc-safety">۱۰</div>
                        <div class="workflow-label">ایمنی</div>
                    </div>
                </div>

                <div class="project-info-box">
                    <h6 class="font-weight-bold mb-3">
                        <i class="fa fa-info-circle ml-2"></i>
                        اطلاعات پروژه و بازرسی
                    </h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="project_selector" style="font-weight:700; color:#1B5E20;">انتخاب پروژه</label>
                                <select id="project_selector" class="form-control select2 form-input-custom" style="border-radius:10px;">
                                    @foreach ($availableProjects as $p)
                                        <option value="{{ $p->id }}"
                                            {{ $p->id == $project->id ? 'selected' : '' }}
                                            data-url="{{ route('project-inspection.inspections.create', ['project_id' => $p->id]) }}">
                                            پروژه #{{ $p->id }}
                                            @if ($p->request)
                                                — کد درخواست: {{ $p->request->unique_code ?? '#' . $p->request_id }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="project_id" value="{{ $project->id }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="visit_date" style="font-weight:700; color:#1B5E20;">تاریخ بازدید <span class="text-danger">*</span></label>
                                <input type="text" name="visit_date" id="visit_date"
                                       class="form-control persian-date form-input-custom @error('visit_date') is-invalid @enderror"
                                       style="border-radius:10px;"
                                       placeholder="مثال: ۱۴۰۳/۰۱/۰۱"
                                       value="{{ old('visit_date') }}" required>
                                @error('visit_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="result" style="font-weight:700; color:#1B5E20;">نتیجه بازرسی <span class="text-danger">*</span></label>
                                <select name="result" id="result"
                                        class="form-control form-input-custom @error('result') is-invalid @enderror"
                                        style="border-radius:10px;" required>
                                    <option value="">— انتخاب کنید —</option>
                                    <option value="approved" {{ old('result') == 'approved' ? 'selected' : '' }}>تایید شده</option>
                                    <option value="rejected" {{ old('result') == 'rejected' ? 'selected' : '' }}>عدم تایید</option>
                                </select>
                                @error('result')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-md-4">
                            <div class="info-line-item">
                                <i class="fa fa-user-circle"></i>
                                <span>بازرس: {{ auth()->user()->name ?? '—' }}</span>
                            </div>
                        </div>
                        @if ($project->contractor)
                        <div class="col-md-4">
                            <div class="info-line-item">
                                <i class="fa fa-building"></i>
                                <span>پیمانکار: {{ $project->contractor->company_name ?? '—' }}</span>
                            </div>
                        </div>
                        @endif
                        @if ($project->request)
                        <div class="col-md-4">
                            <div class="info-line-item">
                                <i class="fa fa-file-text-o"></i>
                                <span>درخواست: {{ $project->request->unique_code ?? '#' . $project->request_id }}</span>
                            </div>
                        </div>
                        @endif
                    </div>

                    <div id="rejectionReasonBox" class="mt-3" style="display:none;">
                        <div class="rejection-box-wrapper">
                            <div class="form-group mb-0">
                                <label for="rejection_reason" style="font-weight:700;">
                                    <i class="fa fa-times-circle ml-1"></i> علت عدم تایید
                                </label>
                                <textarea name="rejection_reason" id="rejection_reason" rows="2"
                                          class="form-control notes-textarea @error('rejection_reason') is-invalid @enderror"
                                          placeholder="لطفاً علت عدم تایید پروژه را توضیح دهید...">{{ old('rejection_reason') }}</textarea>
                                @error('rejection_reason')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">

                        <div class="card section-card section-info">
                            <div class="card-body">
                                <div class="section-title">
                                    <i class="fa fa-info-circle"></i> ۱. اطلاعات پروژه
                                </div>
                                <div class="check-item">
                                    <label>اطلاعات پروژه با سامانه مطابقت دارد؟</label>
                                    <input type="checkbox" name="project_info_matches_system" value="1" {{ old('project_info_matches_system') ? 'checked' : '' }}>
                                </div>
                                <div class="check-item">
                                    <label>ظرفیت نیروگاه صحیح است؟</label>
                                    <input type="checkbox" name="plant_capacity_correct" value="1" {{ old('plant_capacity_correct') ? 'checked' : '' }}>
                                </div>
                                <div class="check-item">
                                    <label>محل نصب مطابق اطلاعات ثبت‌شده است؟</label>
                                    <input type="checkbox" name="installation_location_correct" value="1" {{ old('installation_location_correct') ? 'checked' : '' }}>
                                </div>
                                <div class="form-group mt-3 mb-0">
                                    <textarea name="project_info_notes" rows="2" class="form-control notes-textarea"
                                              placeholder="توضیحات تکمیلی بخش اطلاعات پروژه...">{{ old('project_info_notes') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="card section-card section-panel">
                            <div class="card-body">
                                <div class="section-title">
                                    <i class="fa fa-sun-o"></i> ۲. پنل خورشیدی
                                </div>
                                <div class="check-item">
                                    <label>برند پنل مورد تأیید اتحادیه است؟</label>
                                    <input type="checkbox" name="panel_brand_union_approved" value="1" {{ old('panel_brand_union_approved') ? 'checked' : '' }}>
                                </div>
                                <div class="check-item">
                                    <label>برند و مدل با اطلاعات پروژه یکسان است؟</label>
                                    <input type="checkbox" name="panel_brand_matches_project" value="1" {{ old('panel_brand_matches_project') ? 'checked' : '' }}>
                                </div>
                                <div class="check-item">
                                    <label>مدل مورد تأیید و با مدل پروژه یکسان است؟</label>
                                    <input type="checkbox" name="panel_model_approved" value="1" {{ old('panel_model_approved') ? 'checked' : '' }}>
                                </div>
                                <div class="check-item">
                                    <label>شماره سریال صحیح است؟</label>
                                    <input type="checkbox" name="panel_serial_correct" value="1" {{ old('panel_serial_correct') ? 'checked' : '' }}>
                                </div>
                                <div class="check-item">
                                    <label>تعداد پنل‌ها صحیح است؟</label>
                                    <input type="checkbox" name="panel_quantity_correct" value="1" {{ old('panel_quantity_correct') ? 'checked' : '' }}>
                                </div>
                                <div class="check-item">
                                    <label>پنل سالم و بدون شکستگی است؟</label>
                                    <input type="checkbox" name="panel_intact" value="1" {{ old('panel_intact') ? 'checked' : '' }}>
                                </div>
                                <div class="check-item">
                                    <label>جهت نصب صحیح است؟</label>
                                    <input type="checkbox" name="panel_orientation_correct" value="1" {{ old('panel_orientation_correct') ? 'checked' : '' }}>
                                </div>
                                <div class="check-item">
                                    <label>زاویه نصب مناسب است؟</label>
                                    <input type="checkbox" name="panel_angle_correct" value="1" {{ old('panel_angle_correct') ? 'checked' : '' }}>
                                </div>
                                <div class="form-group mt-3 mb-0">
                                    <textarea name="panel_notes" rows="2" class="form-control notes-textarea"
                                              placeholder="توضیحات تکمیلی بخش پنل خورشیدی...">{{ old('panel_notes') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="card section-card section-structure">
                            <div class="card-body">
                                <div class="section-title">
                                    <i class="fa fa-cubes"></i> ۳. سازه (استراکچر)
                                </div>
                                <div class="check-item">
                                    <label>سازه استاندارد است؟</label>
                                    <input type="checkbox" name="structure_standard" value="1" {{ old('structure_standard') ? 'checked' : '' }}>
                                </div>
                                <div class="check-item">
                                    <label>پیچ و مهره‌ها محکم بسته شده‌اند؟</label>
                                    <input type="checkbox" name="bolts_tightened" value="1" {{ old('bolts_tightened') ? 'checked' : '' }}>
                                </div>
                                <div class="check-item">
                                    <label>خوردگی مشاهده نشده است؟</label>
                                    <input type="checkbox" name="no_corrosion" value="1" {{ old('no_corrosion') ? 'checked' : '' }}>
                                </div>
                                <div class="check-item">
                                    <label>فاصله مناسب از سطح زمین رعایت شده؟</label>
                                    <input type="checkbox" name="proper_ground_clearance" value="1" {{ old('proper_ground_clearance') ? 'checked' : '' }}>
                                </div>
                                <div class="form-group mt-3 mb-0">
                                    <textarea name="structure_notes" rows="2" class="form-control notes-textarea"
                                              placeholder="توضیحات تکمیلی بخش سازه...">{{ old('structure_notes') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="card section-card section-cable">
                            <div class="card-body">
                                <div class="section-title">
                                    <i class="fa fa-plug"></i> ۴. کابل‌کشی DC
                                </div>
                                <div class="check-item">
                                    <label>کابل‌ها استاندارد هستند؟</label>
                                    <input type="checkbox" name="cable_standard" value="1" {{ old('cable_standard') ? 'checked' : '' }}>
                                </div>
                                <div class="check-item">
                                    <label>سطح مقطع کابل مناسب است؟</label>
                                    <input type="checkbox" name="proper_cross_section" value="1" {{ old('proper_cross_section') ? 'checked' : '' }}>
                                </div>
                                <div class="check-item">
                                    <label>کابل‌کشی به صورت صحیح انجام شده است؟</label>
                                    <input type="checkbox" name="proper_cabling" value="1" {{ old('proper_cabling') ? 'checked' : '' }}>
                                </div>
                                <div class="check-item">
                                    <label>کانکتورهای MC4 استاندارد هستند؟</label>
                                    <input type="checkbox" name="mc4_connectors_standard" value="1" {{ old('mc4_connectors_standard') ? 'checked' : '' }}>
                                </div>
                                <div class="form-group mt-3 mb-0">
                                    <textarea name="dc_cabling_notes" rows="2" class="form-control notes-textarea"
                                              placeholder="توضیحات تکمیلی کابل‌کشی DC...">{{ old('dc_cabling_notes') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="card section-card section-inverter">
                            <div class="card-body">
                                <div class="section-title">
                                    <i class="fa fa-bolt"></i> ۵. اینورتر
                                </div>
                                <div class="check-item">
                                    <label>اطلاعات اینورتر با پروژه مطابقت دارد؟</label>
                                    <input type="checkbox" name="inverter_info_matches_project" value="1" {{ old('inverter_info_matches_project') ? 'checked' : '' }}>
                                </div>
                                <div class="check-item">
                                    <label>برند مورد تأیید است؟</label>
                                    <input type="checkbox" name="inverter_brand_approved" value="1" {{ old('inverter_brand_approved') ? 'checked' : '' }}>
                                </div>
                                <div class="check-item">
                                    <label>مدل مورد تأیید است؟</label>
                                    <input type="checkbox" name="inverter_model_approved" value="1" {{ old('inverter_model_approved') ? 'checked' : '' }}>
                                </div>
                                <div class="check-item">
                                    <label>شماره سریال صحیح است؟</label>
                                    <input type="checkbox" name="inverter_serial_correct" value="1" {{ old('inverter_serial_correct') ? 'checked' : '' }}>
                                </div>
                                <div class="check-item">
                                    <label>نصب صحیح است؟</label>
                                    <input type="checkbox" name="inverter_proper_installation" value="1" {{ old('inverter_proper_installation') ? 'checked' : '' }}>
                                </div>
                                <div class="check-item">
                                    <label>تهویه مناسب است؟</label>
                                    <input type="checkbox" name="inverter_ventilation_ok" value="1" {{ old('inverter_ventilation_ok') ? 'checked' : '' }}>
                                </div>
                                <div class="check-item">
                                    <label>تنظیمات صحیح است؟</label>
                                    <input type="checkbox" name="inverter_settings_correct" value="1" {{ old('inverter_settings_correct') ? 'checked' : '' }}>
                                </div>
                                <div class="form-group mt-3 mb-0">
                                    <textarea name="inverter_notes" rows="2" class="form-control notes-textarea"
                                              placeholder="توضیحات تکمیلی اینورتر...">{{ old('inverter_notes') }}</textarea>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="col-md-6">

                        <div class="card section-card section-battery">
                            <div class="card-body">
                                <div class="section-title">
                                    <i class="fa fa-battery-full"></i> ۶. باتری (در صورت وجود)
                                </div>
                                <div class="check-item battery-present-item">
                                    <label>سیستم باتری در این پروژه وجود دارد</label>
                                    <input type="checkbox" id="battery_present" name="battery_present" value="1" {{ old('battery_present') ? 'checked' : '' }}>
                                </div>
                                <div id="batteryFields" style="opacity:.5; pointer-events:none;">
                                    <div class="check-item">
                                        <label>برند باتری مورد تأیید است؟</label>
                                        <input type="checkbox" name="battery_brand_approved" value="1" {{ old('battery_brand_approved') ? 'checked' : '' }}>
                                    </div>
                                    <div class="check-item">
                                        <label>مدل مورد تأیید و با اطلاعات پروژه همخوانی دارد؟</label>
                                        <input type="checkbox" name="battery_model_matches_project" value="1" {{ old('battery_model_matches_project') ? 'checked' : '' }}>
                                    </div>
                                    <div class="check-item">
                                        <label>شماره سریال صحیح است؟</label>
                                        <input type="checkbox" name="battery_serial_correct" value="1" {{ old('battery_serial_correct') ? 'checked' : '' }}>
                                    </div>
                                    <div class="check-item">
                                        <label>کابل‌ها صحیح هستند؟</label>
                                        <input type="checkbox" name="battery_cables_correct" value="1" {{ old('battery_cables_correct') ? 'checked' : '' }}>
                                    </div>
                                    <div class="check-item">
                                        <label>BMS عملکرد صحیح دارد؟</label>
                                        <input type="checkbox" name="battery_bms_ok" value="1" {{ old('battery_bms_ok') ? 'checked' : '' }}>
                                    </div>
                                    <div class="check-item">
                                        <label>تهویه مناسب است؟</label>
                                        <input type="checkbox" name="battery_ventilation_ok" value="1" {{ old('battery_ventilation_ok') ? 'checked' : '' }}>
                                    </div>
                                </div>
                                <div class="form-group mt-3 mb-0">
                                    <textarea name="battery_notes" rows="2" class="form-control notes-textarea"
                                              placeholder="توضیحات تکمیلی باتری...">{{ old('battery_notes') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="card section-card section-grounding">
                            <div class="card-body">
                                <div class="section-title">
                                    <i class="fa fa-shield"></i> ۷. حفاظت و ارت
                                </div>
                                <div class="check-item">
                                    <label>سیستم ارت اجرا شده است؟</label>
                                    <input type="checkbox" name="grounding_implemented" value="1" {{ old('grounding_implemented') ? 'checked' : '' }}>
                                </div>
                                <div class="check-item">
                                    <label>مقاومت ارت در محدوده مجاز است؟</label>
                                    <input type="checkbox" name="grounding_resistance_ok" value="1" {{ old('grounding_resistance_ok') ? 'checked' : '' }}>
                                </div>
                                <div class="check-item">
                                    <label>SPD (محافظ رعد و برق) نصب شده است؟</label>
                                    <input type="checkbox" name="spd_installed" value="1" {{ old('spd_installed') ? 'checked' : '' }}>
                                </div>
                                <div class="check-item">
                                    <label>فیوزها مناسب و استاندارد هستند؟</label>
                                    <input type="checkbox" name="fuses_appropriate" value="1" {{ old('fuses_appropriate') ? 'checked' : '' }}>
                                </div>
                                <div class="check-item">
                                    <label>کلیدهای حفاظتی مناسب هستند؟</label>
                                    <input type="checkbox" name="protection_switches_appropriate" value="1" {{ old('protection_switches_appropriate') ? 'checked' : '' }}>
                                </div>
                                <div class="form-group mt-3 mb-0">
                                    <textarea name="grounding_notes" rows="2" class="form-control notes-textarea"
                                              placeholder="توضیحات تکمیلی سیستم ارت و حفاظت...">{{ old('grounding_notes') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="card section-card section-panelbox">
                            <div class="card-body">
                                <div class="section-title">
                                    <i class="fa fa-th-large"></i> ۸. تابلو برق
                                </div>
                                <div class="check-item">
                                    <label>تابلو برق استاندارد است؟</label>
                                    <input type="checkbox" name="electrical_panel_standard" value="1" {{ old('electrical_panel_standard') ? 'checked' : '' }}>
                                </div>
                                <div class="check-item">
                                    <label>سیم‌کشی تابلو مناسب و استاندارد است؟</label>
                                    <input type="checkbox" name="proper_wiring" value="1" {{ old('proper_wiring') ? 'checked' : '' }}>
                                </div>
                                <div class="check-item">
                                    <label>برچسب‌گذاری تابلو انجام شده است؟</label>
                                    <input type="checkbox" name="labeling_done" value="1" {{ old('labeling_done') ? 'checked' : '' }}>
                                </div>
                                <div class="form-group mt-3 mb-0">
                                    <textarea name="electrical_panel_notes" rows="2" class="form-control notes-textarea"
                                              placeholder="توضیحات تکمیلی تابلو برق...">{{ old('electrical_panel_notes') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="card section-card section-perf">
                            <div class="card-body">
                                <div class="section-title">
                                    <i class="fa fa-line-chart"></i> ۹. عملکرد نیروگاه
                                </div>
                                <div class="check-item">
                                    <label>اینورتر بدون خطا و هشدار کار می‌کند؟</label>
                                    <input type="checkbox" name="inverter_no_error" value="1" {{ old('inverter_no_error') ? 'checked' : '' }}>
                                </div>
                                <div class="check-item">
                                    <label>تولید برق نیروگاه طبیعی است؟</label>
                                    <input type="checkbox" name="production_normal" value="1" {{ old('production_normal') ? 'checked' : '' }}>
                                </div>
                                <div class="check-item">
                                    <label>سیستم مانیتورینگ فعال است؟</label>
                                    <input type="checkbox" name="monitoring_active" value="1" {{ old('monitoring_active') ? 'checked' : '' }}>
                                </div>
                                <div class="check-item">
                                    <label>تست عملکرد نیروگاه موفقیت‌آمیز بوده است؟</label>
                                    <input type="checkbox" name="performance_test_passed" value="1" {{ old('performance_test_passed') ? 'checked' : '' }}>
                                </div>
                                <div class="form-group mt-3 mb-0">
                                    <textarea name="performance_notes" rows="2" class="form-control notes-textarea"
                                              placeholder="توضیحات تکمیلی عملکرد نیروگاه...">{{ old('performance_notes') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="card section-card section-safety">
                            <div class="card-body">
                                <div class="section-title">
                                    <i class="fa fa-exclamation-triangle"></i> ۱۰. ایمنی
                                </div>
                                <div class="check-item">
                                    <label>علائم هشدار نصب شده‌اند؟</label>
                                    <input type="checkbox" name="warning_signs_installed" value="1" {{ old('warning_signs_installed') ? 'checked' : '' }}>
                                </div>
                                <div class="check-item">
                                    <label>تجهیزات ایمنی رعایت شده‌اند؟</label>
                                    <input type="checkbox" name="safety_equipment_ok" value="1" {{ old('safety_equipment_ok') ? 'checked' : '' }}>
                                </div>
                                <div class="check-item">
                                    <label>دسترسی ایمن به تجهیزات فراهم است؟</label>
                                    <input type="checkbox" name="safe_access" value="1" {{ old('safe_access') ? 'checked' : '' }}>
                                </div>
                                <div class="check-item">
                                    <label>حفاظت در برابر آب و رطوبت انجام شده است؟</label>
                                    <input type="checkbox" name="moisture_protection" value="1" {{ old('moisture_protection') ? 'checked' : '' }}>
                                </div>
                                <div class="form-group mt-3 mb-0">
                                    <textarea name="safety_notes" rows="2" class="form-control notes-textarea"
                                              placeholder="توضیحات تکمیلی ایمنی...">{{ old('safety_notes') }}</textarea>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            <div class="sticky-submit">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div class="text-muted small" style="font-weight:600;">
                        <i class="fa fa-check-square-o ml-1" style="color:#43A047;"></i>
                        بعد از بررسی کامل موارد بالا، فرم را ثبت کنید.
                    </div>
                    <div>
                        <a href="{{ route('project-inspection.inspections.index') }}"
                           class="btn btn-outline-secondary ml-2" style="border-radius:10px; font-weight:600;">
                            <i class="fa fa-times ml-1"></i> انصراف
                        </a>
                        <button type="submit" class="btn btn-green-gradient btn-lg px-5">
                            <i class="fa fa-save ml-2"></i> ثبت بازرسی
                        </button>
                    </div>
                </div>
            </div>

        </form>

    </div>
</div>
@endsection

@section('script')
<script>
$(document).ready(function () {

    $('#project_selector').select2({
        placeholder: 'انتخاب پروژه...',
        width: '100%',
        language: { noResults: function () { return 'نتیجه‌ای یافت نشد'; } }
    }).on('change', function () {
        var option = $(this).find('option:selected');
        var url = option.data('url');
        if (url) {
            window.location.href = url;
        }
    });

    $('#result').select2({
        placeholder: 'انتخاب نتیجه...',
        minimumResultsForSearch: Infinity,
        width: '100%',
        language: { noResults: function () { return 'نتیجه‌ای یافت نشد'; } }
    }).on('change', function () {
        if ($(this).val() === 'rejected') {
            $('#rejectionReasonBox').slideDown(250);
        } else {
            $('#rejectionReasonBox').slideUp(200);
            $('#rejection_reason').val('');
        }
    });
    if ($('#result').val() === 'rejected') {
        $('#rejectionReasonBox').show();
    }

    function toggleBatteryFields() {
        if ($('#battery_present').is(':checked')) {
            $('#batteryFields').css({ opacity: '1', pointerEvents: 'auto' });
        } else {
            $('#batteryFields').css({ opacity: '.5', pointerEvents: 'none' });
            $('#batteryFields input[type="checkbox"]').prop('checked', false);
        }
    }
    $('#battery_present').on('change', toggleBatteryFields);
    toggleBatteryFields();

});
</script>
@endsection
