@extends('behin-layouts.app')

@section('content')
<div class="container-fluid" style="direction:rtl;text-align:right;">

    {{-- Header --}}
    <div class="mb-4 p-4 text-white"
         style="background:linear-gradient(135deg,#5C6BC0 0%,#3949AB 100%);border-radius:12px;box-shadow:0 4px 20px rgba(57,73,171,0.25);">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h3 class="mb-1 fw-bold"><i class="fa fa-clipboard-check ms-2"></i>گزارش بازدید اولیه</h3>
                <p class="mb-0 opacity-90">
                    کد تقاضا: <span style="font-family:monospace;">{{ $expertInitialVisit->request?->unique_code }}</span>
                    &nbsp;|&nbsp; تاریخ بازدید: {{ $expertInitialVisit->visit_date_jalali }}
                </p>
            </div>
            <div class="d-flex gap-2">
                {!! $expertInitialVisit->assessment_result_label !!}
                <a href="{{ route('expert-initial-visit.index') }}" class="btn btn-light"
                   style="border-radius:10px;color:#3949AB;font-weight:600;">
                    <i class="fa fa-arrow-right ms-1"></i> بازگشت
                </a>
            </div>
        </div>
    </div>

    @php
        $req = $expertInitialVisit->request;
    @endphp

    {{-- بخش ۱: اطلاعات درخواست --}}
    <div class="card mb-4" style="border-radius:12px;border:none;box-shadow:0 4px 20px rgba(0,0,0,0.08);">
        <div class="card-header" style="background:linear-gradient(135deg,#FFF3E0,#FFE0B2);border-radius:12px 12px 0 0;border:none;">
            <h5 class="mb-0 fw-bold" style="color:#E65100;"><i class="fa fa-file-text ms-2"></i>اطلاعات درخواست و کارشناس</h5>
        </div>
        <div class="card-body p-4">
            <div class="row g-3">
                <div class="col-md-3"><label class="text-muted small d-block">کد درخواست</label><strong><code>{{ $req?->unique_code }}</code></strong></div>
                <div class="col-md-3"><label class="text-muted small d-block">متقاضی</label><strong>{{ $req?->applicant_type?->value === 'company' ? $req->company_name : ($req?->first_name . ' ' . $req?->last_name) }}</strong></div>
                <div class="col-md-3"><label class="text-muted small d-block">شماره تماس</label><strong dir="ltr">{{ $req?->mobile }}</strong></div>
                <div class="col-md-3"><label class="text-muted small d-block">استان / شهر</label><strong>{{ $req?->province }} / {{ $req?->city }}</strong></div>
                <div class="col-md-6"><label class="text-muted small d-block">آدرس</label><strong>{{ $req?->address }}</strong></div>
                <div class="col-md-3"><label class="text-muted small d-block">کارشناس</label><strong>{{ $expertInitialVisit->expert?->name }}</strong></div>
                <div class="col-md-3"><label class="text-muted small d-block">تاریخ بازدید</label><strong>{{ $expertInitialVisit->visit_date_jalali }}</strong></div>
                <div class="col-md-3"><label class="text-muted small d-block">تاریخ ارسال گزارش</label><strong>{{ $expertInitialVisit->submitted_at ? \Morilog\Jalali\Jalalian::fromDateTime($expertInitialVisit->submitted_at)->format('Y/m/d H:i') : '-' }}</strong></div>
            </div>
        </div>
    </div>

    {{-- بخش ۲: احراز محل --}}
    @include('expert-initial-visit::visits.show-partials.section2')

    {{-- بخش ۳: وضعیت کلی محل --}}
    @include('expert-initial-visit::visits.show-partials.section3')

    {{-- بخش ۴: سطح نصب --}}
    @include('expert-initial-visit::visits.show-partials.section4')

    {{-- بخش ۵: سازه و ایمنی --}}
    @include('expert-initial-visit::visits.show-partials.section5')

    {{-- بخش ۶: برق --}}
    @include('expert-initial-visit::visits.show-partials.section6')

    {{-- بخش ۷: بار اضطراری --}}
    @include('expert-initial-visit::visits.show-partials.section7')

    {{-- بخش ۸: محل تجهیزات --}}
    @include('expert-initial-visit::visits.show-partials.section8')

    {{-- بخش ۹: ظرفیت --}}
    @include('expert-initial-visit::visits.show-partials.section9')

    {{-- بخش ۱۰: اصلاحات --}}
    @include('expert-initial-visit::visits.show-partials.section10')

    {{-- بخش ۱۱: نتیجه --}}
    @include('expert-initial-visit::visits.show-partials.section11')

    {{-- بخش ۱۲: جمع‌بندی --}}
    <div class="card mb-4" style="border-radius:12px;border:none;box-shadow:0 4px 20px rgba(0,0,0,0.08);">
        <div class="card-header" style="background:linear-gradient(135deg,#E8EAF6,#C5CAE9);border-radius:12px 12px 0 0;border:none;">
            <h5 class="mb-0 fw-bold" style="color:#283593;"><i class="fa fa-pencil-square ms-2"></i>بخش ۱۲ — جمع‌بندی و نظر کارشناسی</h5>
        </div>
        <div class="card-body p-4">
            <p style="line-height:2;white-space:pre-wrap;">{{ $expertInitialVisit->expert_summary ?? '-' }}</p>
        </div>
    </div>

    {{-- بخش ۱۳: تصاویر --}}
    @if($expertInitialVisit->photos->isNotEmpty())
    <div class="card mb-4" style="border-radius:12px;border:none;box-shadow:0 4px 20px rgba(0,0,0,0.08);">
        <div class="card-header" style="background:linear-gradient(135deg,#E8EAF6,#C5CAE9);border-radius:12px 12px 0 0;border:none;">
            <h5 class="mb-0 fw-bold" style="color:#283593;"><i class="fa fa-camera ms-2"></i>بخش ۱۳ — تصاویر بازدید</h5>
        </div>
        <div class="card-body p-4">
            <div class="row g-3">
                @foreach($expertInitialVisit->photos as $photo)
                <div class="col-md-3">
                    <div class="card" style="border-radius:10px;border:1px solid #E0E0E0;">
                        <img src="{{ Storage::url($photo->path) }}" class="card-img-top"
                             style="border-radius:10px 10px 0 0;height:180px;object-fit:cover;"
                             alt="{{ $photo->photo_type_label }}">
                        <div class="card-body p-2 text-center">
                            <span class="badge" style="background:#E8EAF6;color:#283593;font-size:12px;">{{ $photo->photo_type_label }}</span>
                            @if($photo->caption)
                                <p class="mb-0 mt-1 text-muted small">{{ $photo->caption }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

</div>
@endsection
