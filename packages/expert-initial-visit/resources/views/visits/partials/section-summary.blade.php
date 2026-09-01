<div class="card mb-4" style="border-radius:12px;border:none;box-shadow:0 4px 20px rgba(0,0,0,0.08);">
    <div class="card-header d-flex align-items-center gap-3"
         style="background:linear-gradient(135deg,#E8EAF6,#C5CAE9);border-radius:12px 12px 0 0;border:none;">
        <div style="width:38px;height:38px;background:linear-gradient(135deg,#7986CB,#5C6BC0);border-radius:10px;display:flex;align-items:center;justify-content:center;">
            <i class="fa fa-pencil-square text-white"></i>
        </div>
        <h5 class="mb-0 fw-bold" style="color:#283593;">بخش ۱۲ — جمع‌بندی و نظر کارشناسی <span class="text-danger">*</span></h5>
    </div>
    <div class="card-body p-4">
        <p class="text-muted small mb-3">
            در این بخش جمع‌بندی کامل خود را درباره شرایط محل، امکان اجرا، ظرفیت پیشنهادی، وضعیت برق، بار اضطراری، نیاز به باتری، اصلاحات موردنیاز و سایر ملاحظات اجرایی بنویسید.
        </p>
        <textarea name="expert_summary" rows="6"
                  class="form-control @error('expert_summary') is-invalid @enderror"
                  style="border-radius:8px;font-size:15px;line-height:1.8;"
                  placeholder="جمع‌بندی کارشناسی را اینجا بنویسید...">{{ old('expert_summary') }}</textarea>
        @error('expert_summary')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
    </div>
</div>
