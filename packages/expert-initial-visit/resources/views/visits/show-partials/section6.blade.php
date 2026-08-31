@php
    $v = $expertInitialVisit;
    $elecLabels  = ['single_phase'=>'تک‌فاز','three_phase'=>'سه‌فاز'];
    $condLabels  = ['suitable'=>'مناسب','needs_fix'=>'نیاز به اصلاح','unsuitable'=>'نامناسب','needs_review'=>'نیازمند بررسی'];
    $instLabels  = ['suitable'=>'مناسب','needs_fix'=>'نیاز به اصلاح','unsuitable'=>'نامناسب'];
@endphp
<div class="card mb-4" style="border-radius:12px;border:none;box-shadow:0 4px 20px rgba(0,0,0,0.08);">
    <div class="card-header" style="background:linear-gradient(135deg,#E8EAF6,#C5CAE9);border-radius:12px 12px 0 0;border:none;">
        <h5 class="mb-0 fw-bold" style="color:#283593;"><i class="fa fa-bolt ms-2"></i>بخش ۶ — برق و زیرساخت</h5>
    </div>
    <div class="card-body p-4">
        <div class="row g-3">
            <div class="col-md-3"><label class="text-muted small d-block">نوع برق</label><strong>{{ $elecLabels[$v->electricity_type] ?? '-' }}</strong></div>
            <div class="col-md-3"><label class="text-muted small d-block">ظرفیت انشعاب</label><strong>{{ $v->connection_capacity_ampere ? $v->connection_capacity_ampere.' آمپر' : '-' }}</strong></div>
            <div class="col-md-3"><label class="text-muted small d-block">تابلو قابل دسترسی</label><strong>{{ $v->main_panel_accessible ? 'بله' : 'خیر' }}</strong></div>
            <div class="col-md-3"><label class="text-muted small d-block">وضعیت تابلو</label><strong>{{ $condLabels[$v->main_panel_condition] ?? '-' }}</strong></div>
            <div class="col-md-3"><label class="text-muted small d-block">تأسیسات برق</label><strong>{{ $instLabels[$v->electrical_installation_condition] ?? '-' }}</strong></div>
            <div class="col-md-3"><label class="text-muted small d-block">اتصال به شبکه</label><strong>{{ $v->grid_connection_possible ? 'ممکن' : 'ممکن نیست' }}</strong></div>
            <div class="col-md-3"><label class="text-muted small d-block">نیاز به اصلاح برق</label><strong>{{ $v->electrical_fix_needed ? 'بله' : 'خیر' }}</strong></div>
            @if($v->electrical_notes)
            <div class="col-12"><label class="text-muted small d-block">توضیحات</label><p class="mb-0">{{ $v->electrical_notes }}</p></div>
            @endif
        </div>
    </div>
</div>
