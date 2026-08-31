@php
    $v = $expertInitialVisit;
    $loadLabels = ['suitable'=>'مناسب','needs_reinforcement'=>'نیاز به تقویت','unsuitable'=>'نامناسب','needs_expert_review'=>'نیازمند بررسی تخصصی'];
    $riskLabels = ['wind'=>'باد','humidity'=>'رطوبت','corrosion'=>'خوردگی','fall_risk'=>'خطر سقوط','hard_access'=>'دسترسی دشوار','fire_risk'=>'خطر آتش‌سوزی','other'=>'سایر'];
    $riskLevelLabels = ['low'=>'کم','medium'=>'متوسط','high'=>'زیاد'];
@endphp
<div class="card mb-4" style="border-radius:12px;border:none;box-shadow:0 4px 20px rgba(0,0,0,0.08);">
    <div class="card-header" style="background:linear-gradient(135deg,#E8EAF6,#C5CAE9);border-radius:12px 12px 0 0;border:none;">
        <h5 class="mb-0 fw-bold" style="color:#283593;"><i class="fa fa-building ms-2"></i>بخش ۵ — سازه و ایمنی</h5>
    </div>
    <div class="card-body p-4">
        <div class="row g-3">
            <div class="col-md-4"><label class="text-muted small d-block">تحمل بار سازه</label><strong>{{ $loadLabels[$v->structure_load_capacity] ?? '-' }}</strong></div>
            <div class="col-md-2"><label class="text-muted small d-block">مقاوم‌سازی</label><strong>{{ $v->reinforcement_needed ? 'بله' : 'خیر' }}</strong></div>
            <div class="col-md-2"><label class="text-muted small d-block">سازه خاص</label><strong>{{ $v->special_structure_needed ? 'بله' : 'خیر' }}</strong></div>
            <div class="col-md-4"><label class="text-muted small d-block">سطح ریسک کلی</label>
                @php $rl = $riskLevelLabels[$v->overall_risk_level] ?? '-'; $rlColor = ['low'=>'#1B5E20','medium'=>'#F57F17','high'=>'#B71C1C'][$v->overall_risk_level] ?? '#616161'; @endphp
                <strong style="color:{{ $rlColor }};">{{ $rl }}</strong>
            </div>
            @if($v->site_risks)
            <div class="col-12">
                <label class="text-muted small d-block">ریسک‌های محل</label>
                <div class="d-flex flex-wrap gap-2">
                    @foreach((array)$v->site_risks as $risk)
                        <span class="badge" style="background:#FFEBEE;color:#B71C1C;padding:5px 10px;border-radius:6px;">{{ $riskLabels[$risk] ?? $risk }}</span>
                    @endforeach
                </div>
            </div>
            @endif
            @if($v->structure_notes)
            <div class="col-12"><label class="text-muted small d-block">توضیحات</label><p class="mb-0">{{ $v->structure_notes }}</p></div>
            @endif
        </div>
    </div>
</div>
