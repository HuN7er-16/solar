<div class="card mb-4" style="border-radius:12px;border:2px solid #C5CAE9;box-shadow:0 4px 20px rgba(57,73,171,0.12);">
    <div class="card-header d-flex align-items-center gap-3"
         style="background:linear-gradient(135deg,#3949AB,#283593);border-radius:10px 10px 0 0;border:none;">
        <div style="width:38px;height:38px;background:rgba(255,255,255,0.2);border-radius:10px;display:flex;align-items:center;justify-content:center;">
            <i class="fa fa-flag-checkered text-white"></i>
        </div>
        <h5 class="mb-0 fw-bold text-white">بخش ۱۱ — نتیجه ارزیابی اولیه <span style="opacity:0.7;font-size:13px;">(الزامی)</span></h5>
    </div>
    <div class="card-body p-4">

        @error('assessment_result')
            <div class="alert alert-danger" style="border-radius:8px;">{{ $message }}</div>
        @enderror

        <div class="row g-3 mb-4">
            @php
                $results = [
                    'feasible'          => ['label'=>'قابل اجرا',                      'color'=>'#1B5E20','bg'=>'#E8F5E9','border'=>'#A5D6A7','icon'=>'fa-check-circle'],
                    'feasible_with_fix' => ['label'=>'قابل اجرا با اصلاح',             'color'=>'#F57F17','bg'=>'#FFFDE7','border'=>'#FFE082','icon'=>'fa-exclamation-circle'],
                    'not_feasible'      => ['label'=>'عدم امکان اجرا در شرایط فعلی',   'color'=>'#B71C1C','bg'=>'#FFEBEE','border'=>'#FFCDD2','icon'=>'fa-times-circle'],
                ];
            @endphp

            @foreach($results as $val=>$res)
            <div class="col-md-4">
                <label class="d-block" style="cursor:pointer;">
                    <div class="p-3 text-center"
                         style="border:2px solid {{ old('assessment_result')===$val ? $res['border'] : '#E0E0E0' }};
                                border-radius:10px;background:{{ old('assessment_result')===$val ? $res['bg'] : '#FAFAFA' }};
                                transition:all 0.2s;">
                        <input type="radio" name="assessment_result" value="{{ $val }}"
                               {{ old('assessment_result')===$val?'checked':'' }}
                               style="display:none;" class="result-radio">
                        <i class="fa {{ $res['icon'] }} mb-2" style="font-size:32px;color:{{ $res['color'] }};display:block;"></i>
                        <span class="fw-bold" style="color:{{ $res['color'] }};font-size:15px;">{{ $res['label'] }}</span>
                    </div>
                </label>
            </div>
            @endforeach
        </div>

        <div id="not_feasible_reason_row" style="{{ old('assessment_result')==='not_feasible' ? '' : 'display:none;' }}">
            <label class="form-label fw-bold mb-2" style="color:#B71C1C;">
                <i class="fa fa-exclamation-triangle ms-1"></i>
                علت عدم امکان اجرا: <span class="text-danger">*</span>
            </label>
            <textarea name="not_feasible_reason" rows="3"
                      class="form-control @error('not_feasible_reason') is-invalid @enderror"
                      style="border-radius:8px;"
                      placeholder="لطفاً دلیل عدم امکان اجرای پروژه را به‌طور کامل توضیح دهید...">{{ old('not_feasible_reason') }}</textarea>
            @error('not_feasible_reason')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        </div>

    </div>
</div>


