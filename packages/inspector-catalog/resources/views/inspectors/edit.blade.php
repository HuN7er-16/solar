@extends('behin-layouts.app')

@section('content')
    <div class="container-fluid">
        @if (session()->has('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session()->has('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">ویرایش بازرس: {{ $inspector->full_name }}</h5>
                <a href="{{ route('inspector-catalog.index') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-right ms-1"></i> بازگشت
                </a>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('inspector-catalog.update', $inspector) }}">
                    @csrf
                    @method('PUT')

                    {{-- انتخاب کاربر --}}
                    <fieldset class="mb-4">
                        <legend class="font-weight-bold text-primary" style="font-size:1rem; border-bottom: 2px solid #1976d2; padding-bottom:5px;">کاربر مرتبط</legend>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">کاربر <span class="text-danger">*</span></label>
                                <select name="user_id" class="form-control select2 @error('user_id') is-invalid @enderror" required>
                                    <option value="">-- انتخاب کنید --</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('user_id', $inspector->user_id) == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} — {{ $user->email }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_id') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </fieldset>

                    {{-- اطلاعات هویتی بازرس --}}
                    <fieldset class="mb-4">
                        <legend class="font-weight-bold text-primary" style="font-size:1rem; border-bottom: 2px solid #1976d2; padding-bottom:5px;">اطلاعات هویتی بازرس</legend>
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">کد بازرس <span class="text-danger">*</span></label>
                                <input type="text" name="inspector_code" class="form-control @error('inspector_code') is-invalid @enderror" value="{{ old('inspector_code', $inspector->inspector_code) }}" required>
                                @error('inspector_code') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">نام <span class="text-danger">*</span></label>
                                <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name', $inspector->first_name) }}" required>
                                @error('first_name') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">نام خانوادگی <span class="text-danger">*</span></label>
                                <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name', $inspector->last_name) }}" required>
                                @error('last_name') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">کد ملی <span class="text-danger">*</span></label>
                                <input type="text" name="national_id" class="form-control @error('national_id') is-invalid @enderror" value="{{ old('national_id', $inspector->national_id) }}" required maxlength="10">
                                @error('national_id') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </fieldset>

                    {{-- اطلاعات تماس --}}
                    <fieldset class="mb-4">
                        <legend class="font-weight-bold text-primary" style="font-size:1rem; border-bottom: 2px solid #1976d2; padding-bottom:5px;">اطلاعات تماس</legend>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">شماره همراه <span class="text-danger">*</span></label>
                                <input type="text" name="mobile" class="form-control @error('mobile') is-invalid @enderror" value="{{ old('mobile', $inspector->mobile) }}" required maxlength="11">
                                @error('mobile') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">تلفن ثابت</label>
                                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $inspector->phone) }}" maxlength="11">
                                @error('phone') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </fieldset>

                    {{-- محل فعالیت --}}
                    <fieldset class="mb-4">
                        <legend class="font-weight-bold text-primary" style="font-size:1rem; border-bottom: 2px solid #1976d2; padding-bottom:5px;">محل فعالیت</legend>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">استان محل فعالیت <span class="text-danger">*</span></label>
                                <select name="province" class="form-control select2 @error('province') is-invalid @enderror" required id="province_select">
                                    <option value="">-- انتخاب کنید --</option>
                                    @foreach($provinces as $province)
                                        <option value="{{ $province }}" {{ old('province', $inspector->province) == $province ? 'selected' : '' }}>{{ $province }}</option>
                                    @endforeach
                                </select>
                                @error('province') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">شهر محل فعالیت <span class="text-danger">*</span></label>
                                <select name="city" id="city_select" class="form-control select2 @error('city') is-invalid @enderror" required>
                                    <option value="{{ old('city', $inspector->city) }}">{{ old('city', $inspector->city) }}</option>
                                </select>
                                @error('city') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">آدرس <span class="text-danger">*</span></label>
                                <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="2" required>{{ old('address', $inspector->address) }}</textarea>
                                @error('address') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </fieldset>

                    {{-- گواهی صلاحیت --}}
                    <fieldset class="mb-4">
                        <legend class="font-weight-bold text-primary" style="font-size:1rem; border-bottom: 2px solid #1976d2; padding-bottom:5px;">گواهی صلاحیت حرفه‌ای</legend>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">دارای گواهی صلاحیت حرفه‌ای می‌باشد؟</label>
                                <div class="d-flex gap-4 mt-1">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="is_certificated" id="cert_yes" value="1"
                                            {{ old('is_certificated', $inspector->is_certificated ? '1' : '0') == '1' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="cert_yes">بلی</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="is_certificated" id="cert_no" value="0"
                                            {{ old('is_certificated', $inspector->is_certificated ? '1' : '0') == '0' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="cert_no">خیر</label>
                                    </div>
                                </div>
                                @error('is_certificated') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </fieldset>

                    <div class="mt-4 text-end">
                        <a href="{{ route('inspector-catalog.index') }}" class="btn btn-secondary ms-2">بازگشت</a>
                        <button type="submit" class="btn btn-primary btn-lg">
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
    $(document).ready(function() {
        initProvinceCityPicker('province_select', 'city_select', '{{ old('city', $inspector->city) }}');
    });
</script>
@endsection
