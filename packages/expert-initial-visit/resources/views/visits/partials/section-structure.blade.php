<div class="card mb-4" style="border-radius:12px;border:none;box-shadow:0 4px 20px rgba(0,0,0,0.08);">
    <div class="card-header d-flex align-items-center gap-3"
         style="background:linear-gradient(135deg,#E8EAF6,#C5CAE9);border-radius:12px 12px 0 0;border:none;">
        <div style="width:38px;height:38px;background:linear-gradient(135deg,#7986CB,#5C6BC0);border-radius:10px;display:flex;align-items:center;justify-content:center;">
            <i class="fa fa-building text-white"></i>
        </div>
        <h5 class="mb-0 fw-bold" style="color:#283593;">بخش ۵ — بررسی سازه و ایمنی</h5>
    </div>
    <div class="card-body p-4">
        <div class="row g-4">

            <div class="col-md-6">
                <label class="form-label fw-semibold mb-2">وضعیت تحمل بار سازه: <span class="text-danger">*</span></label>
                <select name="structure_load_capacity" class="form-control @error('structure_load_capacity') is-invalid @enderror"
                        style="border-radius:8px;border:2px solid #E0E0E0;padding:10px 14px;">
                    <option value="">-- انتخاب کنید --</option>
                    <option value="suitable"              {{ old('structure_load_capacity')==='suitable'             ?'selected':'' }}>مناسب</option>
                    <option value="needs_reinforcement"   {{ old('structure_load_capacity')==='needs_reinforcement'  ?'selected':'' }}>نیاز به تقویت</option>
                    <option value="unsuitable"            {{ old('structure_load_capacity')==='unsuitable'           ?'selected':'' }}>نامناسب</option>
                    <option value="needs_expert_review"   {{ old('structure_load_capacity')==='needs_expert_review'  ?'selected':'' }}>نیازمند بررسی تخصصی</option>
                </select>
                @error('structure_load_capacity')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-3">
                <label class="form-label fw-semibold mb-2">نیاز به مقاوم‌سازی: <span class="text-danger">*</span></label>
                <div class="d-flex gap-4 p-3" style="background:#F5F5F5;border-radius:8px;">
                    @foreach(['1'=>'بله','0'=>'خیر'] as $v=>$l)
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="reinforcement_needed" value="{{ $v }}"
                               id="reinf_{{ $v }}" {{ old('reinforcement_needed','0')===$v?'checked':'' }}
                               style="width:18px;height:18px;">
                        <label class="form-check-label fw-semibold me-2" for="reinf_{{ $v }}"
                               style="color:{{ $v==='0'?'#2E7D32':'#C62828' }};">{{ $l }}</label>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="col-md-3">
                <label class="form-label fw-semibold mb-2">نیاز به سازه خاص: <span class="text-danger">*</span></label>
                <div class="d-flex gap-4 p-3" style="background:#F5F5F5;border-radius:8px;">
                    @foreach(['1'=>'بله','0'=>'خیر'] as $v=>$l)
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="special_structure_needed" value="{{ $v }}"
                               id="spec_str_{{ $v }}" {{ old('special_structure_needed','0')===$v?'checked':'' }}
                               style="width:18px;height:18px;">
                        <label class="form-check-label fw-semibold me-2" for="spec_str_{{ $v }}"
                               style="color:{{ $v==='0'?'#2E7D32':'#C62828' }};">{{ $l }}</label>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="col-md-8">
                <label class="form-label fw-semibold mb-2">ریسک‌های موجود در محل:</label>
                <div class="d-flex flex-wrap gap-3 p-3" style="background:#F5F5F5;border-radius:8px;">
                    @foreach(['wind'=>'باد','humidity'=>'رطوبت','corrosion'=>'خوردگی','fall_risk'=>'خطر سقوط','hard_access'=>'دسترسی دشوار','fire_risk'=>'خطر آتش‌سوزی','other'=>'سایر'] as $v=>$l)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="site_risks[]" value="{{ $v }}"
                               id="risk_{{ $v }}" {{ in_array($v,old('site_risks',[]))?'checked':'' }}>
                        <label class="form-check-label" for="risk_{{ $v }}">{{ $l }}</label>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold mb-2">سطح ریسک کلی پروژه: <span class="text-danger">*</span></label>
                <select name="overall_risk_level" class="form-control @error('overall_risk_level') is-invalid @enderror"
                        style="border-radius:8px;border:2px solid #E0E0E0;padding:10px 14px;">
                    <option value="">-- انتخاب کنید --</option>
                    <option value="low"    {{ old('overall_risk_level')==='low'   ?'selected':'' }}>کم</option>
                    <option value="medium" {{ old('overall_risk_level')==='medium'?'selected':'' }}>متوسط</option>
                    <option value="high"   {{ old('overall_risk_level')==='high'  ?'selected':'' }}>زیاد</option>
                </select>
                @error('overall_risk_level')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>

            <div class="col-12">
                <label class="form-label fw-semibold mb-2">توضیحات سازه‌ای و ایمنی:</label>
                <textarea name="structure_notes" rows="2" class="form-control"
                          style="border-radius:8px;border:2px solid #E0E0E0;padding:10px 14px;">{{ old('structure_notes') }}</textarea>
            </div>

        </div>
    </div>
</div>
