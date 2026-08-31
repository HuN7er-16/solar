@php
    $v = $expertInitialVisit;
    $locTypeLabels = ['flat_roof'=>'پشت‌بام مسطح','sloped_roof'=>'پشت‌بام شیب‌دار','ground'=>'زمین','parking_canopy'=>'پارکینگ/سایبان','other'=>'سایر'];
    $obstacleLabels = ['building'=>'ساختمان','tree'=>'درخت','pole'=>'دکل/تأسیسات','adjacent_building'=>'ساختمان مجاور','existing_equipment'=>'تجهیزات موجود','access_limit'=>'محدودیت دسترسی','other'=>'سایر'];
@endphp
<div class="card mb-4" style="border-radius:12px;border:none;box-shadow:0 4px 20px rgba(0,0,0,0.08);">
    <div class="card-header" style="background:linear-gradient(135deg,#E8EAF6,#C5CAE9);border-radius:12px 12px 0 0;border:none;">
        <h5 class="mb-0 fw-bold" style="color:#283593;"><i class="fa fa-home ms-2"></i>بخش ۳ — وضعیت کلی محل</h5>
    </div>
    <div class="card-body p-4">
        <div class="row g-3">
            <div class="col-md-3"><label class="text-muted small d-block">فضای مناسب</label><strong>{{ $v->suitable_space_exists ? 'بله' : 'خیر' }}</strong></div>
            <div class="col-md-3"><label class="text-muted small d-block">نوع محل نصب</label><strong>{{ $locTypeLabels[$v->installation_location_type] ?? $v->installation_location_type }}{{ $v->installation_location_type === 'other' && $v->installation_location_type_other ? ' — '.$v->installation_location_type_other : '' }}</strong></div>
            <div class="col-md-3"><label class="text-muted small d-block">مساحت کل</label><strong>{{ $v->total_area_sqm ? $v->total_area_sqm.' متر مربع' : '-' }}</strong></div>
            <div class="col-md-3"><label class="text-muted small d-block">مساحت قابل استفاده</label><strong>{{ $v->usable_area_sqm ? $v->usable_area_sqm.' متر مربع' : '-' }}</strong></div>
            <div class="col-md-4"><label class="text-muted small d-block">مانع فیزیکی</label><strong>{{ $v->physical_obstacle_exists ? 'دارد' : 'ندارد' }}</strong></div>
            @if($v->physical_obstacle_exists && $v->obstacle_types)
            <div class="col-md-8">
                <label class="text-muted small d-block">انواع موانع</label>
                <div class="d-flex flex-wrap gap-2">
                    @foreach((array)$v->obstacle_types as $obs)
                        <span class="badge" style="background:#FFCDD2;color:#B71C1C;padding:5px 10px;border-radius:6px;">{{ $obstacleLabels[$obs] ?? $obs }}</span>
                    @endforeach
                </div>
                @if($v->obstacle_notes)<p class="text-muted small mt-1 mb-0">{{ $v->obstacle_notes }}</p>@endif
            </div>
            @endif
        </div>
    </div>
</div>
