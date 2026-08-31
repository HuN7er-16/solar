<div class="card mb-4" style="border-radius:12px;border:none;box-shadow:0 4px 20px rgba(0,0,0,0.08);">
    <div class="card-header d-flex align-items-center gap-3"
         style="background:linear-gradient(135deg,#E8F5E9,#C8E6C9);border-radius:12px 12px 0 0;border:none;">
        <div style="width:38px;height:38px;background:linear-gradient(135deg,#81C784,#4CAF50);border-radius:10px;display:flex;align-items:center;justify-content:center;">
            <i class="fa fa-tachometer text-white"></i>
        </div>
        <h5 class="mb-0 fw-bold" style="color:#1B5E20;">بخش ۹ — تعیین ظرفیت پیشنهادی نیروگاه</h5>
    </div>
    <div class="card-body p-4">
        <div class="row g-4">

            <div class="col-md-3">
                <label class="form-label fw-semibold mb-2">ظرفیت درخواستی متقاضی (kW):</label>
                <input type="number" name="applicant_requested_capacity_kw" min="0" step="0.01" class="form-control"
                       value="{{ old('applicant_requested_capacity_kw', $solarPlantRequest->capacity_kw ?? '') }}"
                       style="border-radius:8px;border:2px solid #E0E0E0;padding:10px 14px;background:#F5F5F5;" readonly>
                <small class="text-muted">از اطلاعات ثبت‌شده متقاضی</small>
            </div>

            <div class="col-md-3">
                <label class="form-label fw-semibold mb-2">ظرفیت قابل نصب بر اساس فضا (kW):</label>
                <input type="number" name="installable_capacity_kw" min="0" step="0.01" class="form-control"
                       value="{{ old('installable_capacity_kw') }}"
                       style="border-radius:8px;border:2px solid #E0E0E0;padding:10px 14px;">
            </div>

            <div class="col-md-3">
                <label class="form-label fw-semibold mb-2">ظرفیت پیشنهادی کارشناس (kW): <span class="text-danger">*</span></label>
                <input type="number" name="expert_proposed_capacity_kw" min="0" step="0.01"
                       class="form-control @error('expert_proposed_capacity_kw') is-invalid @enderror"
                       value="{{ old('expert_proposed_capacity_kw') }}"
                       style="border-radius:8px;border:2px solid #A5D6A7;padding:10px 14px;background:#F1F8E9;">
                @error('expert_proposed_capacity_kw')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-3">
                <label class="form-label fw-semibold mb-2">ظرفیت پیشنهادی اینورتر (kW):</label>
                <input type="number" name="expert_proposed_inverter_kw" min="0" step="0.01" class="form-control"
                       value="{{ old('expert_proposed_inverter_kw') }}"
                       style="border-radius:8px;border:2px solid #E0E0E0;padding:10px 14px;">
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold mb-2">نیاز به باتری: <span class="text-danger">*</span></label>
                <div class="d-flex gap-4 p-3" style="background:#F5F5F5;border-radius:8px;">
                    @foreach(['1'=>'بله','0'=>'خیر'] as $v=>$l)
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="battery_required" value="{{ $v }}"
                               id="bat_req_{{ $v }}" {{ old('battery_required','0')===$v?'checked':'' }}
                               style="width:18px;height:18px;">
                        <label class="form-check-label fw-semibold me-2" for="bat_req_{{ $v }}"
                               style="color:{{ $v==='1'?'#2E7D32':'#C62828' }};">{{ $l }}</label>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="col-md-4" id="battery_kwh_row" style="{{ old('battery_required')==='1' ? '' : 'display:none;' }}">
                <label class="form-label fw-semibold mb-2">ظرفیت پیشنهادی باتری (kWh):</label>
                <input type="number" name="expert_proposed_battery_kwh" min="0" step="0.01" class="form-control"
                       value="{{ old('expert_proposed_battery_kwh') }}"
                       style="border-radius:8px;border:2px solid #E0E0E0;padding:10px 14px;">
            </div>

            <div class="col-12">
                <label class="form-label fw-semibold mb-2">علت تفاوت ظرفیت پیشنهادی با ظرفیت درخواستی (در صورت وجود):</label>
                <textarea name="capacity_difference_reason" rows="2" class="form-control"
                          style="border-radius:8px;border:2px solid #E0E0E0;padding:10px 14px;">{{ old('capacity_difference_reason') }}</textarea>
            </div>

        </div>
    </div>
</div>
