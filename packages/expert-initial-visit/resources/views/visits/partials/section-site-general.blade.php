<div class="card mb-4" style="border-radius:12px;border:none;box-shadow:0 4px 20px rgba(0,0,0,0.08);">
    <div class="card-header d-flex align-items-center gap-3"
         style="background:linear-gradient(135deg,#E8EAF6,#C5CAE9);border-radius:12px 12px 0 0;border:none;">
        <div style="width:38px;height:38px;background:linear-gradient(135deg,#7986CB,#5C6BC0);border-radius:10px;display:flex;align-items:center;justify-content:center;">
            <i class="fa fa-home text-white"></i>
        </div>
        <h5 class="mb-0 fw-bold" style="color:#283593;">بخش ۳ — وضعیت کلی محل و فضای قابل استفاده</h5>
    </div>
    <div class="card-body p-4">
        <div class="row g-4">

            <div class="col-md-6">
                <label class="form-label fw-semibold mb-2">فضای مناسب برای احداث نیروگاه وجود دارد؟ <span class="text-danger">*</span></label>
                <div class="d-flex gap-4 p-3 @error('suitable_space_exists') border border-danger @enderror" style="background:#F5F5F5;border-radius:8px;">
                    @foreach(['1'=>'بله','0'=>'خیر'] as $val=>$lbl)
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="suitable_space_exists" value="{{ $val }}"
                               id="space_{{ $val }}" {{ old('suitable_space_exists','1')===$val?'checked':'' }}
                               style="width:18px;height:18px;">
                        <label class="form-check-label fw-semibold me-2" for="space_{{ $val }}"
                               style="color:{{ $val==='1'?'#2E7D32':'#C62828' }};">{{ $lbl }}</label>
                    </div>
                    @endforeach
                </div>
                @error('suitable_space_exists')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold mb-2">نوع محل نصب: <span class="text-danger">*</span></label>
                <select name="installation_location_type" id="loc_type_select"
                        class="form-control @error('installation_location_type') is-invalid @enderror"
                        style="border-radius:8px;">
                    <option value="">-- انتخاب کنید --</option>
                    <option value="flat_roof"      {{ old('installation_location_type')==='flat_roof'     ?'selected':'' }}>پشت‌بام مسطح</option>
                    <option value="sloped_roof"    {{ old('installation_location_type')==='sloped_roof'   ?'selected':'' }}>پشت‌بام شیب‌دار</option>
                    <option value="ground"         {{ old('installation_location_type')==='ground'        ?'selected':'' }}>زمین</option>
                    <option value="parking_canopy" {{ old('installation_location_type')==='parking_canopy'?'selected':'' }}>پارکینگ / سایبان</option>
                    <option value="other"          {{ old('installation_location_type')==='other'         ?'selected':'' }}>سایر</option>
                </select>
                @error('installation_location_type')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6" id="loc_type_other_row" style="{{ old('installation_location_type')==='other' ? '' : 'display:none;' }}">
                <label class="form-label fw-semibold mb-2">توضیح نوع محل:</label>
                <input type="text" name="installation_location_type_other" class="form-control"
                       value="{{ old('installation_location_type_other') }}"
                       style="border-radius:8px;">
            </div>

            <div class="col-md-3">
                <label class="form-label fw-semibold mb-2">مساحت کل محل (متر مربع):</label>
                <input type="number" name="total_area_sqm" min="0" class="form-control"
                       value="{{ old('total_area_sqm') }}" style="border-radius:8px;">
            </div>

            <div class="col-md-3">
                <label class="form-label fw-semibold mb-2">مساحت قابل استفاده (متر مربع):</label>
                <input type="number" name="usable_area_sqm" min="0" class="form-control"
                       value="{{ old('usable_area_sqm') }}" style="border-radius:8px;">
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold mb-2">دسترسی به محل نصب مناسب است؟ <span class="text-danger">*</span></label>
                <div class="d-flex gap-4 p-3 @error('access_to_installation_site') border border-danger @enderror" style="background:#F5F5F5;border-radius:8px;">
                    @foreach(['1'=>'بله','0'=>'خیر'] as $val=>$lbl)
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="access_to_installation_site" value="{{ $val }}"
                               id="acc_site_{{ $val }}" {{ old('access_to_installation_site','1')===$val?'checked':'' }}
                               style="width:18px;height:18px;">
                        <label class="form-check-label fw-semibold me-2" for="acc_site_{{ $val }}"
                               style="color:{{ $val==='1'?'#2E7D32':'#C62828' }};">{{ $lbl }}</label>
                    </div>
                    @endforeach
                </div>
                @error('access_to_installation_site')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold mb-2">مانع فیزیکی مؤثر در نصب وجود دارد؟ <span class="text-danger">*</span></label>
                <div class="d-flex gap-4 p-3 @error('physical_obstacle_exists') border border-danger @enderror" style="background:#F5F5F5;border-radius:8px;">
                    @foreach(['1'=>'دارد','0'=>'ندارد'] as $val=>$lbl)
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="physical_obstacle_exists" value="{{ $val }}"
                               id="obstacle_{{ $val }}" {{ old('physical_obstacle_exists','0')===$val?'checked':'' }}
                               style="width:18px;height:18px;">
                        <label class="form-check-label fw-semibold me-2" for="obstacle_{{ $val }}"
                               style="color:{{ $val==='0'?'#2E7D32':'#C62828' }};">{{ $lbl }}</label>
                    </div>
                    @endforeach
                </div>
                @error('physical_obstacle_exists')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>

            <div class="col-12" id="obstacle_types_row" style="{{ old('physical_obstacle_exists')==='1' ? '' : 'display:none;' }}">
                <label class="form-label fw-semibold mb-2">نوع موانع:</label>
                <div class="row g-2">
                    @foreach(['building'=>'ساختمان','tree'=>'درخت','pole'=>'دکل / تأسیسات','adjacent_building'=>'ساختمان مجاور','existing_equipment'=>'تجهیزات موجود','access_limit'=>'محدودیت دسترسی','other'=>'سایر'] as $val=>$lbl)
                    <div class="col-md-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="obstacle_types[]" value="{{ $val }}"
                                   id="obs_{{ $val }}"
                                   {{ in_array($val, old('obstacle_types',[]))?'checked':'' }}>
                            <label class="form-check-label" for="obs_{{ $val }}">{{ $lbl }}</label>
                        </div>
                    </div>
                    @endforeach
                </div>
                <textarea name="obstacle_notes" rows="2" placeholder="توضیحات بیشتر..." class="form-control mt-2"
                          style="border-radius:8px;">{{ old('obstacle_notes') }}</textarea>
            </div>

        </div>
    </div>
</div>
