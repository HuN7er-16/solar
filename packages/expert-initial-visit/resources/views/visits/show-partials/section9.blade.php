@php $v = $expertInitialVisit; @endphp
<div class="card mb-4" style="border-radius:12px;border:2px solid #A5D6A7;box-shadow:0 4px 20px rgba(76,175,80,0.12);">
    <div class="card-header" style="background:linear-gradient(135deg,#E8F5E9,#C8E6C9);border-radius:10px 10px 0 0;border:none;">
        <h5 class="mb-0 fw-bold" style="color:#1B5E20;"><i class="fa fa-tachometer ms-2"></i>بخش ۹ — ظرفیت پیشنهادی</h5>
    </div>
    <div class="card-body p-4">
        <div class="row g-4">
            <div class="col-md-3 text-center p-3" style="background:#FFF3E0;border-radius:10px;">
                <label class="text-muted small d-block mb-1">ظرفیت درخواستی متقاضی</label>
                <h4 class="mb-0 fw-bold" style="color:#E65100;">{{ $v->applicant_requested_capacity_kw ?? '-' }} <small>kW</small></h4>
            </div>
            <div class="col-md-3 text-center p-3" style="background:#E3F2FD;border-radius:10px;">
                <label class="text-muted small d-block mb-1">ظرفیت قابل نصب بر اساس فضا</label>
                <h4 class="mb-0 fw-bold" style="color:#1565C0;">{{ $v->installable_capacity_kw ?? '-' }} <small>kW</small></h4>
            </div>
            <div class="col-md-3 text-center p-3" style="background:#E8F5E9;border-radius:10px;border:2px solid #A5D6A7;">
                <label class="text-muted small d-block mb-1">ظرفیت پیشنهادی کارشناس</label>
                <h4 class="mb-0 fw-bold" style="color:#1B5E20;">{{ $v->expert_proposed_capacity_kw }} <small>kW</small></h4>
            </div>
            <div class="col-md-3 text-center p-3" style="background:#F3E5F5;border-radius:10px;">
                <label class="text-muted small d-block mb-1">ظرفیت اینورتر پیشنهادی</label>
                <h4 class="mb-0 fw-bold" style="color:#6A1B9A;">{{ $v->expert_proposed_inverter_kw ?? '-' }} <small>kW</small></h4>
            </div>
            @if($v->battery_required)
            <div class="col-md-3 text-center p-3" style="background:#E0F7FA;border-radius:10px;">
                <label class="text-muted small d-block mb-1">ظرفیت باتری پیشنهادی</label>
                <h4 class="mb-0 fw-bold" style="color:#006064;">{{ $v->expert_proposed_battery_kwh ?? '-' }} <small>kWh</small></h4>
            </div>
            @endif
            @if($v->capacity_difference_reason)
            <div class="col-12">
                <label class="text-muted small d-block">علت تفاوت ظرفیت:</label>
                <p class="mb-0">{{ $v->capacity_difference_reason }}</p>
            </div>
            @endif
        </div>
    </div>
</div>
