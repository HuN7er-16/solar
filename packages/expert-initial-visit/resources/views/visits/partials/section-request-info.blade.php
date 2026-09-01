@php
    $req = $solarPlantRequest;
@endphp
<div class="card mb-4" style="border-radius:12px;border:none;box-shadow:0 4px 20px rgba(0,0,0,0.08);">
    <div class="card-header d-flex align-items-center gap-3"
         style="background:linear-gradient(135deg,#FFF3E0,#FFE0B2);border-radius:12px 12px 0 0;border:none;">
        <div style="width:38px;height:38px;background:linear-gradient(135deg,#FFB74D,#FF9800);border-radius:10px;display:flex;align-items:center;justify-content:center;">
            <i class="fa fa-file-text text-white"></i>
        </div>
        <div>
            <h5 class="mb-0 fw-bold" style="color:#E65100;">بخش ۱ — اطلاعات درخواست</h5>
            <small class="text-muted">این اطلاعات فقط قابل مشاهده هستند</small>
        </div>
    </div>
    <div class="card-body p-4">
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label text-muted small mb-1">کد درخواست</label>
                <div class="fw-semibold"><code>{{ $req->unique_code }}</code></div>
            </div>
            <div class="col-md-3">
                <label class="form-label text-muted small mb-1">نام متقاضی</label>
                <div class="fw-semibold">
                    @if($req->applicant_type?->value === 'company')
                        {{ $req->company_name }}
                    @else
                        {{ $req->first_name }} {{ $req->last_name }}
                    @endif
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label text-muted small mb-1">شماره تماس</label>
                <div class="fw-semibold" dir="ltr">{{ $req->mobile }}</div>
            </div>
            <div class="col-md-3">
                <label class="form-label text-muted small mb-1">تاریخ ثبت درخواست</label>
                <div class="fw-semibold">{{ \Morilog\Jalali\Jalalian::fromDateTime($req->created_at)->format('Y/m/d') }}</div>
            </div>
            <div class="col-md-3">
                <label class="form-label text-muted small mb-1">استان</label>
                <div class="fw-semibold">{{ $req->province }}</div>
            </div>
            <div class="col-md-3">
                <label class="form-label text-muted small mb-1">شهر</label>
                <div class="fw-semibold">{{ $req->city }}</div>
            </div>
            <div class="col-md-6">
                <label class="form-label text-muted small mb-1">آدرس محل احداث</label>
                <div class="fw-semibold">{{ $req->address }}</div>
            </div>
            <div class="col-md-3">
                <label class="form-label text-muted small mb-1">ظرفیت درخواستی متقاضی</label>
                <div class="fw-semibold" style="color:#E65100;">{{ $req->capacity_kw ?? '-' }} kW</div>
            </div>
            <div class="col-md-3">
                <label class="form-label text-muted small mb-1">نام کارشناس</label>
                <div class="fw-semibold">{{ auth()->user()->name }}</div>
            </div>
            <div class="col-md-3">
                <label class="form-label text-muted small mb-1">تاریخ بازدید <span class="text-danger">*</span></label>
                <input type="text" name="visit_date" id="visit_date"
                       class="form-control persian-date @error('visit_date') is-invalid @enderror"
                       value="{{ old('visit_date') }}"
                       placeholder="مثال: ۱۴۰۵/۰۵/۲۱"
                       style="border-radius:8px;">
                @error('visit_date')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>
</div>
