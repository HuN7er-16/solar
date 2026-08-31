@php $v = $expertInitialVisit; @endphp
<div class="card mb-4" style="border-radius:12px;border:2px solid #C5CAE9;box-shadow:0 4px 20px rgba(57,73,171,0.12);">
    <div class="card-header" style="background:linear-gradient(135deg,#3949AB,#283593);border-radius:10px 10px 0 0;border:none;">
        <h5 class="mb-0 fw-bold text-white"><i class="fa fa-flag-checkered ms-2"></i>بخش ۱۱ — نتیجه ارزیابی اولیه</h5>
    </div>
    <div class="card-body p-4">
        <div class="d-flex align-items-center gap-3 mb-3">
            <span style="font-size:18px;font-weight:700;color:#37474F;">نتیجه:</span>
            {!! $v->assessment_result_label !!}
        </div>
        @if($v->assessment_result === 'not_feasible' && $v->not_feasible_reason)
            <div class="p-3 mt-2" style="background:#FFEBEE;border-radius:8px;border-right:4px solid #EF5350;">
                <label class="fw-bold d-block mb-1" style="color:#B71C1C;"><i class="fa fa-exclamation-triangle ms-1"></i>علت عدم امکان اجرا:</label>
                <p class="mb-0" style="white-space:pre-wrap;">{{ $v->not_feasible_reason }}</p>
            </div>
        @endif
    </div>
</div>
