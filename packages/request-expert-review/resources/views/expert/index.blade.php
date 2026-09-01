@extends('behin-layouts.app')

@section('content')
<div class="container-fluid" style="direction: rtl; text-align: right;">

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert"
             style="border-radius:12px;border:none;background:linear-gradient(135deg,#C8E6C9,#A5D6A7);color:#1B5E20;">
            <i class="fa fa-check-circle ms-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" style="float:left;"></button>
        </div>
    @endif

    {{-- Header --}}
    <div class="mb-4 p-4 text-white"
         style="background:linear-gradient(135deg,#7986CB 0%,#5C6BC0 100%);border-radius:12px;box-shadow:0 4px 20px rgba(92,107,192,0.25);">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h3 class="mb-1 fw-bold"><i class="fa fa-clipboard-list ms-2"></i>تقاضاهای من</h3>
                <p class="mb-0 opacity-90">تقاضاهایی که برای بررسی به شما اختصاص داده شده‌اند</p>
            </div>
            <div class="d-flex align-items-center gap-3 flex-wrap">
                <span class="badge"
                      style="background:rgba(255,255,255,0.2);color:#fff;font-size:16px;padding:10px 20px;border-radius:10px;">
                    {{ $requests->count() }} تقاضا
                </span>
                <a href="{{ route('admin.dashboard') }}"
                   class="btn btn-light"
                   style="border-radius:10px;color:#3949AB;font-weight:600;">
                    <i class="fa fa-home ms-1"></i> بازگشت به داشبرد
                </a>
            </div>
        </div>
    </div>

    @if ($requests->isEmpty())
        <div class="card text-center py-5" style="border-radius:12px;border:none;box-shadow:0 4px 20px rgba(0,0,0,0.08);">
            <div class="card-body">
                <div style="width:90px;height:90px;margin:0 auto 20px;
                            background:linear-gradient(135deg,#E8EAF6,#C5CAE9);
                            border-radius:50%;display:flex;align-items:center;justify-content:center;">
                    <i class="fa fa-folder-open" style="font-size:38px;color:#5C6BC0;"></i>
                </div>
                <h5 class="mb-2 fw-bold" style="color:#283593;">هیچ تقاضایی در انتظار بررسی وجود ندارد</h5>
                <p class="text-muted">راهبر سایت هنوز تقاضایی به شما اختصاص نداده است.</p>
            </div>
        </div>
    @else
        <div class="card" style="border-radius:12px;border:none;box-shadow:0 4px 20px rgba(0,0,0,0.08);">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="expertRequestsTable">
                        <thead>
                            <tr style="background:#E8EAF6;">
                                <th style="padding:14px 16px;font-weight:700;color:#283593;border:none;">#</th>
                                <th style="padding:14px 16px;font-weight:700;color:#283593;border:none;">متقاضی</th>
                                <th style="padding:14px 16px;font-weight:700;color:#283593;border:none;">کد پیگیری</th>
                                <th style="padding:14px 16px;font-weight:700;color:#283593;border:none;">استان / شهر</th>
                                <th style="padding:14px 16px;font-weight:700;color:#283593;border:none;">ظرفیت (kW)</th>
                                <th style="padding:14px 16px;font-weight:700;color:#283593;border:none;">وضعیت</th>
                                <th style="padding:14px 16px;font-weight:700;color:#283593;border:none;">تاریخ ثبت</th>
                                <th style="padding:14px 16px;font-weight:700;color:#283593;border:none;">عملیات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($requests as $req)
                                <tr style="border-bottom:1px solid #F5F5F5;">
                                    <td style="padding:14px 16px;vertical-align:middle;">{{ $loop->iteration }}</td>
                                    <td style="padding:14px 16px;vertical-align:middle;">
                                        <div class="fw-semibold" style="color:#263238;">
                                            @if ($req->applicant_type?->value === 'company')
                                                {{ $req->company_name }}
                                            @else
                                                {{ $req->first_name }} {{ $req->last_name }}
                                            @endif
                                        </div>
                                        <small class="text-muted" style="font-size:12px;" dir="ltr">{{ $req->mobile }}</small>
                                    </td>
                                    <td style="padding:14px 16px;vertical-align:middle;">
                                        <code style="background:#E8EAF6;color:#283593;padding:3px 8px;border-radius:6px;font-size:12px;">
                                            {{ $req->unique_code }}
                                        </code>
                                    </td>
                                    <td style="padding:14px 16px;vertical-align:middle;">
                                        <div style="color:#37474F;">
                                            <i class="fa fa-map-marker-alt ms-1" style="color:#FF7043;"></i>
                                            {{ $req->province }}
                                        </div>
                                        <small class="text-muted">{{ $req->city }}</small>
                                    </td>
                                    <td style="padding:14px 16px;vertical-align:middle;font-weight:700;color:#5C6BC0;">
                                        {{ $req->capacity_kw ?? '-' }}
                                    </td>
                                    <td style="padding:14px 16px;vertical-align:middle;">
                                        <span class="badge"
                                              style="background:#E3F2FD;color:#1565C0;padding:6px 14px;border-radius:20px;font-weight:600;">
                                            <i class="fa fa-search ms-1" style="font-size:10px;"></i>
                                            {{ $req->status_label }}
                                        </span>
                                    </td>
                                    <td style="padding:14px 16px;vertical-align:middle;font-family:'Vazir',monospace;color:#546E7A;">
                                        {{ \Morilog\Jalali\Jalalian::fromDateTime($req->created_at)->format('Y/m/d') }}
                                    </td>
                                    <td style="padding:14px 16px;vertical-align:middle;">
                                        @php
                                            $existingVisit = null;
                                            if (class_exists(\ExpertInitialVisit\Models\ExpertInitialVisit::class)) {
                                                try {
                                                    $existingVisit = \ExpertInitialVisit\Models\ExpertInitialVisit::query()
                                                        ->where('solar_plant_request_id', $req->id)->first();
                                                } catch (\Throwable $e) {}
                                            }
                                        @endphp
                                        <div class="d-flex flex-column gap-2">
                                            <a href="{{ route('request-expert-review.expert.show', $req) }}"
                                               class="btn btn-sm text-white"
                                               style="background:linear-gradient(135deg,#7986CB,#5C6BC0);border-radius:8px;font-weight:600;padding:7px 16px;">
                                                <i class="fa fa-eye ms-1"></i> مشاهده جزئیات
                                            </a>
                                            @if(class_exists(\ExpertInitialVisit\Models\ExpertInitialVisit::class))
                                                @if($existingVisit)
                                                    <a href="{{ route('expert-initial-visit.show', $existingVisit) }}"
                                                       class="btn btn-sm text-white"
                                                       style="background:linear-gradient(135deg,#4CAF50,#2E7D32);border-radius:8px;font-weight:600;padding:7px 16px;">
                                                        <i class="fa fa-check-circle ms-1"></i> مشاهده فرم بازدید
                                                    </a>
                                                @else
                                                    <a href="{{ route('expert-initial-visit.create', ['request_id' => $req->id]) }}"
                                                       class="btn btn-sm text-white"
                                                       style="background:linear-gradient(135deg,#FF9800,#E65100);border-radius:8px;font-weight:600;padding:7px 16px;">
                                                        <i class="fa fa-clipboard-check ms-1"></i> ثبت فرم بازدید
                                                    </a>
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

</div>
@endsection

@section('script')
<style>
    #expertRequestsTable tbody tr:hover { background-color: #E8EAF6 !important; }
</style>
<script>
    $(document).ready(function () {
        $('#expertRequestsTable').DataTable({
            responsive: true,
            paging: false,
            searching: true,
            info: true,
            order: [[6, 'desc']],
            language: {
                search: "جستجو:",
                info: "نمایش _START_ تا _END_ از _TOTAL_ تقاضا",
                emptyTable: "تقاضایی وجود ندارد"
            }
        });
    });
</script>
@endsection
