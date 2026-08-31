<div class="card mb-4" style="border-radius:12px;border:none;box-shadow:0 4px 20px rgba(0,0,0,0.08);">
    <div class="card-header d-flex align-items-center gap-3"
         style="background:linear-gradient(135deg,#E8EAF6,#C5CAE9);border-radius:12px 12px 0 0;border:none;">
        <div style="width:38px;height:38px;background:linear-gradient(135deg,#7986CB,#5C6BC0);border-radius:10px;display:flex;align-items:center;justify-content:center;">
            <i class="fa fa-map-marker text-white"></i>
        </div>
        <h5 class="mb-0 fw-bold" style="color:#283593;">بخش ۲ — بررسی و احراز محل بازدید</h5>
    </div>
    <div class="card-body p-4">
        <div class="row g-4">

            <div class="col-md-6">
                <label class="form-label fw-semibold mb-2">محل بازدید با محل اعلام‌شده توسط متقاضی مطابقت دارد؟ <span class="text-danger">*</span></label>
                <div class="d-flex gap-4 p-3" style="background:#F5F5F5;border-radius:8px;">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="location_matches" value="1"
                               id="loc_match_yes" {{ old('location_matches','1')==='1'?'checked':'' }}
                               style="width:18px;height:18px;">
                        <label class="form-check-label fw-semibold me-2" for="loc_match_yes" style="color:#2E7D32;">بله</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="location_matches" value="0"
                               id="loc_match_no" {{ old('location_matches')==='0'?'checked':'' }}
                               style="width:18px;height:18px;">
                        <label class="form-check-label fw-semibold me-2" for="loc_match_no" style="color:#C62828;">خیر</label>
                    </div>
                </div>
            </div>

            <div class="col-12" id="actual_address_row" style="{{ old('location_matches')==='0' ? '' : 'display:none;' }}">
                <label class="form-label fw-semibold mb-2">آدرس دقیق واقعی:</label>
                <textarea name="actual_address" rows="2" class="form-control"
                          style="border-radius:8px;border:2px solid #E0E0E0;padding:10px 14px;">{{ old('actual_address') }}</textarea>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold mb-2">وجود فیزیکی محل تأیید می‌شود؟ <span class="text-danger">*</span></label>
                <div class="d-flex gap-4 p-3" style="background:#F5F5F5;border-radius:8px;">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="location_physically_confirmed" value="1"
                               id="loc_phys_yes" {{ old('location_physically_confirmed','1')==='1'?'checked':'' }}
                               style="width:18px;height:18px;">
                        <label class="form-check-label fw-semibold me-2" for="loc_phys_yes" style="color:#2E7D32;">بله</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="location_physically_confirmed" value="0"
                               id="loc_phys_no" {{ old('location_physically_confirmed')==='0'?'checked':'' }}
                               style="width:18px;height:18px;">
                        <label class="form-check-label fw-semibold me-2" for="loc_phys_no" style="color:#C62828;">خیر</label>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold mb-2">دسترسی به محل: <span class="text-danger">*</span></label>
                <select name="location_access" class="form-control @error('location_access') is-invalid @enderror"
                        style="border-radius:8px;border:2px solid #E0E0E0;padding:10px 14px;">
                    <option value="">-- انتخاب کنید --</option>
                    <option value="easy"   {{ old('location_access')==='easy'  ?'selected':'' }}>آسان</option>
                    <option value="medium" {{ old('location_access')==='medium'?'selected':'' }}>متوسط</option>
                    <option value="hard"   {{ old('location_access')==='hard'  ?'selected':'' }}>دشوار</option>
                </select>
                @error('location_access')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>

        </div>
    </div>
</div>
