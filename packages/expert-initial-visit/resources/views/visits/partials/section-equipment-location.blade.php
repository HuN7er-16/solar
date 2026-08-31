<div class="card mb-4" style="border-radius:12px;border:none;box-shadow:0 4px 20px rgba(0,0,0,0.08);">
    <div class="card-header d-flex align-items-center gap-3"
         style="background:linear-gradient(135deg,#E8EAF6,#C5CAE9);border-radius:12px 12px 0 0;border:none;">
        <div style="width:38px;height:38px;background:linear-gradient(135deg,#7986CB,#5C6BC0);border-radius:10px;display:flex;align-items:center;justify-content:center;">
            <i class="fa fa-wrench text-white"></i>
        </div>
        <h5 class="mb-0 fw-bold" style="color:#283593;">بخش ۸ — ارزیابی محل نصب تجهیزات</h5>
    </div>
    <div class="card-body p-4">
        <div class="row g-4">

            @php
                $locOptions = ['yes'=>'بله','no'=>'خیر','with_fix'=>'با اصلاح شرایط'];
                $batLocOptions = ['yes'=>'بله','no'=>'خیر','with_fix'=>'با اصلاح شرایط','not_needed'=>'موردنیاز نیست'];
            @endphp

            <div class="col-md-4">
                <label class="form-label fw-semibold mb-2">محل مناسب نصب اینورتر: <span class="text-danger">*</span></label>
                <select name="inverter_location" class="form-control @error('inverter_location') is-invalid @enderror"
                        style="border-radius:8px;border:2px solid #E0E0E0;padding:10px 14px;">
                    <option value="">-- انتخاب کنید --</option>
                    @foreach($locOptions as $v=>$l)
                    <option value="{{ $v }}" {{ old('inverter_location')===$v?'selected':'' }}>{{ $l }}</option>
                    @endforeach
                </select>
                @error('inverter_location')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold mb-2">محل مناسب نصب باتری: <span class="text-danger">*</span></label>
                <select name="battery_location" class="form-control @error('battery_location') is-invalid @enderror"
                        style="border-radius:8px;border:2px solid #E0E0E0;padding:10px 14px;">
                    <option value="">-- انتخاب کنید --</option>
                    @foreach($batLocOptions as $v=>$l)
                    <option value="{{ $v }}" {{ old('battery_location')===$v?'selected':'' }}>{{ $l }}</option>
                    @endforeach
                </select>
                @error('battery_location')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold mb-2">تهویه محل تجهیزات مناسب است؟ <span class="text-danger">*</span></label>
                <div class="d-flex gap-4 p-3" style="background:#F5F5F5;border-radius:8px;">
                    @foreach(['1'=>'بله','0'=>'خیر'] as $v=>$l)
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="equipment_ventilation_ok" value="{{ $v }}"
                               id="vent_{{ $v }}" {{ old('equipment_ventilation_ok','1')===$v?'checked':'' }}
                               style="width:18px;height:18px;">
                        <label class="form-check-label fw-semibold me-2" for="vent_{{ $v }}"
                               style="color:{{ $v==='1'?'#2E7D32':'#C62828' }};">{{ $l }}</label>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold mb-2">مسیر مناسب کابل‌کشی: <span class="text-danger">*</span></label>
                <div class="d-flex gap-4 p-3" style="background:#F5F5F5;border-radius:8px;">
                    @foreach(['1'=>'بله','0'=>'خیر'] as $v=>$l)
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="cable_route_ok" value="{{ $v }}"
                               id="cable_{{ $v }}" {{ old('cable_route_ok','1')===$v?'checked':'' }}
                               style="width:18px;height:18px;">
                        <label class="form-check-label fw-semibold me-2" for="cable_{{ $v }}"
                               style="color:{{ $v==='1'?'#2E7D32':'#C62828' }};">{{ $l }}</label>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold mb-2">نیاز به ایجاد فضای جدید: <span class="text-danger">*</span></label>
                <div class="d-flex gap-4 p-3" style="background:#F5F5F5;border-radius:8px;">
                    @foreach(['1'=>'بله','0'=>'خیر'] as $v=>$l)
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="new_equipment_space_needed" value="{{ $v }}"
                               id="newsp_{{ $v }}" {{ old('new_equipment_space_needed','0')===$v?'checked':'' }}
                               style="width:18px;height:18px;">
                        <label class="form-check-label fw-semibold me-2" for="newsp_{{ $v }}"
                               style="color:{{ $v==='0'?'#2E7D32':'#C62828' }};">{{ $l }}</label>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="col-12">
                <label class="form-label fw-semibold mb-2">توضیحات:</label>
                <textarea name="equipment_location_notes" rows="2" class="form-control"
                          style="border-radius:8px;border:2px solid #E0E0E0;padding:10px 14px;">{{ old('equipment_location_notes') }}</textarea>
            </div>

        </div>
    </div>
</div>
