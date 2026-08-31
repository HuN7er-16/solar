@php
    $v = $expertInitialVisit;
    $locLabels    = ['yes'=>'بله','no'=>'خیر','with_fix'=>'با اصلاح'];
    $batLocLabels = ['yes'=>'بله','no'=>'خیر','with_fix'=>'با اصلاح','not_needed'=>'موردنیاز نیست'];
@endphp
<div class="card mb-4" style="border-radius:12px;border:none;box-shadow:0 4px 20px rgba(0,0,0,0.08);">
    <div class="card-header" style="background:linear-gradient(135deg,#E8EAF6,#C5CAE9);border-radius:12px 12px 0 0;border:none;">
        <h5 class="mb-0 fw-bold" style="color:#283593;"><i class="fa fa-wrench ms-2"></i>بخش ۸ — محل نصب تجهیزات</h5>
    </div>
    <div class="card-body p-4">
        <div class="row g-3">
            <div class="col-md-3"><label class="text-muted small d-block">محل اینورتر</label><strong>{{ $locLabels[$v->inverter_location] ?? '-' }}</strong></div>
            <div class="col-md-3"><label class="text-muted small d-block">محل باتری</label><strong>{{ $batLocLabels[$v->battery_location] ?? '-' }}</strong></div>
            <div class="col-md-3"><label class="text-muted small d-block">تهویه مناسب</label><strong>{{ $v->equipment_ventilation_ok ? 'بله' : 'خیر' }}</strong></div>
            <div class="col-md-3"><label class="text-muted small d-block">مسیر کابل‌کشی</label><strong>{{ $v->cable_route_ok ? 'مناسب' : 'نامناسب' }}</strong></div>
            <div class="col-md-3"><label class="text-muted small d-block">نیاز به فضای جدید</label><strong>{{ $v->new_equipment_space_needed ? 'بله' : 'خیر' }}</strong></div>
            @if($v->equipment_location_notes)
            <div class="col-12"><label class="text-muted small d-block">توضیحات</label><p class="mb-0">{{ $v->equipment_location_notes }}</p></div>
            @endif
        </div>
    </div>
</div>
