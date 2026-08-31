@extends('behin-layouts.app')

@section('content')
<div class="container-fluid" style="direction:rtl;text-align:right;">

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert"
             style="border-radius:12px;border:none;background:linear-gradient(135deg,#FFCDD2,#EF9A9A);color:#B71C1C;">
            <strong><i class="fa fa-exclamation-triangle ms-2"></i>لطفاً خطاهای زیر را برطرف کنید:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" style="float:left;"></button>
        </div>
    @endif

    {{-- Header --}}
    <div class="mb-4 p-4 text-white"
         style="background:linear-gradient(135deg,#5C6BC0 0%,#3949AB 100%);border-radius:12px;box-shadow:0 4px 20px rgba(57,73,171,0.25);">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h3 class="mb-1 fw-bold"><i class="fa fa-clipboard-list ms-2"></i>فرم ارزیابی و بازدید اولیه محل</h3>
                <p class="mb-0 opacity-90">کد پیگیری: <span style="font-family:monospace;">{{ $solarPlantRequest->unique_code }}</span></p>
            </div>
            <a href="{{ route('expert-initial-visit.index') }}" class="btn btn-light"
               style="border-radius:10px;color:#3949AB;font-weight:600;">
                <i class="fa fa-arrow-right ms-1"></i> بازگشت
            </a>
        </div>
    </div>

    <form method="POST" action="{{ route('expert-initial-visit.store') }}" enctype="multipart/form-data" id="visitForm">
        @csrf
        <input type="hidden" name="solar_plant_request_id" value="{{ $solarPlantRequest->id }}">


        {{-- ===== بخش ۱: اطلاعات درخواست (فقط نمایش) ===== --}}
        @include('expert-initial-visit::visits.partials.section-request-info')

        {{-- ===== بخش ۲: احراز محل ===== --}}
        @include('expert-initial-visit::visits.partials.section-location-verify')

        {{-- ===== بخش ۳: وضعیت کلی محل ===== --}}
        @include('expert-initial-visit::visits.partials.section-site-general')

        {{-- ===== بخش ۴: سطح نصب پنل ===== --}}
        @include('expert-initial-visit::visits.partials.section-surface')

        {{-- ===== بخش ۵: سازه و ایمنی ===== --}}
        @include('expert-initial-visit::visits.partials.section-structure')

        {{-- ===== بخش ۶: برق و زیرساخت ===== --}}
        @include('expert-initial-visit::visits.partials.section-electrical')

        {{-- ===== بخش ۷: بار اضطراری ===== --}}
        @include('expert-initial-visit::visits.partials.section-emergency-load')

        {{-- ===== بخش ۸: محل نصب تجهیزات ===== --}}
        @include('expert-initial-visit::visits.partials.section-equipment-location')

        {{-- ===== بخش ۹: ظرفیت پیشنهادی ===== --}}
        @include('expert-initial-visit::visits.partials.section-capacity')

        {{-- ===== بخش ۱۰: اصلاحات پیش از اجرا ===== --}}
        @include('expert-initial-visit::visits.partials.section-pre-execution')

        {{-- ===== بخش ۱۱: نتیجه ارزیابی ===== --}}
        @include('expert-initial-visit::visits.partials.section-result')

        {{-- ===== بخش ۱۲: جمع‌بندی ===== --}}
        @include('expert-initial-visit::visits.partials.section-summary')

        {{-- ===== بخش ۱۳: تصاویر ===== --}}
        @include('expert-initial-visit::visits.partials.section-photos')

        {{-- ===== بخش ۱۴: ثبت نهایی ===== --}}
        <div class="card mb-4" style="border-radius:12px;border:none;box-shadow:0 4px 20px rgba(0,0,0,0.08);">
            <div class="card-body p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <p class="mb-1 fw-semibold" style="color:#283593;">
                        <i class="fa fa-info-circle ms-1"></i>
                        پس از ارسال، گزارش برای راهبر سامانه قابل مشاهده خواهد بود.
                    </p>
                    <small class="text-muted">تاریخ و ساعت ارسال به‌صورت خودکار ثبت می‌شود.</small>
                </div>
                <button type="submit" class="btn btn-lg text-white"
                        style="background:linear-gradient(135deg,#3949AB,#283593);border-radius:12px;font-weight:700;padding:14px 48px;box-shadow:0 4px 20px rgba(40,53,147,0.35);">
                    <i class="fa fa-paper-plane ms-2"></i> ثبت و ارسال گزارش بازدید اولیه
                </button>
            </div>
        </div>

    </form>
</div>
@endsection

@section('script')
<script>
// نمایش/پنهان کردن فیلدهای شرطی
document.addEventListener('DOMContentLoaded', function () {

    // عدم مطابقت محل
    toggleOnRadio('location_matches', '0', 'actual_address_row');

    // مانع فیزیکی
    toggleOnRadio('physical_obstacle_exists', '1', 'obstacle_types_row');

    // بار اضطراری
    toggleOnRadio('has_emergency_load', '1', 'emergency_equipment_table');

    // نتیجه عدم امکان
    document.querySelectorAll('input[name="assessment_result"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            var row = document.getElementById('not_feasible_reason_row');
            if (row) row.style.display = this.value === 'not_feasible' ? '' : 'none';
        });
    });

    // اصلاحات پیش از اجرا
    toggleOnRadio('pre_execution_fix_needed', '1', 'pre_execution_fix_details');

    // ظرفیت پیشنهادی باتری
    toggleOnRadio('battery_required', '1', 'battery_kwh_row');
});

function toggleOnRadio(name, triggerValue, targetId) {
    var radios = document.querySelectorAll('input[name="' + name + '"]');
    var target = document.getElementById(targetId);
    if (!target) return;

    radios.forEach(function(radio) {
        radio.addEventListener('change', function() {
            target.style.display = this.value === triggerValue ? '' : 'none';
        });
        if (radio.checked && radio.value === triggerValue) {
            target.style.display = '';
        } else if (radio.checked) {
            target.style.display = 'none';
        }
    });
}
</script>
@endsection
