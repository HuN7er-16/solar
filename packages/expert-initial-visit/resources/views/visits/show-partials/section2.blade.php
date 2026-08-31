@php
    $v = $expertInitialVisit;
    $accessLabels = ['easy'=>'آسان','medium'=>'متوسط','hard'=>'دشوار'];
@endphp
<div class="card mb-4" style="border-radius:12px;border:none;box-shadow:0 4px 20px rgba(0,0,0,0.08);">
    <div class="card-header" style="background:linear-gradient(135deg,#E8EAF6,#C5CAE9);border-radius:12px 12px 0 0;border:none;">
        <h5 class="mb-0 fw-bold" style="color:#283593;"><i class="fa fa-map-marker ms-2"></i>بخش ۲ — احراز محل</h5>
    </div>
    <div class="card-body p-4">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="text-muted small d-block">مطابقت محل</label>
                <strong>{{ $v->location_matches ? 'بله' : 'خیر' }}</strong>
                @if(!$v->location_matches && $v->actual_address)
                    <p class="text-muted small mb-0">آدرس واقعی: {{ $v->actual_address }}</p>
                @endif
            </div>
            <div class="col-md-4">
                <label class="text-muted small d-block">تأیید فیزیکی محل</label>
                <strong>{{ $v->location_physically_confirmed ? 'بله' : 'خیر' }}</strong>
            </div>
            <div class="col-md-4">
                <label class="text-muted small d-block">دسترسی به محل</label>
                <strong>{{ $accessLabels[$v->location_access] ?? $v->location_access }}</strong>
            </div>
        </div>
    </div>
</div>
