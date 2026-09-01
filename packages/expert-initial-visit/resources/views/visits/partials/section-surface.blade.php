<div class="card mb-4" style="border-radius:12px;border:none;box-shadow:0 4px 20px rgba(0,0,0,0.08);">
    <div class="card-header d-flex align-items-center gap-3"
         style="background:linear-gradient(135deg,#E8EAF6,#C5CAE9);border-radius:12px 12px 0 0;border:none;">
        <div style="width:38px;height:38px;background:linear-gradient(135deg,#7986CB,#5C6BC0);border-radius:10px;display:flex;align-items:center;justify-content:center;">
            <i class="fa fa-th-large text-white"></i>
        </div>
        <h5 class="mb-0 fw-bold" style="color:#283593;">بخش ۴ — ارزیابی سطح و شرایط نصب پنل</h5>
    </div>
    <div class="card-body p-4">
        <div class="row g-4">

            <div class="col-md-4">
                <label class="form-label fw-semibold mb-2">نوع سطح نصب: <span class="text-danger">*</span></label>
                <select name="surface_type" class="form-control @error('surface_type') is-invalid @enderror"
                        style="border-radius:8px;">
                    <option value="">-- انتخاب کنید --</option>
                    @foreach(['concrete'=>'بتنی','metal'=>'فلزی','tile'=>'شیروانی','soil'=>'خاکی','other'=>'سایر'] as $v=>$l)
                    <option value="{{ $v }}" {{ old('surface_type')===$v?'selected':'' }}>{{ $l }}</option>
                    @endforeach
                </select>
                @error('surface_type')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold mb-2">وضعیت سطح: <span class="text-danger">*</span></label>
                <select name="surface_orientation" class="form-control @error('surface_orientation') is-invalid @enderror"
                        style="border-radius:8px;">
                    <option value="">-- انتخاب کنید --</option>
                    <option value="horizontal" {{ old('surface_orientation')==='horizontal'?'selected':'' }}>افقی</option>
                    <option value="sloped"     {{ old('surface_orientation')==='sloped'    ?'selected':'' }}>شیب‌دار</option>
                </select>
                @error('surface_orientation')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold mb-2">جهت سطح نصب: <span class="text-danger">*</span></label>
                <select name="panel_direction" class="form-control @error('panel_direction') is-invalid @enderror"
                        style="border-radius:8px;">
                    <option value="">-- انتخاب کنید --</option>
                    @foreach(['south'=>'جنوب','southeast'=>'جنوب‌شرق','southwest'=>'جنوب‌غرب','east'=>'شرق','west'=>'غرب','north'=>'شمال','other'=>'سایر'] as $v=>$l)
                    <option value="{{ $v }}" {{ old('panel_direction')===$v?'selected':'' }}>{{ $l }}</option>
                    @endforeach
                </select>
                @error('panel_direction')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold mb-2">میزان سایه‌اندازی: <span class="text-danger">*</span></label>
                <select name="shading_level" class="form-control @error('shading_level') is-invalid @enderror"
                        style="border-radius:8px;">
                    <option value="">-- انتخاب کنید --</option>
                    @foreach(['none'=>'ندارد','low'=>'کم','medium'=>'متوسط','high'=>'زیاد'] as $v=>$l)
                    <option value="{{ $v }}" {{ old('shading_level')===$v?'selected':'' }}>{{ $l }}</option>
                    @endforeach
                </select>
                @error('shading_level')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-8">
                <label class="form-label fw-semibold mb-2">منابع سایه‌اندازی:</label>
                <div class="d-flex flex-wrap gap-3 p-3" style="background:#F5F5F5;border-radius:8px;">
                    @foreach(['building'=>'ساختمان','tree'=>'درخت','adjacent'=>'ساختمان مجاور','pole'=>'دکل / تأسیسات','other'=>'سایر'] as $v=>$l)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="shading_sources[]" value="{{ $v }}"
                               id="shade_{{ $v }}" {{ in_array($v,old('shading_sources',[]))?'checked':'' }}>
                        <label class="form-check-label" for="shade_{{ $v }}">{{ $l }}</label>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold mb-2">وضعیت سطح برای نصب پنل: <span class="text-danger">*</span></label>
                <select name="surface_condition" class="form-control @error('surface_condition') is-invalid @enderror"
                        style="border-radius:8px;">
                    <option value="">-- انتخاب کنید --</option>
                    <option value="suitable"          {{ old('surface_condition')==='suitable'         ?'selected':'' }}>مناسب</option>
                    <option value="unsuitable"        {{ old('surface_condition')==='unsuitable'       ?'selected':'' }}>نامناسب</option>
                    <option value="suitable_with_fix" {{ old('surface_condition')==='suitable_with_fix'?'selected':'' }}>مناسب با اصلاح</option>
                </select>
                @error('surface_condition')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>

            <div class="col-12">
                <label class="form-label fw-semibold mb-2">توضیحات سطح و سایه‌اندازی:</label>
                <textarea name="surface_notes" rows="2" class="form-control"
                          style="border-radius:8px;">{{ old('surface_notes') }}</textarea>
            </div>

        </div>
    </div>
</div>
