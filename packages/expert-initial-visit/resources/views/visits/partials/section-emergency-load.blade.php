<div class="card mb-4" style="border-radius:12px;border:none;box-shadow:0 4px 20px rgba(0,0,0,0.08);">
    <div class="card-header d-flex align-items-center gap-3"
         style="background:linear-gradient(135deg,#E8EAF6,#C5CAE9);border-radius:12px 12px 0 0;border:none;">
        <div style="width:38px;height:38px;background:linear-gradient(135deg,#7986CB,#5C6BC0);border-radius:10px;display:flex;align-items:center;justify-content:center;">
            <i class="fa fa-plug text-white"></i>
        </div>
        <h5 class="mb-0 fw-bold" style="color:#283593;">بخش ۷ — بررسی مصرف‌کننده‌ها در زمان قطعی برق</h5>
    </div>
    <div class="card-body p-4">

        <div class="mb-4">
            <label class="form-label fw-semibold mb-2">آیا متقاضی نیاز دارد تجهیزاتی در زمان قطعی برق فعال بمانند؟ <span class="text-danger">*</span></label>
            <div class="d-flex gap-4 p-3 @error('has_emergency_load') border border-danger @enderror" style="background:#F5F5F5;border-radius:8px;">
                @foreach(['1'=>'بله','0'=>'خیر'] as $v=>$l)
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="has_emergency_load" value="{{ $v }}"
                           id="emrg_{{ $v }}" {{ old('has_emergency_load','0')===$v?'checked':'' }}
                           style="width:18px;height:18px;">
                    <label class="form-check-label fw-semibold me-2" for="emrg_{{ $v }}"
                           style="color:{{ $v==='1'?'#2E7D32':'#C62828' }};">{{ $l }}</label>
                </div>
                @endforeach
            </div>
            @error('has_emergency_load')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        </div>

        {{-- جدول پویای تجهیزات --}}
        <div id="emergency_equipment_table" style="{{ old('has_emergency_load')==='1' ? '' : 'display:none;' }}">

            <div class="table-responsive mb-3">
                <table class="table table-bordered" id="equipmentTable" style="min-width:900px;">
                    <thead style="background:#E8EAF6;">
                        <tr>
                            <th style="color:#283593;">نام تجهیز</th>
                            <th style="color:#283593;width:80px;">تعداد</th>
                            <th style="color:#283593;width:110px;">توان (W)</th>
                            <th style="color:#283593;width:110px;">توان کل (W)</th>
                            <th style="color:#283593;width:100px;">مدت (ساعت)</th>
                            <th style="color:#283593;width:80px;">ضروری</th>
                            <th style="color:#283593;">توضیحات</th>
                            <th style="width:50px;"></th>
                        </tr>
                    </thead>
                    <tbody id="equipmentBody">
                        @php
                            $defaultItems = ['یخچال','پمپ آب','سیستم سرمایش','سیستم گرمایش','روشنایی','کامپیوتر','تجهیزات شبکه'];
                            $oldNames = old('equipment_name', []);
                        @endphp

                        @if(count($oldNames))
                            @foreach($oldNames as $i => $eName)
                            <tr>
                                <td><input type="text" name="equipment_name[]" value="{{ $eName }}" class="form-control form-control-sm" style="border-radius:6px;"></td>
                                <td><input type="number" name="equipment_quantity[]" value="{{ old('equipment_quantity.'.$i, 1) }}" min="1" class="form-control form-control-sm eq-qty" style="border-radius:6px;"></td>
                                <td><input type="number" name="equipment_power_watts[]" value="{{ old('equipment_power_watts.'.$i) }}" min="0" step="0.01" class="form-control form-control-sm eq-power" style="border-radius:6px;"></td>
                                <td><input type="number" name="equipment_total_power_watts[]" value="{{ old('equipment_total_power_watts.'.$i) }}" readonly class="form-control form-control-sm eq-total" style="border-radius:6px;background:#F5F5F5;"></td>
                                <td><input type="number" name="equipment_usage_hours[]" value="{{ old('equipment_usage_hours.'.$i) }}" min="0" class="form-control form-control-sm" style="border-radius:6px;"></td>
                                <td class="text-center">
                                    <input type="checkbox" name="equipment_is_critical[]" value="1" class="form-check-input" style="width:20px;height:20px;" {{ old('equipment_is_critical.'.$i) ? 'checked' : '' }}>
                                </td>
                                <td><input type="text" name="equipment_notes[]" value="{{ old('equipment_notes.'.$i) }}" class="form-control form-control-sm" style="border-radius:6px;"></td>
                                <td class="text-center"><button type="button" class="btn btn-sm btn-danger remove-row" style="border-radius:6px;padding:2px 8px;"><i class="fa fa-times"></i></button></td>
                            </tr>
                            @endforeach
                        @else
                            @foreach($defaultItems as $item)
                            <tr>
                                <td><input type="text" name="equipment_name[]" value="{{ $item }}" class="form-control form-control-sm" style="border-radius:6px;"></td>
                                <td><input type="number" name="equipment_quantity[]" value="1" min="1" class="form-control form-control-sm eq-qty" style="border-radius:6px;"></td>
                                <td><input type="number" name="equipment_power_watts[]" value="" min="0" step="0.01" class="form-control form-control-sm eq-power" style="border-radius:6px;" placeholder="W"></td>
                                <td><input type="number" name="equipment_total_power_watts[]" value="" readonly class="form-control form-control-sm eq-total" style="border-radius:6px;background:#F5F5F5;"></td>
                                <td><input type="number" name="equipment_usage_hours[]" value="" min="0" class="form-control form-control-sm" style="border-radius:6px;" placeholder="ساعت"></td>
                                <td class="text-center">
                                    <input type="checkbox" name="equipment_is_critical[]" value="1" class="form-check-input" style="width:20px;height:20px;">
                                </td>
                                <td><input type="text" name="equipment_notes[]" value="" class="form-control form-control-sm" style="border-radius:6px;"></td>
                                <td class="text-center"><button type="button" class="btn btn-sm btn-danger remove-row" style="border-radius:6px;padding:2px 8px;"><i class="fa fa-times"></i></button></td>
                            </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>

            <button type="button" id="addEquipmentRow" class="btn btn-sm text-white mb-4"
                    style="background:linear-gradient(135deg,#7986CB,#5C6BC0);border-radius:8px;">
                <i class="fa fa-plus ms-1"></i> افزودن تجهیز جدید
            </button>

            <div class="row g-4 p-3" style="background:#F8F9FF;border-radius:10px;border:1px solid #C5CAE9;">
                <h6 class="fw-bold" style="color:#283593;">جمع‌بندی بار اضطراری</h6>
                <div class="col-md-3">
                    <label class="form-label fw-semibold mb-2">مجموع توان بارهای ضروری (kW):</label>
                    <input type="number" name="total_emergency_load_kw" min="0" step="0.01" class="form-control"
                           value="{{ old('total_emergency_load_kw') }}" style="border-radius:8px;">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold mb-2">مدت زمان تأمین برق (ساعت):</label>
                    <input type="number" name="emergency_supply_hours" min="0" class="form-control"
                           value="{{ old('emergency_supply_hours') }}" style="border-radius:8px;">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold mb-2">نیاز به باتری:</label>
                    <select name="battery_need" class="form-control" style="border-radius:8px;">
                        <option value="">-- انتخاب کنید --</option>
                        <option value="yes"      {{ old('battery_need')==='yes'     ?'selected':'' }}>بله</option>
                        <option value="no"       {{ old('battery_need')==='no'      ?'selected':'' }}>خیر</option>
                        <option value="optional" {{ old('battery_need')==='optional'?'selected':'' }}>اختیاری</option>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold mb-2">توضیحات بار اضطراری:</label>
                    <textarea name="emergency_load_notes" rows="2" class="form-control"
                              style="border-radius:8px;">{{ old('emergency_load_notes') }}</textarea>
                </div>
            </div>
        </div>

    </div>
</div>
