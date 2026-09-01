<div class="card mb-4" style="border-radius:12px;border:none;box-shadow:0 4px 20px rgba(0,0,0,0.08);">
    <div class="card-header d-flex align-items-center gap-3"
         style="background:linear-gradient(135deg,#E8EAF6,#C5CAE9);border-radius:12px 12px 0 0;border:none;">
        <div style="width:38px;height:38px;background:linear-gradient(135deg,#7986CB,#5C6BC0);border-radius:10px;display:flex;align-items:center;justify-content:center;">
            <i class="fa fa-cogs text-white"></i>
        </div>
        <h5 class="mb-0 fw-bold" style="color:#283593;">بخش ۱۰ — اصلاحات و الزامات پیش از اجرا</h5>
    </div>
    <div class="card-body p-4">

        <div class="mb-4">
            <label class="form-label fw-semibold mb-2">آیا پیش از اجرا نیاز به اصلاح یا اقدام خاصی وجود دارد؟ <span class="text-danger">*</span></label>
            <div class="d-flex gap-4 p-3" style="background:#F5F5F5;border-radius:8px;">
                @foreach(['1'=>'بله','0'=>'خیر'] as $v=>$l)
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="pre_execution_fix_needed" value="{{ $v }}"
                           id="prefix_{{ $v }}" {{ old('pre_execution_fix_needed','0')===$v?'checked':'' }}
                           style="width:18px;height:18px;">
                    <label class="form-check-label fw-semibold me-2" for="prefix_{{ $v }}"
                           style="color:{{ $v==='0'?'#2E7D32':'#C62828' }};">{{ $l }}</label>
                </div>
                @endforeach
            </div>
        </div>

        <div id="pre_execution_fix_details" style="{{ old('pre_execution_fix_needed')==='1' ? '' : 'display:none;' }}">
            <label class="form-label fw-semibold mb-2">موارد اصلاح موردنیاز:</label>
            <div class="row g-2 mb-3 p-3" style="background:#F5F5F5;border-radius:8px;">
                @foreach([
                    'structure_fix'       => 'اصلاح سازه',
                    'reinforcement'       => 'مقاوم‌سازی',
                    'panel_fix'           => 'اصلاح تابلو برق',
                    'electrical_fix'      => 'اصلاح تأسیسات برق',
                    'cable_route_fix'     => 'اصلاح مسیر کابل‌کشی',
                    'equipment_space'     => 'ایجاد محل نصب تجهیزات',
                    'obstacle_removal'    => 'رفع موانع فیزیکی',
                    'capacity_adjustment' => 'اصلاح ظرفیت پروژه',
                    'other'               => 'سایر',
                ] as $v=>$l)
                <div class="col-md-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="pre_execution_fix_types[]" value="{{ $v }}"
                               id="prefix_type_{{ $v }}" {{ in_array($v, old('pre_execution_fix_types',[]))?'checked':'' }}>
                        <label class="form-check-label" for="prefix_type_{{ $v }}">{{ $l }}</label>
                    </div>
                </div>
                @endforeach
            </div>

            <label class="form-label fw-semibold mb-2">شرح اقدامات و اصلاحات:</label>
            <textarea name="pre_execution_fix_description" rows="3" class="form-control"
                      style="border-radius:8px;">{{ old('pre_execution_fix_description') }}</textarea>
        </div>

    </div>
</div>
