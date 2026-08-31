<div class="card mb-4" style="border-radius:12px;border:none;box-shadow:0 4px 20px rgba(0,0,0,0.08);">
    <div class="card-header d-flex align-items-center gap-3"
         style="background:linear-gradient(135deg,#E8EAF6,#C5CAE9);border-radius:12px 12px 0 0;border:none;">
        <div style="width:38px;height:38px;background:linear-gradient(135deg,#7986CB,#5C6BC0);border-radius:10px;display:flex;align-items:center;justify-content:center;">
            <i class="fa fa-bolt text-white"></i>
        </div>
        <h5 class="mb-0 fw-bold" style="color:#283593;">بخش ۶ — بررسی برق و زیرساخت</h5>
    </div>
    <div class="card-body p-4">
        <div class="row g-4">

            <div class="col-md-4">
                <label class="form-label fw-semibold mb-2">نوع برق محل: <span class="text-danger">*</span></label>
                <div class="d-flex gap-4 p-3" style="background:#F5F5F5;border-radius:8px;">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="electricity_type" value="single_phase"
                               id="elec_single" {{ old('electricity_type')==='single_phase'?'checked':'' }}
                               style="width:18px;height:18px;">
                        <label class="form-check-label fw-semibold me-2" for="elec_single">تک‌فاز</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="electricity_type" value="three_phase"
                               id="elec_three" {{ old('electricity_type')==='three_phase'?'checked':'' }}
                               style="width:18px;height:18px;">
                        <label class="form-check-label fw-semibold me-2" for="elec_three">سه‌فاز</label>
                    </div>
                </div>
                @error('electricity_type')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold mb-2">ظرفیت انشعاب (آمپر):</label>
                <input type="number" name="connection_capacity_ampere" min="0" class="form-control"
                       value="{{ old('connection_capacity_ampere') }}"
                       style="border-radius:8px;border:2px solid #E0E0E0;padding:10px 14px;">
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold mb-2">تابلو برق اصلی قابل دسترسی است؟ <span class="text-danger">*</span></label>
                <div class="d-flex gap-4 p-3" style="background:#F5F5F5;border-radius:8px;">
                    @foreach(['1'=>'بله','0'=>'خیر'] as $v=>$l)
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="main_panel_accessible" value="{{ $v }}"
                               id="panel_acc_{{ $v }}" {{ old('main_panel_accessible','1')===$v?'checked':'' }}
                               style="width:18px;height:18px;">
                        <label class="form-check-label fw-semibold me-2" for="panel_acc_{{ $v }}"
                               style="color:{{ $v==='1'?'#2E7D32':'#C62828' }};">{{ $l }}</label>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold mb-2">وضعیت تابلو برق: <span class="text-danger">*</span></label>
                <select name="main_panel_condition" class="form-control @error('main_panel_condition') is-invalid @enderror"
                        style="border-radius:8px;border:2px solid #E0E0E0;padding:10px 14px;">
                    <option value="">-- انتخاب کنید --</option>
                    <option value="suitable"      {{ old('main_panel_condition')==='suitable'      ?'selected':'' }}>مناسب</option>
                    <option value="needs_fix"     {{ old('main_panel_condition')==='needs_fix'     ?'selected':'' }}>نیاز به اصلاح</option>
                    <option value="unsuitable"    {{ old('main_panel_condition')==='unsuitable'    ?'selected':'' }}>نامناسب</option>
                    <option value="needs_review"  {{ old('main_panel_condition')==='needs_review'  ?'selected':'' }}>نیازمند بررسی بیشتر</option>
                </select>
                @error('main_panel_condition')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold mb-2">وضعیت کلی تأسیسات برق: <span class="text-danger">*</span></label>
                <select name="electrical_installation_condition" class="form-control @error('electrical_installation_condition') is-invalid @enderror"
                        style="border-radius:8px;border:2px solid #E0E0E0;padding:10px 14px;">
                    <option value="">-- انتخاب کنید --</option>
                    <option value="suitable"   {{ old('electrical_installation_condition')==='suitable'  ?'selected':'' }}>مناسب</option>
                    <option value="needs_fix"  {{ old('electrical_installation_condition')==='needs_fix' ?'selected':'' }}>نیاز به اصلاح</option>
                    <option value="unsuitable" {{ old('electrical_installation_condition')==='unsuitable'?'selected':'' }}>نامناسب</option>
                </select>
                @error('electrical_installation_condition')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold mb-2">امکان اتصال به شبکه: <span class="text-danger">*</span></label>
                <div class="d-flex gap-4 p-3" style="background:#F5F5F5;border-radius:8px;">
                    @foreach(['1'=>'بله','0'=>'خیر'] as $v=>$l)
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="grid_connection_possible" value="{{ $v }}"
                               id="grid_{{ $v }}" {{ old('grid_connection_possible','1')===$v?'checked':'' }}
                               style="width:18px;height:18px;">
                        <label class="form-check-label fw-semibold me-2" for="grid_{{ $v }}"
                               style="color:{{ $v==='1'?'#2E7D32':'#C62828' }};">{{ $l }}</label>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold mb-2">نیاز به اصلاح تأسیسات برق: <span class="text-danger">*</span></label>
                <div class="d-flex gap-4 p-3" style="background:#F5F5F5;border-radius:8px;">
                    @foreach(['1'=>'بله','0'=>'خیر'] as $v=>$l)
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="electrical_fix_needed" value="{{ $v }}"
                               id="elec_fix_{{ $v }}" {{ old('electrical_fix_needed','0')===$v?'checked':'' }}
                               style="width:18px;height:18px;">
                        <label class="form-check-label fw-semibold me-2" for="elec_fix_{{ $v }}"
                               style="color:{{ $v==='0'?'#2E7D32':'#C62828' }};">{{ $l }}</label>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="col-12">
                <label class="form-label fw-semibold mb-2">توضیحات برق و زیرساخت:</label>
                <textarea name="electrical_notes" rows="2" class="form-control"
                          style="border-radius:8px;border:2px solid #E0E0E0;padding:10px 14px;">{{ old('electrical_notes') }}</textarea>
            </div>

        </div>
    </div>
</div>
