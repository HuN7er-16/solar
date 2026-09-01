@extends('behin-layouts.app')

@section('content')
<div class="container-fluid" style="direction:rtl;text-align:right;">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert"
             style="border-radius:12px;border:none;background:linear-gradient(135deg,#C8E6C9,#A5D6A7);color:#1B5E20;">
            <i class="fa fa-check-circle ms-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" style="float:left;"></button>
        </div>
    @endif

    <div class="mb-4 p-4 text-white"
         style="background:linear-gradient(135deg,#5C6BC0 0%,#3949AB 100%);border-radius:12px;box-shadow:0 4px 20px rgba(57,73,171,0.25);">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h3 class="mb-1 fw-bold"><i class="fa fa-clipboard-check ms-2"></i>فرم‌های بازدید اولیه</h3>
                <p class="mb-0 opacity-90">گزارش‌های بازدید اولیه از محل احداث نیروگاه خورشیدی</p>
            </div>
            <span class="badge" style="background:rgba(255,255,255,0.2);color:#fff;font-size:15px;padding:10px 20px;border-radius:10px;">
                {{ $visits->total() }} گزارش
            </span>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-light"
               style="border-radius:10px;color:#3949AB;font-weight:600;">
                <i class="fa fa-home ms-1"></i> داشبرد
            </a>
        </div>
    </div>

    <div class="card" style="border-radius:12px;border:none;box-shadow:0 4px 20px rgba(0,0,0,0.08);">
        <div class="card-body p-0">
            @if($visits->isEmpty())
                <div class="text-center py-5">
                    <i class="fa fa-folder-open" style="font-size:48px;color:#C5CAE9;"></i>
                    <p class="mt-3 text-muted">هیچ فرم بازدیدی ثبت نشده است.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr style="background:#E8EAF6;">
                                <th style="padding:14px 16px;font-weight:700;color:#283593;border:none;">#</th>
                                <th style="padding:14px 16px;font-weight:700;color:#283593;border:none;">متقاضی</th>
                                <th style="padding:14px 16px;font-weight:700;color:#283593;border:none;">کد پیگیری</th>
                                <th style="padding:14px 16px;font-weight:700;color:#283593;border:none;">کارشناس</th>
                                <th style="padding:14px 16px;font-weight:700;color:#283593;border:none;">تاریخ بازدید</th>
                                <th style="padding:14px 16px;font-weight:700;color:#283593;border:none;">نتیجه ارزیابی</th>
                                <th style="padding:14px 16px;font-weight:700;color:#283593;border:none;">ظرفیت پیشنهادی</th>
                                <th style="padding:14px 16px;font-weight:700;color:#283593;border:none;">عملیات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($visits as $visit)
                                <tr style="border-bottom:1px solid #F5F5F5;">
                                    <td style="padding:14px 16px;vertical-align:middle;">{{ $loop->iteration }}</td>
                                    <td style="padding:14px 16px;vertical-align:middle;">
                                        <div class="fw-semibold" style="color:#263238;">
                                            @if($visit->request?->applicant_type?->value === 'company')
                                                {{ $visit->request->company_name }}
                                            @else
                                                {{ $visit->request?->first_name }} {{ $visit->request?->last_name }}
                                            @endif
                                        </div>
                                    </td>
                                    <td style="padding:14px 16px;vertical-align:middle;">
                                        <code style="background:#E8EAF6;color:#283593;padding:3px 8px;border-radius:6px;font-size:12px;">
                                            {{ $visit->request?->unique_code }}
                                        </code>
                                    </td>
                                    <td style="padding:14px 16px;vertical-align:middle;">{{ $visit->expert?->name }}</td>
                                    <td style="padding:14px 16px;vertical-align:middle;font-family:'Vazir',monospace;">
                                        {{ $visit->visit_date_jalali }}
                                    </td>
                                    <td style="padding:14px 16px;vertical-align:middle;">
                                        {!! $visit->assessment_result_label !!}
                                    </td>
                                    <td style="padding:14px 16px;vertical-align:middle;font-weight:700;color:#3949AB;">
                                        {{ $visit->expert_proposed_capacity_kw }} kW
                                    </td>
                                    <td style="padding:14px 16px;vertical-align:middle;">
                                        <a href="{{ route('expert-initial-visit.show', $visit) }}"
                                           class="btn btn-sm text-white"
                                           style="background:linear-gradient(135deg,#7986CB,#5C6BC0);border-radius:8px;padding:7px 16px;font-weight:600;">
                                            <i class="fa fa-eye ms-1"></i> مشاهده
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-4">{{ $visits->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
