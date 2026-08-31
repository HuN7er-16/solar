@php
    $v = $expertInitialVisit;
    $surfaceLabels    = ['concrete'=>'بتنی','metal'=>'فلزی','tile'=>'شیروانی','soil'=>'خاکی','other'=>'سایر'];
    $orientLabels     = ['horizontal'=>'افقی','sloped'=>'شیب‌دار'];
    $dirLabels        = ['south'=>'جنوب','southeast'=>'جنوب‌شرق','southwest'=>'جنوب‌غرب','east'=>'شرق','west'=>'غرب','north'=>'شمال','other'=>'سایر'];
    $shadingLabels    = ['none'=>'ندارد','low'=>'کم','medium'=>'متوسط','high'=>'زیاد'];
    $conditionLabels  = ['suitable'=>'مناسب','unsuitable'=>'نامناسب','suitable_with_fix'=>'مناسب با اصلاح'];
    $shadingSrcLabels = ['building'=>'ساختمان','tree'=>'درخت','adjacent'=>'ساختمان مجاور','pole'=>'دکل/تأسیسات','other'=>'سایر'];
@endphp
<div class="card mb-4" style="border-radius:12px;border:none;box-shadow:0 4px 20px rgba(0,0,0,0.08);">
    <div class="card-header" style="background:linear-gradient(135deg,#E8EAF6,#C5CAE9);border-radius:12px 12px 0 0;border:none;">
        <h5 class="mb-0 fw-bold" style="color:#283593;"><i class="fa fa-th-large ms-2"></i>بخش ۴ — سطح نصب پنل</h5>
    </div>
    <div class="card-body p-4">
        <div class="row g-3">
            <div class="col-md-3"><label class="text-muted small d-block">نوع سطح</label><strong>{{ $surfaceLabels[$v->surface_type] ?? '-' }}</strong></div>
            <div class="col-md-3"><label class="text-muted small d-block">وضعیت سطح</label><strong>{{ $orientLabels[$v->surface_orientation] ?? '-' }}</strong></div>
            <div class="col-md-3"><label class="text-muted small d-block">جهت نصب</label><strong>{{ $dirLabels[$v->panel_direction] ?? '-' }}</strong></div>
            <div class="col-md-3"><label class="text-muted small d-block">سایه‌اندازی</label><strong>{{ $shadingLabels[$v->shading_level] ?? '-' }}</strong></div>
            <div class="col-md-4"><label class="text-muted small d-block">وضعیت سطح برای نصب</label><strong>{{ $conditionLabels[$v->surface_condition] ?? '-' }}</strong></div>
            @if($v->shading_sources)
            <div class="col-md-8">
                <label class="text-muted small d-block">منابع سایه‌اندازی</label>
                <div class="d-flex flex-wrap gap-2">
                    @foreach((array)$v->shading_sources as $src)
                        <span class="badge" style="background:#FFF9C4;color:#F57F17;padding:5px 10px;border-radius:6px;">{{ $shadingSrcLabels[$src] ?? $src }}</span>
                    @endforeach
                </div>
            </div>
            @endif
            @if($v->surface_notes)
            <div class="col-12"><label class="text-muted small d-block">توضیحات</label><p class="mb-0">{{ $v->surface_notes }}</p></div>
            @endif
        </div>
    </div>
</div>
