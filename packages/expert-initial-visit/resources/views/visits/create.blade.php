@extends('behin-layouts.app')

@section('content')
<div class="container-fluid" style="direction:rtl;text-align:right;">

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert"
             style="border-radius:12px;border:none;background:linear-gradient(135deg,#FFCDD2,#EF9A9A);color:#B71C1C;">
            <strong><i class="fa fa-exclamation-triangle ms-2"></i>لطفاً موارد زیر را تکمیل کنید:</strong>
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

    <style>
    /* ── نرمال‌سازی input/select/textarea در فرم بازدید ── */
    #visitForm .form-control {
        border: 1px solid #CFD8DC !important;
        border-radius: 8px !important;
        padding: 7px 12px !important;
        font-size: 14px;
        line-height: 1.5;
        height: auto;
        box-shadow: none;
        transition: border-color 0.2s;
    }
    #visitForm .form-control:focus {
        border-color: #7986CB !important;
        box-shadow: 0 0 0 3px rgba(121,134,203,0.15) !important;
    }
    #visitForm .form-control.is-invalid {
        border-color: #E53935 !important;
    }
    #visitForm select.form-control {
        padding-left: 8px !important;
    }
    #visitForm textarea.form-control {
        resize: vertical;
    }
    /* فیلد readonly ظرفیت متقاضی */
    #visitForm .form-control[readonly] {
        background: #F5F5F5 !important;
        cursor: not-allowed;
    }
</style>

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
document.addEventListener('DOMContentLoaded', function () {

    // ─── toggle helpers ───────────────────────────────────────────
    function toggleOnRadio(name, triggerValue, targetId) {
        var radios = document.querySelectorAll('input[name="' + name + '"]');
        var target = document.getElementById(targetId);
        if (!target) return;
        radios.forEach(function(radio) {
            radio.addEventListener('change', function() {
                target.style.display = this.value === triggerValue ? '' : 'none';
            });
            if (radio.checked) {
                target.style.display = radio.value === triggerValue ? '' : 'none';
            }
        });
    }

    // ─── بخش ۲: عدم مطابقت محل ───────────────────────────────────
    toggleOnRadio('location_matches', '0', 'actual_address_row');

    // ─── بخش ۳: نوع محل (سایر) ───────────────────────────────────
    var locTypeSelect = document.getElementById('loc_type_select');
    if (locTypeSelect) {
        locTypeSelect.addEventListener('change', function() {
            var row = document.getElementById('loc_type_other_row');
            if (row) row.style.display = this.value === 'other' ? '' : 'none';
        });
    }

    // ─── بخش ۳: مانع فیزیکی ──────────────────────────────────────
    toggleOnRadio('physical_obstacle_exists', '1', 'obstacle_types_row');

    // ─── بخش ۷: بار اضطراری ──────────────────────────────────────
    toggleOnRadio('has_emergency_load', '1', 'emergency_equipment_table');

    // ─── بخش ۹: ظرفیت پیشنهادی باتری ────────────────────────────
    toggleOnRadio('battery_required', '1', 'battery_kwh_row');

    // ─── بخش ۱۰: اصلاحات پیش از اجرا ────────────────────────────
    toggleOnRadio('pre_execution_fix_needed', '1', 'pre_execution_fix_details');

    // ─── بخش ۱۱: نتیجه ارزیابی ───────────────────────────────────
    document.querySelectorAll('input[name="assessment_result"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            var row = document.getElementById('not_feasible_reason_row');
            if (row) row.style.display = this.value === 'not_feasible' ? '' : 'none';

            // استایل کارت‌های انتخاب نتیجه
            document.querySelectorAll('.result-radio').forEach(function(r) {
                var card = r.closest('label').querySelector('div');
                card.style.borderColor = '#E0E0E0';
                card.style.background  = '#FAFAFA';
            });
            var colors = {
                'feasible':          {border:'#A5D6A7', bg:'#E8F5E9'},
                'feasible_with_fix': {border:'#FFE082', bg:'#FFFDE7'},
                'not_feasible':      {border:'#FFCDD2', bg:'#FFEBEE'},
            };
            var c = colors[this.value];
            if (c) {
                var card = this.closest('label').querySelector('div');
                if (card) { card.style.borderColor = c.border; card.style.background = c.bg; }
            }
        });
    });

    // ─── بخش ۷: محاسبه توان کل جدول تجهیزات ─────────────────────
    function recalcTotal(row) {
        var qty   = parseFloat(row.querySelector('.eq-qty')?.value)   || 0;
        var power = parseFloat(row.querySelector('.eq-power')?.value) || 0;
        var total = row.querySelector('.eq-total');
        if (total) total.value = (qty && power) ? (qty * power).toFixed(2) : '';
    }

    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('eq-qty') || e.target.classList.contains('eq-power')) {
            recalcTotal(e.target.closest('tr'));
        }
    });

    // حذف ردیف تجهیز
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-row')) {
            var row = e.target.closest('tr');
            if (document.querySelectorAll('#equipmentBody tr').length > 1) row.remove();
        }
    });

    // افزودن ردیف جدید تجهیز
    var addEquipmentBtn = document.getElementById('addEquipmentRow');
    if (addEquipmentBtn) {
        addEquipmentBtn.addEventListener('click', function() {
            var tbody = document.getElementById('equipmentBody');
            var tr = document.createElement('tr');
            tr.innerHTML =
                '<td><input type="text"   name="equipment_name[]"              class="form-control form-control-sm" style="border-radius:6px;"></td>' +
                '<td><input type="number" name="equipment_quantity[]"    value="1" min="1" class="form-control form-control-sm eq-qty" style="border-radius:6px;"></td>' +
                '<td><input type="number" name="equipment_power_watts[]" step="0.01" min="0" class="form-control form-control-sm eq-power" style="border-radius:6px;" placeholder="W"></td>' +
                '<td><input type="number" name="equipment_total_power_watts[]" readonly class="form-control form-control-sm eq-total" style="border-radius:6px;background:#F5F5F5;"></td>' +
                '<td><input type="number" name="equipment_usage_hours[]" min="0" class="form-control form-control-sm" style="border-radius:6px;" placeholder="ساعت"></td>' +
                '<td class="text-center"><input type="checkbox" name="equipment_is_critical[]" value="1" class="form-check-input" style="width:20px;height:20px;"></td>' +
                '<td><input type="text"   name="equipment_notes[]"             class="form-control form-control-sm" style="border-radius:6px;"></td>' +
                '<td class="text-center"><button type="button" class="btn btn-sm btn-danger remove-row" style="border-radius:6px;padding:2px 8px;"><i class="fa fa-times"></i></button></td>';
            tbody.appendChild(tr);
        });
    }

    // ─── بخش ۱۳: تصاویر ─────────────────────────────────────────
    function updateRemovePhotoButtons() {
        var rows = document.querySelectorAll('.photo-row');
        rows.forEach(function(row) {
            var btn = row.querySelector('.remove-photo-row');
            if (btn) btn.style.display = rows.length > 1 ? '' : 'none';
        });
    }

    var addPhotoBtn = document.getElementById('addPhotoRow');
    if (addPhotoBtn) {
        addPhotoBtn.addEventListener('click', function() {
            var container = document.getElementById('photosContainer');
            var row = container.querySelector('.photo-row').cloneNode(true);
            row.querySelectorAll('input').forEach(function(i) { i.value = ''; });
            row.querySelector('.remove-photo-row').style.display = '';
            container.appendChild(row);
            updateRemovePhotoButtons();
        });
    }

    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-photo-row')) {
            e.target.closest('.photo-row').remove();
            updateRemovePhotoButtons();
        }
    });

});
</script>
@endsection
