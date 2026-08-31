@php
    $v = $expertInitialVisit;
    $fixTypeLabels = [
        'structure_fix'       => 'اصلاح سازه',
        'reinforcement'       => 'مقاوم‌سازی',
        'panel_fix'           => 'اصلاح تابلو برق',
        'electrical_fix'      => 'اصلاح تأسیسات برق',
        'cable_route_fix'     => 'اصلاح مسیر کابل‌کشی',
        'equipment_space'     => 'ایجاد محل نصب تجهیزات',
        'obstacle_removal'    => 'رفع موانع فیزیکی',
        'capacity_adjustment' => 'اصلاح ظرفیت پروژه',
        'other'               => 'سایر',
    ];
@endphp
<div class="card mb-4" style="border-radius:12px;border:none;box-shadow:0 4px 20px rgba(0,0,0,0.08);">
    <div class="card-header" style="background:linear-gradient(135deg,#E8EAF6,#C5CAE9);border-radius:12px 12px 0 0;border:none;">
        <h5 class="mb-0 fw-bold" style="color:#283593;"><i class="fa fa-cogs ms-2"></i>بخش ۱۰ — اصلاحات پیش از اجرا</h5>
    </div>
    <div class="card-body p-4">
        @if(!$v->pre_execution_fix_needed)
            <p class="text-muted mb-0">نیاز به اصلاح خاصی پیش از اجرا وجود ندارد.</p>
        @else
            @if($v->pre_execution_fix_types)
            <div class="d-flex flex-wrap gap-2 mb-3">
                @foreach((array)$v->pre_execution_fix_types as $ft)
                    <span class="badge" style="background:#FFF9C4;color:#F57F17;padding:6px 12px;border-radius:8px;font-size:13px;">
                        <i class="fa fa-wrench ms-1" style="font-size:11px;"></i>{{ $fixTypeLabels[$ft] ?? $ft }}
                    </span>
                @endforeach
            </div>
            @endif
            @if($v->pre_execution_fix_description)
                <p class="mb-0" style="white-space:pre-wrap;">{{ $v->pre_execution_fix_description }}</p>
            @endif
        @endif
    </div>
</div>
