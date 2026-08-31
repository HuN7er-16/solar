@extends('behin-layouts.app')

@section('content')
    <div class="container-fluid" style="direction: rtl; text-align: right;">
        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 12px; border: none; background: linear-gradient(135deg, #C8E6C9 0%, #A5D6A7 100%); color: #1B5E20;">
                <i class="fa fa-check-circle ms-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" style="float: left;"></button>
            </div>
        @endif
        @if (session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 12px; border: none; background: linear-gradient(135deg, #FFCDD2 0%, #EF9A9A 100%); color: #B71C1C;">
                <i class="fa fa-exclamation-circle ms-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" style="float: left;"></button>
            </div>
        @endif

        <div class="mb-4 p-4 text-white" style="background: linear-gradient(135deg, #4DB6AC 0%, #26A69A 100%); border-radius: 12px; box-shadow: 0 4px 20px rgba(38, 166, 154, 0.25);">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h3 class="mb-1 fw-bold"><i class="fa fa-user-plus ms-2"></i>افزودن بازرس جدید</h3>
                    <p class="mb-0 opacity-90">ثبت اطلاعات شخصی، شغلی و دسترسی‌های بازرس کنترل کیفیت</p>
                </div>
                <a href="{{ route('inspector-catalog.index') }}" class="btn btn-light" style="border-radius: 12px; color: #00695C; font-weight: 600;">
                    <i class="fa fa-arrow-right ms-1"></i> بازگشت
                </a>
            </div>
        </div>

        <div class="card" style="border-radius: 12px; border: none; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
            <div class="card-body p-5">
                <form method="POST" action="{{ route('inspector-catalog.store') }}" enctype="multipart/form-data">
                    @csrf

                    <fieldset class="mb-5">
                        <div class="d-flex align-items-center mb-4">
                            <div class="me-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: linear-gradient(135deg, #64B5F6 0%, #1976D2 100%); border-radius: 12px;">
                                <i class="fa fa-user-circle text-white" style="font-size: 20px;"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold" style="color: #1565C0;">انتخاب کاربر</h5>
                                <p class="mb-0 text-muted small">انتخاب حساب کاربری برای دسترسی بازرس به سیستم</p>
                            </div>
                        </div>
                        <div style="height: 3px; background: linear-gradient(90deg, #64B5F6, #1976D2, #4DB6AC); border-radius: 3px; margin-bottom: 24px;"></div>
                        <div class="row g-4">
                            <div class="col-md-8">
                                <label class="form-label fw-semibold mb-2" style="color: #37474F;">کاربر <span class="text-danger">*</span></label>
                                <select name="user_id" class="form-control form-control-lg select2 @error('user_id') is-invalid @enderror" required style="border-radius: 10px; border: 2px solid #E0E0E0; padding: 12px 16px;">
                                    <option value="">-- کاربر مورد نظر را انتخاب کنید --</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} — {{ $user->email }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_id') <div class="text-danger small mt-2"><i class="fa fa-exclamation-circle ms-1"></i>{{ $message }}</div> @enderror
                                <small class="text-muted d-block mt-2" style="font-size: 13px;"><i class="fa fa-info-circle ms-1" style="color: #26A69A;"></i>فقط کاربرانی که هنوز پروفایل بازرس ندارند نمایش داده می‌شوند.</small>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="mb-5">
                        <div class="d-flex align-items-center mb-4">
                            <div class="me-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: linear-gradient(135deg, #4DB6AC 0%, #26A69A 100%); border-radius: 12px;">
                                <i class="fa fa-id-card text-white" style="font-size: 20px;"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold" style="color: #00695C;">اطلاعات هویتی بازرس</h5>
                                <p class="mb-0 text-muted small">کد بازرس و مشخصات هویتی</p>
                            </div>
                        </div>
                        <div style="height: 3px; background: linear-gradient(90deg, #4DB6AC, #26A69A, #00897B); border-radius: 3px; margin-bottom: 24px;"></div>
                        <div class="row g-4">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold mb-2" style="color: #37474F;">کد بازرس <span class="text-danger">*</span></label>
                                <input type="text" name="inspector_code" class="form-control form-control-lg @error('inspector_code') is-invalid @enderror" value="{{ old('inspector_code') }}" required style="border-radius: 10px; border: 2px solid #E0E0E0; padding: 12px 16px; font-family: 'Vazir', monospace; transition: all 0.2s;" onfocus="this.style.borderColor='#4DB6AC'; this.style.boxShadow='0 0 0 3px rgba(77,182,172,0.15)';" onblur="this.style.borderColor='#E0E0E0'; this.style.boxShadow='none';">
                                @error('inspector_code') <div class="text-danger small mt-2"><i class="fa fa-exclamation-circle ms-1"></i>{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold mb-2" style="color: #37474F;">نام <span class="text-danger">*</span></label>
                                <input type="text" name="first_name" class="form-control form-control-lg @error('first_name') is-invalid @enderror" value="{{ old('first_name') }}" required style="border-radius: 10px; border: 2px solid #E0E0E0; padding: 12px 16px; transition: all 0.2s;" onfocus="this.style.borderColor='#4DB6AC'; this.style.boxShadow='0 0 0 3px rgba(77,182,172,0.15)';" onblur="this.style.borderColor='#E0E0E0'; this.style.boxShadow='none';">
                                @error('first_name') <div class="text-danger small mt-2"><i class="fa fa-exclamation-circle ms-1"></i>{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold mb-2" style="color: #37474F;">نام خانوادگی <span class="text-danger">*</span></label>
                                <input type="text" name="last_name" class="form-control form-control-lg @error('last_name') is-invalid @enderror" value="{{ old('last_name') }}" required style="border-radius: 10px; border: 2px solid #E0E0E0; padding: 12px 16px; transition: all 0.2s;" onfocus="this.style.borderColor='#4DB6AC'; this.style.boxShadow='0 0 0 3px rgba(77,182,172,0.15)';" onblur="this.style.borderColor='#E0E0E0'; this.style.boxShadow='none';">
                                @error('last_name') <div class="text-danger small mt-2"><i class="fa fa-exclamation-circle ms-1"></i>{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold mb-2" style="color: #37474F;">کد ملی <span class="text-danger">*</span></label>
                                <input type="text" name="national_id" class="form-control form-control-lg @error('national_id') is-invalid @enderror" value="{{ old('national_id') }}" required maxlength="10" placeholder="۱۰ رقم" style="border-radius: 10px; border: 2px solid #E0E0E0; padding: 12px 16px; font-family: 'Vazir', monospace; transition: all 0.2s;" onfocus="this.style.borderColor='#4DB6AC'; this.style.boxShadow='0 0 0 3px rgba(77,182,172,0.15)';" onblur="this.style.borderColor='#E0E0E0'; this.style.boxShadow='none';">
                                @error('national_id') <div class="text-danger small mt-2"><i class="fa fa-exclamation-circle ms-1"></i>{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="mb-5">
                        <div class="d-flex align-items-center mb-4">
                            <div class="me-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: linear-gradient(135deg, #81C784 0%, #4CAF50 100%); border-radius: 12px;">
                                <i class="fa fa-phone text-white" style="font-size: 20px;"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold" style="color: #2E7D32;">اطلاعات تماس</h5>
                                <p class="mb-0 text-muted small">شماره موبایل، تلفن ثابت و ایمیل</p>
                            </div>
                        </div>
                        <div style="height: 3px; background: linear-gradient(90deg, #81C784, #4CAF50, #388E3C); border-radius: 3px; margin-bottom: 24px;"></div>
                        <div class="row g-4">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold mb-2" style="color: #37474F;">شماره همراه <span class="text-danger">*</span></label>
                                <input type="text" name="mobile" class="form-control form-control-lg @error('mobile') is-invalid @enderror" value="{{ old('mobile') }}" required maxlength="11" placeholder="۰۹XXXXXXXXX" style="border-radius: 10px; border: 2px solid #E0E0E0; padding: 12px 16px; font-family: 'Vazir', monospace; transition: all 0.2s;" onfocus="this.style.borderColor='#81C784'; this.style.boxShadow='0 0 0 3px rgba(129,199,132,0.15)';" onblur="this.style.borderColor='#E0E0E0'; this.style.boxShadow='none';">
                                @error('mobile') <div class="text-danger small mt-2"><i class="fa fa-exclamation-circle ms-1"></i>{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold mb-2" style="color: #37474F;">تلفن ثابت</label>
                                <input type="text" name="phone" class="form-control form-control-lg @error('phone') is-invalid @enderror" value="{{ old('phone') }}" maxlength="11" placeholder="مثال: ۰۲۱XXXXXXX" style="border-radius: 10px; border: 2px solid #E0E0E0; padding: 12px 16px; font-family: 'Vazir', monospace; transition: all 0.2s;" onfocus="this.style.borderColor='#81C784'; this.style.boxShadow='0 0 0 3px rgba(129,199,132,0.15)';" onblur="this.style.borderColor='#E0E0E0'; this.style.boxShadow='none';">
                                @error('phone') <div class="text-danger small mt-2"><i class="fa fa-exclamation-circle ms-1"></i>{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold mb-2" style="color: #37474F;">ایمیل</label>
                                <input type="email" name="email_extra" class="form-control form-control-lg" value="{{ old('email_extra') }}" placeholder="example@email.com" style="border-radius: 10px; border: 2px solid #E0E0E0; padding: 12px 16px; transition: all 0.2s;" onfocus="this.style.borderColor='#81C784'; this.style.boxShadow='0 0 0 3px rgba(129,199,132,0.15)';" onblur="this.style.borderColor='#E0E0E0'; this.style.boxShadow='none';">
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="mb-5">
                        <div class="d-flex align-items-center mb-4">
                            <div class="me-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: linear-gradient(135deg, #FFB74D 0%, #FF9800 100%); border-radius: 12px;">
                                <i class="fa fa-briefcase text-white" style="font-size: 20px;"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold" style="color: #E65100;">اطلاعات شغلی</h5>
                                <p class="mb-0 text-muted small">نظام مهندسی، رشته تحصیلی و محل فعالیت</p>
                            </div>
                        </div>
                        <div style="height: 3px; background: linear-gradient(90deg, #FFB74D, #FF9800, #F57C00); border-radius: 3px; margin-bottom: 24px;"></div>
                        <div class="row g-4">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold mb-2" style="color: #37474F;">شماره نظام مهندسی</label>
                                <input type="text" name="engineering_system_number" class="form-control form-control-lg" value="{{ old('engineering_system_number') }}" placeholder="در صورت وجود" style="border-radius: 10px; border: 2px solid #E0E0E0; padding: 12px 16px; font-family: 'Vazir', monospace; transition: all 0.2s;" onfocus="this.style.borderColor='#FFB74D'; this.style.boxShadow='0 0 0 3px rgba(255,183,77,0.15)';" onblur="this.style.borderColor='#E0E0E0'; this.style.boxShadow='none';">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold mb-2" style="color: #37474F;">رشته تحصیلی</label>
                                <input type="text" name="field_of_study" class="form-control form-control-lg" value="{{ old('field_of_study') }}" placeholder="مثال: عمران، برق، مکانیک" style="border-radius: 10px; border: 2px solid #E0E0E0; padding: 12px 16px; transition: all 0.2s;" onfocus="this.style.borderColor='#FFB74D'; this.style.boxShadow='0 0 0 3px rgba(255,183,77,0.15)';" onblur="this.style.borderColor='#E0E0E0'; this.style.boxShadow='none';">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold mb-2" style="color: #37474F;">آدرس محل فعالیت</label>
                                <input type="text" name="work_address" class="form-control form-control-lg" value="{{ old('work_address') }}" placeholder="آدرس دفتر کار" style="border-radius: 10px; border: 2px solid #E0E0E0; padding: 12px 16px; transition: all 0.2s;" onfocus="this.style.borderColor='#FFB74D'; this.style.boxShadow='0 0 0 3px rgba(255,183,77,0.15)';" onblur="this.style.borderColor='#E0E0E0'; this.style.boxShadow='none';">
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="mb-5">
                        <div class="d-flex align-items-center mb-4">
                            <div class="me-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: linear-gradient(135deg, #BA68C8 0%, #9C27B0 100%); border-radius: 12px;">
                                <i class="fa fa-map-marker-alt text-white" style="font-size: 20px;"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold" style="color: #7B1FA2;">محل فعالیت</h5>
                                <p class="mb-0 text-muted small">استان، شهر و آدرس دقیق</p>
                            </div>
                        </div>
                        <div style="height: 3px; background: linear-gradient(90deg, #BA68C8, #9C27B0, #7B1FA2); border-radius: 3px; margin-bottom: 24px;"></div>
                        <div class="row g-4">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold mb-2" style="color: #37474F;">استان محل فعالیت <span class="text-danger">*</span></label>
                                <select name="province" class="form-control form-control-lg select2 @error('province') is-invalid @enderror" required id="province_select" style="border-radius: 10px; border: 2px solid #E0E0E0; padding: 12px 16px;">
                                    <option value="">-- انتخاب کنید --</option>
                                    @foreach($provinces as $province)
                                        <option value="{{ $province }}" {{ old('province') == $province ? 'selected' : '' }}>{{ $province }}</option>
                                    @endforeach
                                </select>
                                @error('province') <div class="text-danger small mt-2"><i class="fa fa-exclamation-circle ms-1"></i>{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold mb-2" style="color: #37474F;">شهر محل فعالیت <span class="text-danger">*</span></label>
                                <select name="city" id="city_select" class="form-control form-control-lg select2 @error('city') is-invalid @enderror" required style="border-radius: 10px; border: 2px solid #E0E0E0; padding: 12px 16px;">
                                    <option value="">ابتدا استان را انتخاب کنید</option>
                                </select>
                                @error('city') <div class="text-danger small mt-2"><i class="fa fa-exclamation-circle ms-1"></i>{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold mb-2" style="color: #37474F;">آدرس سکونت <span class="text-danger">*</span></label>
                                <textarea name="address" class="form-control form-control-lg @error('address') is-invalid @enderror" rows="3" required style="border-radius: 10px; border: 2px solid #E0E0E0; padding: 12px 16px; resize: vertical; transition: all 0.2s;" onfocus="this.style.borderColor='#BA68C8'; this.style.boxShadow='0 0 0 3px rgba(186,104,200,0.15)';" onblur="this.style.borderColor='#E0E0E0'; this.style.boxShadow='none';">{{ old('address') }}</textarea>
                                @error('address') <div class="text-danger small mt-2"><i class="fa fa-exclamation-circle ms-1"></i>{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="mb-5">
                        <div class="d-flex align-items-center mb-4">
                            <div class="me-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: linear-gradient(135deg, #FF8A65 0%, #FF5722 100%); border-radius: 12px;">
                                <i class="fa fa-certificate text-white" style="font-size: 20px;"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold" style="color: #BF360C;">دسترسی‌ها و وضعیت</h5>
                                <p class="mb-0 text-muted small">گواهی صلاحیت و وضعیت فعال بودن</p>
                            </div>
                        </div>
                        <div style="height: 3px; background: linear-gradient(90deg, #FF8A65, #FF5722, #E64A19); border-radius: 3px; margin-bottom: 24px;"></div>
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold mb-2" style="color: #37474F;">دارای گواهی صلاحیت حرفه‌ای می‌باشد؟</label>
                                <div class="d-flex gap-4 mt-1 p-3" style="background: #FFF3E0; border-radius: 10px;">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="is_certificated" id="cert_yes_create" value="1" {{ old('is_certificated') == '1' ? 'checked' : '' }} style="width: 20px; height: 20px; cursor: pointer;">
                                        <label class="form-check-label fw-semibold me-2" for="cert_yes_create" style="color: #2E7D32; cursor: pointer; font-size: 15px;">
                                            <i class="fa fa-check-circle ms-1"></i> بلی
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="is_certificated" id="cert_no_create" value="0" {{ old('is_certificated', '0') == '0' ? 'checked' : '' }} style="width: 20px; height: 20px; cursor: pointer;">
                                        <label class="form-check-label fw-semibold me-2" for="cert_no_create" style="color: #C62828; cursor: pointer; font-size: 15px;">
                                            <i class="fa fa-times-circle ms-1"></i> خیر
                                        </label>
                                    </div>
                                </div>
                                @error('is_certificated') <div class="text-danger small mt-2"><i class="fa fa-exclamation-circle ms-1"></i>{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold mb-2" style="color: #37474F;">وضعیت فعال بودن حساب</label>
                                <div class="d-flex gap-4 mt-1 p-3" style="background: #E0F2F1; border-radius: 10px;">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="is_active" id="active_yes_create" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }} style="width: 20px; height: 20px; cursor: pointer;">
                                        <label class="form-check-label fw-semibold me-2" for="active_yes_create" style="color: #00695C; cursor: pointer; font-size: 15px;">
                                            <i class="fa fa-user-check ms-1"></i> فعال
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="is_active" id="active_no_create" value="0" {{ old('is_active') == '0' ? 'checked' : '' }} style="width: 20px; height: 20px; cursor: pointer;">
                                        <label class="form-check-label fw-semibold me-2" for="active_no_create" style="color: #546E7A; cursor: pointer; font-size: 15px;">
                                            <i class="fa fa-user-slash ms-1"></i> غیرفعال
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>

                    <div class="mt-5 d-flex gap-3 justify-content-end flex-wrap">
                        <a href="{{ route('inspector-catalog.index') }}" class="btn btn-lg" style="border-radius: 12px; background: #F5F5F5; color: #546E7A; font-weight: 600; padding: 12px 32px;">
                            <i class="fa fa-arrow-right ms-1"></i> بازگشت
                        </a>
                        <button type="submit" class="btn btn-lg text-white" style="background: linear-gradient(135deg, #4DB6AC 0%, #26A69A 100%); border-radius: 12px; font-weight: 700; padding: 12px 40px; box-shadow: 0 4px 15px rgba(38, 166, 154, 0.3);">
                            <i class="fa fa-save ms-1"></i> ثبت بازرس
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script src="{{ url('behin/behin-js/province-city-picker.js') }}"></script>
<script>
    $(document).ready(function() {
        initProvinceCityPicker('province_select', 'city_select', '{{ old('city') }}');
    });
</script>
@endsection
