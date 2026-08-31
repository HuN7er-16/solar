@extends('behin-layouts.app')

@section('content')
    <div class="container-fluid" style="direction: rtl; text-align: right;">

        <div class="mb-4 p-4 text-white"
             style="background:linear-gradient(135deg,#7986CB 0%,#5C6BC0 100%);border-radius:12px;box-shadow:0 4px 20px rgba(92,107,192,0.25);">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h3 class="mb-1 fw-bold"><i class="fa fa-user-tie ms-2"></i>جزئیات کارشناس</h3>
                    <p class="mb-0 opacity-90">{{ $expert->first_name }} {{ $expert->last_name }}</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('expert-catalog.edit', $expert) }}"
                       class="btn btn-warning"
                       style="border-radius:10px;font-weight:600;">
                        <i class="fa fa-edit ms-1"></i> ویرایش
                    </a>
                    <a href="{{ route('expert-catalog.index') }}"
                       class="btn btn-light"
                       style="border-radius:10px;color:#3949AB;font-weight:600;">
                        <i class="fa fa-arrow-right ms-1"></i> بازگشت
                    </a>
                </div>
            </div>
        </div>

        <div class="card" style="border-radius:12px;border:none;box-shadow:0 4px 20px rgba(0,0,0,0.08);">
            <div class="card-body p-4">

                <fieldset class="mb-4">
                    <legend class="fw-bold" style="font-size:1rem;border-bottom:2px solid #5C6BC0;padding-bottom:5px;color:#283593;">
                        اطلاعات حساب کاربری
                    </legend>
                    <div class="row g-3 mt-1">
                        <div class="col-md-4"><strong>نام نمایشی:</strong> {{ $expert->user?->name ?? '-' }}</div>
                        <div class="col-md-4"><strong>ایمیل:</strong> {{ $expert->user?->email ?? '-' }}</div>
                        <div class="col-md-4"><strong>شماره تلفن کاربری:</strong> {{ $expert->user?->phone ?? '-' }}</div>
                    </div>
                </fieldset>

                <fieldset class="mb-4">
                    <legend class="fw-bold" style="font-size:1rem;border-bottom:2px solid #5C6BC0;padding-bottom:5px;color:#283593;">
                        اطلاعات هویتی
                    </legend>
                    <div class="row g-3 mt-1">
                        <div class="col-md-3"><strong>کد کارشناس:</strong> {{ $expert->expert_code }}</div>
                        <div class="col-md-3"><strong>نام:</strong> {{ $expert->first_name }}</div>
                        <div class="col-md-3"><strong>نام خانوادگی:</strong> {{ $expert->last_name }}</div>
                        <div class="col-md-3"><strong>کد ملی:</strong> {{ $expert->national_id }}</div>
                    </div>
                </fieldset>

                <fieldset class="mb-4">
                    <legend class="fw-bold" style="font-size:1rem;border-bottom:2px solid #5C6BC0;padding-bottom:5px;color:#283593;">
                        اطلاعات تماس
                    </legend>
                    <div class="row g-3 mt-1">
                        <div class="col-md-6"><strong>شماره همراه:</strong> {{ $expert->mobile }}</div>
                        <div class="col-md-6"><strong>تلفن ثابت:</strong> {{ $expert->phone ?? '-' }}</div>
                    </div>
                </fieldset>

                <fieldset class="mb-4">
                    <legend class="fw-bold" style="font-size:1rem;border-bottom:2px solid #5C6BC0;padding-bottom:5px;color:#283593;">
                        محل فعالیت
                    </legend>
                    <div class="row g-3 mt-1">
                        <div class="col-md-3"><strong>استان:</strong> {{ $expert->province }}</div>
                        <div class="col-md-3"><strong>شهر:</strong> {{ $expert->city }}</div>
                        <div class="col-12"><strong>آدرس:</strong> {{ $expert->address }}</div>
                    </div>
                </fieldset>

                <fieldset class="mb-4">
                    <legend class="fw-bold" style="font-size:1rem;border-bottom:2px solid #5C6BC0;padding-bottom:5px;color:#283593;">
                        گواهی صلاحیت
                    </legend>
                    <div class="row g-3 mt-1">
                        <div class="col-md-6">
                            <strong>دارای گواهی صلاحیت حرفه‌ای:</strong>
                            @if($expert->is_certificated)
                                <span class="badge" style="background:#C8E6C9;color:#1B5E20;padding:5px 12px;border-radius:8px;">بلی</span>
                            @else
                                <span class="badge" style="background:#E0E0E0;color:#424242;padding:5px 12px;border-radius:8px;">خیر</span>
                            @endif
                        </div>
                    </div>
                </fieldset>

                <fieldset class="mb-4">
                    <legend class="fw-bold" style="font-size:1rem;border-bottom:2px solid #5C6BC0;padding-bottom:5px;color:#283593;">
                        اطلاعات سیستم
                    </legend>
                    <div class="row g-3 mt-1">
                        <div class="col-md-6"><strong>تاریخ ثبت:</strong> {{ jdate($expert->created_at)->format('Y/m/d H:i') }}</div>
                        <div class="col-md-6"><strong>آخرین ویرایش:</strong> {{ jdate($expert->updated_at)->format('Y/m/d H:i') }}</div>
                    </div>
                </fieldset>

            </div>
        </div>
    </div>
@endsection
