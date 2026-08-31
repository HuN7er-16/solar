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

        <div class="mb-4 p-4 text-white" style="background: linear-gradient(135deg, #64B5F6 0%, #1976D2 100%); border-radius: 12px; box-shadow: 0 4px 20px rgba(25, 118, 210, 0.25);">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h3 class="mb-1 fw-bold"><i class="fa fa-user-plus ms-2"></i>افزودن پیمانکار جدید</h3>
                    <p class="mb-0 opacity-90">اطلاعات شرکت، مدیر عامل و پروانه فعالیت را ثبت کنید</p>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn text-white" id="fillLastRecord" style="background: rgba(255,255,255,0.2); border-radius: 12px; backdrop-filter: blur(10px); font-weight: 600;">
                        <i class="fa fa-copy ms-1"></i> پر کردن با آخرین رکورد
                    </button>
                    <a href="{{ route('contractor-catalog.index') }}" class="btn btn-light" style="border-radius: 12px; color: #1976D2; font-weight: 600;">
                        <i class="fa fa-arrow-right ms-1"></i> بازگشت
                    </a>
                </div>
            </div>
        </div>

        <div class="card" style="border-radius: 12px; border: none; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
            <div class="card-body p-5">
                <form method="POST" action="{{ route('contractor-catalog.store') }}" enctype="multipart/form-data">
                    @csrf

                    <fieldset class="mb-5">
                        <div class="d-flex align-items-center mb-4">
                            <div class="me-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: linear-gradient(135deg, #64B5F6 0%, #1976D2 100%); border-radius: 12px;">
                                <i class="fa fa-building text-white" style="font-size: 20px;"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold" style="color: #1565C0;">اطلاعات شرکت</h5>
                                <p class="mb-0 text-muted small">مشخصات هویتی و تماس شرکت</p>
                            </div>
                        </div>
                        <div style="height: 3px; background: linear-gradient(90deg, #64B5F6, #1976D2, #FF9800); border-radius: 3px; margin-bottom: 24px;"></div>
                        <div class="row g-4">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold mb-2" style="color: #37474F;">نام شرکت <span class="text-danger">*</span></label>
                                <input type="text" name="company_name" class="form-control form-control-lg" value="{{ old('company_name') }}" required style="border-radius: 10px; border: 2px solid #E0E0E0; padding: 12px 16px; transition: all 0.2s;" onfocus="this.style.borderColor='#64B5F6'; this.style.boxShadow='0 0 0 3px rgba(100,181,246,0.15)';" onblur="this.style.borderColor='#E0E0E0'; this.style.boxShadow='none';">
                                @error('company_name') <div class="text-danger small mt-2"><i class="fa fa-exclamation-circle ms-1"></i>{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold mb-2" style="color: #37474F;">شناسه ملی شرکت <span class="text-danger">*</span></label>
                                <input type="text" name="national_id" class="form-control form-control-lg" value="{{ old('national_id') }}" required maxlength="11" placeholder="۱۱ رقم" style="border-radius: 10px; border: 2px solid #E0E0E0; padding: 12px 16px; font-family: 'Vazir', monospace; transition: all 0.2s;" onfocus="this.style.borderColor='#64B5F6'; this.style.boxShadow='0 0 0 3px rgba(100,181,246,0.15)';" onblur="this.style.borderColor='#E0E0E0'; this.style.boxShadow='none';">
                                @error('national_id') <div class="text-danger small mt-2"><i class="fa fa-exclamation-circle ms-1"></i>{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold mb-2" style="color: #37474F;">تلفن شرکت</label>
                                <input type="text" name="company_phone" class="form-control form-control-lg" value="{{ old('company_phone') }}" maxlength="11" placeholder="مثال: ۰۲۱XXXXXXX" style="border-radius: 10px; border: 2px solid #E0E0E0; padding: 12px 16px; font-family: 'Vazir', monospace; transition: all 0.2s;" onfocus="this.style.borderColor='#64B5F6'; this.style.boxShadow='0 0 0 3px rgba(100,181,246,0.15)';" onblur="this.style.borderColor='#E0E0E0'; this.style.boxShadow='none';">
                                @error('company_phone') <div class="text-danger small mt-2"><i class="fa fa-exclamation-circle ms-1"></i>{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="mb-5">
                        <div class="d-flex align-items-center mb-4">
                            <div class="me-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: linear-gradient(135deg, #FFB74D 0%, #FF9800 100%); border-radius: 12px;">
                                <i class="fa fa-user-tie text-white" style="font-size: 20px;"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold" style="color: #E65100;">اطلاعات مدیر عامل</h5>
                                <p class="mb-0 text-muted small">مشخصات مدیرعامل شرکت</p>
                            </div>
                        </div>
                        <div style="height: 3px; background: linear-gradient(90deg, #FFB74D, #FF9800, #F57C00); border-radius: 3px; margin-bottom: 24px;"></div>
                        <div class="row g-4">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold mb-2" style="color: #37474F;">نام مدیر عامل <span class="text-danger">*</span></label>
                                <input type="text" name="ceo_name" class="form-control form-control-lg" value="{{ old('ceo_name') }}" required style="border-radius: 10px; border: 2px solid #E0E0E0; padding: 12px 16px; transition: all 0.2s;" onfocus="this.style.borderColor='#FFB74D'; this.style.boxShadow='0 0 0 3px rgba(255,183,77,0.15)';" onblur="this.style.borderColor='#E0E0E0'; this.style.boxShadow='none';">
                                @error('ceo_name') <div class="text-danger small mt-2"><i class="fa fa-exclamation-circle ms-1"></i>{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold mb-2" style="color: #37474F;">کد ملی مدیر عامل <span class="text-danger">*</span></label>
                                <input type="text" name="ceo_national_code" class="form-control form-control-lg" value="{{ old('ceo_national_code') }}" required maxlength="10" placeholder="۱۰ رقم" style="border-radius: 10px; border: 2px solid #E0E0E0; padding: 12px 16px; font-family: 'Vazir', monospace; transition: all 0.2s;" onfocus="this.style.borderColor='#FFB74D'; this.style.boxShadow='0 0 0 3px rgba(255,183,77,0.15)';" onblur="this.style.borderColor='#E0E0E0'; this.style.boxShadow='none';">
                                @error('ceo_national_code') <div class="text-danger small mt-2"><i class="fa fa-exclamation-circle ms-1"></i>{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold mb-2" style="color: #37474F;">تلفن همراه مدیر عامل <span class="text-danger">*</span></label>
                                <input type="text" name="ceo_mobile" class="form-control form-control-lg" value="{{ old('ceo_mobile') }}" required maxlength="11" placeholder="۰۹XXXXXXXXX" style="border-radius: 10px; border: 2px solid #E0E0E0; padding: 12px 16px; font-family: 'Vazir', monospace; transition: all 0.2s;" onfocus="this.style.borderColor='#FFB74D'; this.style.boxShadow='0 0 0 3px rgba(255,183,77,0.15)';" onblur="this.style.borderColor='#E0E0E0'; this.style.boxShadow='none';">
                                @error('ceo_mobile') <div class="text-danger small mt-2"><i class="fa fa-exclamation-circle ms-1"></i>{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="mb-5">
                        <div class="d-flex align-items-center mb-4">
                            <div class="me-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: linear-gradient(135deg, #81C784 0%, #4CAF50 100%); border-radius: 12px;">
                                <i class="fa fa-user text-white" style="font-size: 20px;"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold" style="color: #2E7D32;">اطلاعات شخص رابط</h5>
                                <p class="mb-0 text-muted small">شخصی برای همکاری و ارتباط</p>
                            </div>
                        </div>
                        <div style="height: 3px; background: linear-gradient(90deg, #81C784, #4CAF50, #388E3C); border-radius: 3px; margin-bottom: 24px;"></div>
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold mb-2" style="color: #37474F;">نام شخص رابط <span class="text-danger">*</span></label>
                                <input type="text" name="contact_person_name" class="form-control form-control-lg" value="{{ old('contact_person_name') }}" required style="border-radius: 10px; border: 2px solid #E0E0E0; padding: 12px 16px; transition: all 0.2s;" onfocus="this.style.borderColor='#81C784'; this.style.boxShadow='0 0 0 3px rgba(129,199,132,0.15)';" onblur="this.style.borderColor='#E0E0E0'; this.style.boxShadow='none';">
                                @error('contact_person_name') <div class="text-danger small mt-2"><i class="fa fa-exclamation-circle ms-1"></i>{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold mb-2" style="color: #37474F;">شماره همراه شخص رابط <span class="text-danger">*</span></label>
                                <input type="text" name="contact_person_mobile" class="form-control form-control-lg" value="{{ old('contact_person_mobile') }}" required maxlength="11" placeholder="۰۹XXXXXXXXX" style="border-radius: 10px; border: 2px solid #E0E0E0; padding: 12px 16px; font-family: 'Vazir', monospace; transition: all 0.2s;" onfocus="this.style.borderColor='#81C784'; this.style.boxShadow='0 0 0 3px rgba(129,199,132,0.15)';" onblur="this.style.borderColor='#E0E0E0'; this.style.boxShadow='none';">
                                @error('contact_person_mobile') <div class="text-danger small mt-2"><i class="fa fa-exclamation-circle ms-1"></i>{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="mb-5">
                        <div class="d-flex align-items-center mb-4">
                            <div class="me-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: linear-gradient(135deg, #BA68C8 0%, #9C27B0 100%); border-radius: 12px;">
                                <i class="fa fa-map-marker-alt text-white" style="font-size: 20px;"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold" style="color: #7B1FA2;">آدرس شرکت</h5>
                                <p class="mb-0 text-muted small">استان، شهر و آدرس کامل</p>
                            </div>
                        </div>
                        <div style="height: 3px; background: linear-gradient(90deg, #BA68C8, #9C27B0, #7B1FA2); border-radius: 3px; margin-bottom: 24px;"></div>
                        <div class="row g-4">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold mb-2" style="color: #37474F;">استان <span class="text-danger">*</span></label>
                                <select name="province" class="form-control form-control-lg select2" required id="province_select" style="border-radius: 10px; border: 2px solid #E0E0E0; padding: 12px 16px; transition: all 0.2s;">
                                    <option value="">-- انتخاب کنید --</option>
                                    @foreach($provinces as $province)
                                        <option value="{{ $province }}" {{ old('province') == $province ? 'selected' : '' }}>{{ $province }}</option>
                                    @endforeach
                                </select>
                                @error('province') <div class="text-danger small mt-2"><i class="fa fa-exclamation-circle ms-1"></i>{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold mb-2" style="color: #37474F;">شهر <span class="text-danger">*</span></label>
                                <select name="city" id="city_select" class="form-control form-control-lg select2 @error('city') is-invalid @enderror" required style="border-radius: 10px; border: 2px solid #E0E0E0; padding: 12px 16px;">
                                    <option value="">ابتدا استان را انتخاب کنید</option>
                                </select>
                                @error('city') <div class="text-danger small mt-2"><i class="fa fa-exclamation-circle ms-1"></i>{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold mb-2" style="color: #37474F;">آدرس کامل <span class="text-danger">*</span></label>
                                <textarea name="address" class="form-control form-control-lg" rows="3" required style="border-radius: 10px; border: 2px solid #E0E0E0; padding: 12px 16px; resize: vertical; transition: all 0.2s;" onfocus="this.style.borderColor='#BA68C8'; this.style.boxShadow='0 0 0 3px rgba(186,104,200,0.15)';" onblur="this.style.borderColor='#E0E0E0'; this.style.boxShadow='none';">{{ old('address') }}</textarea>
                                @error('address') <div class="text-danger small mt-2"><i class="fa fa-exclamation-circle ms-1"></i>{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="mb-5">
                        <div class="d-flex align-items-center mb-4">
                            <div class="me-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: linear-gradient(135deg, #4DD0E1 0%, #00BCD4 100%); border-radius: 12px;">
                                <i class="fa fa-certificate text-white" style="font-size: 20px;"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold" style="color: #00838F;">پروانه فعالیت</h5>
                                <p class="mb-0 text-muted small">اطلاعات پروانه کسب و معتبر بودن آن</p>
                            </div>
                        </div>
                        <div style="height: 3px; background: linear-gradient(90deg, #4DD0E1, #00BCD4, #0097A7); border-radius: 3px; margin-bottom: 24px;"></div>
                        <div class="row g-4">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold mb-2" style="color: #37474F;">شماره پروانه کسب <span class="text-danger">*</span></label>
                                <input type="text" name="license_number" class="form-control form-control-lg" value="{{ old('license_number') }}" required style="border-radius: 10px; border: 2px solid #E0E0E0; padding: 12px 16px; transition: all 0.2s;" onfocus="this.style.borderColor='#4DD0E1'; this.style.boxShadow='0 0 0 3px rgba(77,208,225,0.15)';" onblur="this.style.borderColor='#E0E0E0'; this.style.boxShadow='none';">
                                @error('license_number') <div class="text-danger small mt-2"><i class="fa fa-exclamation-circle ms-1"></i>{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold mb-2" style="color: #37474F;">تاریخ صدور پروانه <span class="text-danger">*</span></label>
                                <input type="text" name="license_issue_date" class="form-control form-control-lg persian-date" value="{{ old('license_issue_date') }}" required placeholder="مثال: 1400/01/01" style="border-radius: 10px; border: 2px solid #E0E0E0; padding: 12px 16px; font-family: 'Vazir', monospace; transition: all 0.2s;" onfocus="this.style.borderColor='#4DD0E1'; this.style.boxShadow='0 0 0 3px rgba(77,208,225,0.15)';" onblur="this.style.borderColor='#E0E0E0'; this.style.boxShadow='none';">
                                @error('license_issue_date') <div class="text-danger small mt-2"><i class="fa fa-exclamation-circle ms-1"></i>{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold mb-2" style="color: #37474F;">تاریخ انقضای پروانه <span class="text-danger">*</span></label>
                                <input type="text" name="license_expiry_date" class="form-control form-control-lg persian-date" value="{{ old('license_expiry_date') }}" required placeholder="مثال: 1405/01/01" style="border-radius: 10px; border: 2px solid #E0E0E0; padding: 12px 16px; font-family: 'Vazir', monospace; transition: all 0.2s;" onfocus="this.style.borderColor='#4DD0E1'; this.style.boxShadow='0 0 0 3px rgba(77,208,225,0.15)';" onblur="this.style.borderColor='#E0E0E0'; this.style.boxShadow='none';">
                                @error('license_expiry_date') <div class="text-danger small mt-2"><i class="fa fa-exclamation-circle ms-1"></i>{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="mb-5">
                        <div class="d-flex align-items-center mb-4">
                            <div class="me-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: linear-gradient(135deg, #FFB74D 0%, #FF9800 100%); border-radius: 12px;">
                                <i class="fa fa-chart-bar text-white" style="font-size: 20px;"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold" style="color: #E65100;">آمار پروژه‌ها</h5>
                                <p class="mb-0 text-muted small">تعداد پروژه‌های ثبت شده توسط پیمانکار</p>
                            </div>
                        </div>
                        <div style="height: 3px; background: linear-gradient(90deg, #FFB74D, #FF9800); border-radius: 3px; margin-bottom: 24px;"></div>
                        <div class="row g-4">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold mb-2" style="color: #37474F;">تعداد پروژه‌های ثبت شده</label>
                                <input type="number" name="registered_projects_count" class="form-control form-control-lg" value="{{ old('registered_projects_count', 0) }}" min="0" style="border-radius: 10px; border: 2px solid #E0E0E0; padding: 12px 16px; font-family: 'Vazir', monospace; transition: all 0.2s;" onfocus="this.style.borderColor='#FFB74D'; this.style.boxShadow='0 0 0 3px rgba(255,183,77,0.15)';" onblur="this.style.borderColor='#E0E0E0'; this.style.boxShadow='none';">
                                @error('registered_projects_count') <div class="text-danger small mt-2"><i class="fa fa-exclamation-circle ms-1"></i>{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="mb-5">
                        <div class="d-flex align-items-center mb-4">
                            <div class="me-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: linear-gradient(135deg, #FF8A65 0%, #FF5722 100%); border-radius: 12px;">
                                <i class="fa fa-file-upload text-white" style="font-size: 20px;"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold" style="color: #BF360C;">مستندات و فایل‌ها</h5>
                                <p class="mb-0 text-muted small">آپلود مدارک و مستندات مرتبط (اختیاری)</p>
                            </div>
                        </div>
                        <div style="height: 3px; background: linear-gradient(90deg, #FF8A65, #FF5722); border-radius: 3px; margin-bottom: 24px;"></div>
                        <div class="row g-4">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold mb-2" style="color: #37474F;">اسکن پروانه کسب</label>
                                <input type="file" name="license_file" class="form-control form-control-lg" accept=".pdf,.jpg,.jpeg,.png" style="border-radius: 10px; border: 2px dashed #FF8A65; padding: 14px; background: #FFF3E0;">
                                <small class="text-muted d-block mt-1">PDF, JPG, PNG - حداکثر ۱۰ مگابایت</small>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold mb-2" style="color: #37474F;">آدرس پوستر شرکت</label>
                                <input type="text" name="poster_image" class="form-control form-control-lg" value="{{ old('poster_image') }}" placeholder="لینک تصویر یا آپلود..." style="border-radius: 10px; border: 2px dashed #FF8A65; padding: 12px 16px; background: #FFF3E0;">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold mb-2" style="color: #37474F;">سایر مدارک</label>
                                <input type="file" name="other_documents[]" class="form-control form-control-lg" multiple accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" style="border-radius: 10px; border: 2px dashed #FF8A65; padding: 14px; background: #FFF3E0;">
                                <small class="text-muted d-block mt-1">چند فایل را همزمان انتخاب کنید</small>
                            </div>
                        </div>
                    </fieldset>

                    <div class="mt-5 d-flex gap-3 justify-content-end flex-wrap">
                        <a href="{{ route('contractor-catalog.index') }}" class="btn btn-lg" style="border-radius: 12px; background: #F5F5F5; color: #546E7A; font-weight: 600; padding: 12px 32px;">
                            <i class="fa fa-arrow-right ms-1"></i> بازگشت
                        </a>
                        <button type="submit" class="btn btn-lg text-white" style="background: linear-gradient(135deg, #64B5F6 0%, #1976D2 100%); border-radius: 12px; font-weight: 700; padding: 12px 40px; box-shadow: 0 4px 15px rgba(25, 118, 210, 0.3);">
                            <i class="fa fa-save ms-1"></i> ثبت پیمانکار
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

        $('#fillLastRecord').on('click', function() {
            var btn = $(this);
            btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin ms-1"></i> در حال دریافت...');

            $.ajax({
                url: '{{ route("contractor-catalog.last-record") }}',
                method: 'GET',
                success: function(data) {
                    if (data) {
                        var textFields = [
                            'company_name', 'ceo_name', 'contact_person_name',
                            'city', 'address', 'company_phone'
                        ];
                        textFields.forEach(function(field) {
                            if (data[field]) {
                                $('[name="' + field + '"]').val(data[field]);
                            }
                        });

                        if (data.province) {
                            $('select[name="province"]').val(data.province).trigger('change');
                        }

                        if (typeof toastr !== 'undefined') {
                            toastr.success('فیلدها با آخرین رکورد پر شدند');
                        }
                    } else {
                        if (typeof toastr !== 'undefined') toastr.info('رکوردی یافت نشد');
                    }
                },
                error: function() {
                    if (typeof toastr !== 'undefined') toastr.error('خطا در دریافت اطلاعات');
                },
                complete: function() {
                    btn.prop('disabled', false).html('<i class="fa fa-copy ms-1"></i> پر کردن با آخرین رکورد');
                }
            });
        });
    });
</script>
@endsection
