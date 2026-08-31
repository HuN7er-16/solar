@extends('behin-layouts.app')

@section('content')
    <div class="container-fluid" style="direction: rtl; text-align: right;">

        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert"
                 style="border-radius:12px;border:none;background:linear-gradient(135deg,#C8E6C9,#A5D6A7);color:#1B5E20;">
                <i class="fa fa-check-circle ms-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" style="float:left;"></button>
            </div>
        @endif

        <div class="mb-4 p-4 text-white"
             style="background:linear-gradient(135deg,#7986CB 0%,#5C6BC0 100%);border-radius:12px;box-shadow:0 4px 20px rgba(92,107,192,0.25);">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h3 class="mb-1 fw-bold"><i class="fa fa-user-edit ms-2"></i>ویرایش کارشناس</h3>
                    <p class="mb-0 opacity-90">{{ $expert->first_name }} {{ $expert->last_name }}</p>
                </div>
                <a href="{{ route('expert-catalog.index') }}"
                   class="btn btn-light"
                   style="border-radius:12px;color:#3949AB;font-weight:600;">
                    <i class="fa fa-arrow-right ms-1"></i> بازگشت
                </a>
            </div>
        </div>

        <div class="card" style="border-radius:12px;border:none;box-shadow:0 4px 20px rgba(0,0,0,0.08);">
            <div class="card-body p-5">
                <form method="POST" action="{{ route('expert-catalog.update', $expert) }}">
                    @csrf
                    @method('PUT')

                    {{-- انتخاب کاربر --}}
                    <fieldset class="mb-5">
                        <div class="d-flex align-items-center mb-3">
                            <div class="me-3 d-flex align-items-center justify-content-center"
                                 style="width:48px;height:48px;background:linear-gradient(135deg,#7986CB,#5C6BC0);border-radius:12px;">
                                <i class="fa fa-user-circle text-white" style="font-size:20px;"></i>
                            </div>
                            <h5 class="mb-0 fw-bold" style="color:#283593;">انتخاب کاربر</h5>
                        </div>
                        <div style="height:3px;background:linear-gradient(90deg,#7986CB,#5C6BC0);border-radius:3px;margin-bottom:24px;"></div>
                        <div class="row g-4">
                            <div class="col-md-8">
                                <label class="form-label fw-semibold mb-2" style="color:#37474F;">کاربر <span class="text-danger">*</span></label>
                                <select name="user_id"
                                        class="form-control form-control-lg select2 @error('user_id') is-invalid @enderror"
                                        required
                                        style="border-radius:10px;border:2px solid #E0E0E0;padding:12px 16px;">
                                    <option value="">-- کاربر مورد نظر را انتخاب کنید --</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}"
                                            {{ old('user_id', $expert->user_id) == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} — {{ $user->email }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <div class="text-danger small mt-2"><i class="fa fa-exclamation-circle ms-1"></i>{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </fieldset>

                    {{-- اطلاعات هویتی --}}
                    <fieldset class="mb-5">
                        <div class="d-flex align-items-center mb-3">
                            <div class="me-3 d-flex align-items-center justify-content-center"
                                 style="width:48px;height:48px;background:linear-gradient(135deg,#7986CB,#5C6BC0);border-radius:12px;">
                                <i class="fa fa-id-card text-white" style="font-size:20px;"></i>
                            </div>
                            <h5 class="mb-0 fw-bold" style="color:#283593;">اطلاعات هویتی</h5>
                        </div>
                        <div style="height:3px;background:linear-gradient(90deg,#7986CB,#5C6BC0);border-radius:3px;margin-bottom:24px;"></div>
                        <div class="row g-4">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold mb-2" style="color:#37474F;">کد کارشناس <span class="text-danger">*</span></label>
                                <input type="text" name="expert_code"
                                       class="form-control form-control-lg @error('expert_code') is-invalid @enderror"
                                       value="{{ old('expert_code', $expert->expert_code) }}" required
                                       style="border-radius:10px;border:2px solid #E0E0E0;padding:12px 16px;font-family:'Vazir',monospace;">
                                @error('expert_code')
                                    <div class="text-danger small mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold mb-2" style="color:#37474F;">نام <span class="text-danger">*</span></label>
                                <input type="text" name="first_name"
                                       class="form-control form-control-lg @error('first_name') is-invalid @enderror"
                                       value="{{ old('first_name', $expert->first_name) }}" required
                                       style="border-radius:10px;border:2px solid #E0E0E0;padding:12px 16px;">
                                @error('first_name')
                                    <div class="text-danger small mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold mb-2" style="color:#37474F;">نام خانوادگی <span class="text-danger">*</span></label>
                                <input type="text" name="last_name"
                                       class="form-control form-control-lg @error('last_name') is-invalid @enderror"
                                       value="{{ old('last_name', $expert->last_name) }}" required
                                       style="border-radius:10px;border:2px solid #E0E0E0;padding:12px 16px;">
                                @error('last_name')
                                    <div class="text-danger small mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold mb-2" style="color:#37474F;">کد ملی <span class="text-danger">*</span></label>
                                <input type="text" name="national_id"
                                       class="form-control form-control-lg @error('national_id') is-invalid @enderror"
                                       value="{{ old('national_id', $expert->national_id) }}" required maxlength="10"
                                       style="border-radius:10px;border:2px solid #E0E0E0;padding:12px 16px;font-family:'Vazir',monospace;">
                                @error('national_id')
                                    <div class="text-danger small mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </fieldset>

                    {{-- اطلاعات تماس --}}
                    <fieldset class="mb-5">
                        <div class="d-flex align-items-center mb-3">
                            <div class="me-3 d-flex align-items-center justify-content-center"
                                 style="width:48px;height:48px;background:linear-gradient(135deg,#81C784,#4CAF50);border-radius:12px;">
                                <i class="fa fa-phone text-white" style="font-size:20px;"></i>
                            </div>
                            <h5 class="mb-0 fw-bold" style="color:#2E7D32;">اطلاعات تماس</h5>
                        </div>
                        <div style="height:3px;background:linear-gradient(90deg,#81C784,#4CAF50);border-radius:3px;margin-bottom:24px;"></div>
                        <div class="row g-4">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold mb-2" style="color:#37474F;">شماره همراه <span class="text-danger">*</span></label>
                                <input type="text" name="mobile"
                                       class="form-control form-control-lg @error('mobile') is-invalid @enderror"
                                       value="{{ old('mobile', $expert->mobile) }}" required maxlength="11"
                                       style="border-radius:10px;border:2px solid #E0E0E0;padding:12px 16px;font-family:'Vazir',monospace;">
                                @error('mobile')
                                    <div class="text-danger small mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold mb-2" style="color:#37474F;">تلفن ثابت</label>
                                <input type="text" name="phone"
                                       class="form-control form-control-lg @error('phone') is-invalid @enderror"
                                       value="{{ old('phone', $expert->phone) }}" maxlength="11"
                                       style="border-radius:10px;border:2px solid #E0E0E0;padding:12px 16px;font-family:'Vazir',monospace;">
                                @error('phone')
                                    <div class="text-danger small mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </fieldset>

                    {{-- محل فعالیت --}}
                    <fieldset class="mb-5">
                        <div class="d-flex align-items-center mb-3">
                            <div class="me-3 d-flex align-items-center justify-content-center"
                                 style="width:48px;height:48px;background:linear-gradient(135deg,#BA68C8,#9C27B0);border-radius:12px;">
                                <i class="fa fa-map-marker-alt text-white" style="font-size:20px;"></i>
                            </div>
                            <h5 class="mb-0 fw-bold" style="color:#7B1FA2;">محل فعالیت</h5>
                        </div>
                        <div style="height:3px;background:linear-gradient(90deg,#BA68C8,#9C27B0);border-radius:3px;margin-bottom:24px;"></div>
                        <div class="row g-4">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold mb-2" style="color:#37474F;">استان <span class="text-danger">*</span></label>
                                <select name="province" id="province_select"
                                        class="form-control form-control-lg select2 @error('province') is-invalid @enderror"
                                        required
                                        style="border-radius:10px;border:2px solid #E0E0E0;padding:12px 16px;">
                                    <option value="">-- انتخاب کنید --</option>
                                    @foreach($provinces as $province)
                                        <option value="{{ $province }}"
                                            {{ old('province', $expert->province) == $province ? 'selected' : '' }}>
                                            {{ $province }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('province')
                                    <div class="text-danger small mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold mb-2" style="color:#37474F;">شهر <span class="text-danger">*</span></label>
                                <select name="city" id="city_select"
                                        class="form-control form-control-lg select2 @error('city') is-invalid @enderror"
                                        required
                                        style="border-radius:10px;border:2px solid #E0E0E0;padding:12px 16px;">
                                    <option value="">ابتدا استان را انتخاب کنید</option>
                                </select>
                                @error('city')
                                    <div class="text-danger small mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold mb-2" style="color:#37474F;">آدرس <span class="text-danger">*</span></label>
                                <textarea name="address" rows="3"
                                          class="form-control form-control-lg @error('address') is-invalid @enderror"
                                          required
                                          style="border-radius:10px;border:2px solid #E0E0E0;padding:12px 16px;resize:vertical;">{{ old('address', $expert->address) }}</textarea>
                                @error('address')
                                    <div class="text-danger small mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </fieldset>

                    {{-- گواهی --}}
                    <fieldset class="mb-5">
                        <div class="d-flex align-items-center mb-3">
                            <div class="me-3 d-flex align-items-center justify-content-center"
                                 style="width:48px;height:48px;background:linear-gradient(135deg,#FF8A65,#FF5722);border-radius:12px;">
                                <i class="fa fa-certificate text-white" style="font-size:20px;"></i>
                            </div>
                            <h5 class="mb-0 fw-bold" style="color:#BF360C;">گواهی صلاحیت</h5>
                        </div>
                        <div style="height:3px;background:linear-gradient(90deg,#FF8A65,#FF5722);border-radius:3px;margin-bottom:24px;"></div>
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold mb-2" style="color:#37474F;">دارای گواهی صلاحیت حرفه‌ای؟</label>
                                <div class="d-flex gap-4 mt-1 p-3" style="background:#FFF3E0;border-radius:10px;">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="is_certificated"
                                               id="cert_yes_edit" value="1"
                                               {{ old('is_certificated', $expert->is_certificated ? '1' : '0') == '1' ? 'checked' : '' }}
                                               style="width:20px;height:20px;cursor:pointer;">
                                        <label class="form-check-label fw-semibold me-2" for="cert_yes_edit"
                                               style="color:#2E7D32;cursor:pointer;font-size:15px;">
                                            <i class="fa fa-check-circle ms-1"></i> بلی
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="is_certificated"
                                               id="cert_no_edit" value="0"
                                               {{ old('is_certificated', $expert->is_certificated ? '1' : '0') == '0' ? 'checked' : '' }}
                                               style="width:20px;height:20px;cursor:pointer;">
                                        <label class="form-check-label fw-semibold me-2" for="cert_no_edit"
                                               style="color:#C62828;cursor:pointer;font-size:15px;">
                                            <i class="fa fa-times-circle ms-1"></i> خیر
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>

                    <div class="mt-5 d-flex gap-3 justify-content-end flex-wrap">
                        <a href="{{ route('expert-catalog.index') }}"
                           class="btn btn-lg"
                           style="border-radius:12px;background:#F5F5F5;color:#546E7A;font-weight:600;padding:12px 32px;">
                            <i class="fa fa-arrow-right ms-1"></i> بازگشت
                        </a>
                        <button type="submit" class="btn btn-lg text-white"
                                style="background:linear-gradient(135deg,#7986CB 0%,#5C6BC0 100%);border-radius:12px;font-weight:700;padding:12px 40px;box-shadow:0 4px 15px rgba(92,107,192,0.3);">
                            <i class="fa fa-save ms-1"></i> ذخیره تغییرات
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
    $(document).ready(function () {
        initProvinceCityPicker('province_select', 'city_select', '{{ old('city', $expert->city) }}');
    });
</script>
@endsection
