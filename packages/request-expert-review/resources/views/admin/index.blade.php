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
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert"
             style="border-radius:12px;border:none;background:linear-gradient(135deg,#FFCDD2,#EF9A9A);color:#B71C1C;">
            <i class="fa fa-exclamation-circle ms-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" style="float:left;"></button>
        </div>
    @endif

    {{-- Header --}}
    <div class="mb-4 p-4 text-white"
         style="background:linear-gradient(135deg,#5C6BC0 0%,#3949AB 100%);border-radius:12px;box-shadow:0 4px 20px rgba(57,73,171,0.25);">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h3 class="mb-1 fw-bold"><i class="fa fa-user-tag ms-2"></i>تخصیص کارشناس به تقاضاها</h3>
                <p class="mb-0 opacity-90">تقاضاهای در وضعیت «ثبت اولیه» آماده تخصیص کارشناس هستند</p>
            </div>
            @php
                $initialCount = $requests->where('status', \SolarPlantRequests\Enums\SolarPlantRequestStatus::INITIAL)->count();
            @endphp
            <div class="d-flex gap-3">
                <span class="badge"
                      style="background:rgba(255,255,255,0.2);color:#fff;font-size:14px;padding:10px 18px;border-radius:10px;">
                    <i class="fa fa-clock ms-1"></i> {{ $initialCount }} در انتظار کارشناس
                </span>
            </div>
        </div>
    </div>

    {{-- جدول تقاضاها --}}
    <div class="card" style="border-radius:12px;border:none;box-shadow:0 4px 20px rgba(0,0,0,0.08);">
        <div class="card-body p-0">
            @if ($requests->isEmpty())
                <div class="text-center py-5">
                    <i class="fa fa-inbox" style="font-size:48px;color:#C5CAE9;"></i>
                    <p class="mt-3 text-muted">هیچ تقاضایی وجود ندارد.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="adminRequestsTable">
                        <thead>
                            <tr style="background:#E8EAF6;">
                                <th style="padding:14px 16px;font-weight:700;color:#283593;border:none;">#</th>
                                <th style="padding:14px 16px;font-weight:700;color:#283593;border:none;">متقاضی</th>
                                <th style="padding:14px 16px;font-weight:700;color:#283593;border:none;">کد پیگیری</th>
                                <th style="padding:14px 16px;font-weight:700;color:#283593;border:none;">استان / شهر</th>
                                <th style="padding:14px 16px;font-weight:700;color:#283593;border:none;">وضعیت</th>
                                <th style="padding:14px 16px;font-weight:700;color:#283593;border:none;">کارشناس فعلی</th>
                                <th style="padding:14px 16px;font-weight:700;color:#283593;border:none;">تاریخ ثبت</th>
                                <th style="padding:14px 16px;font-weight:700;color:#283593;border:none;">تخصیص کارشناس</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($requests as $req)
                                @php
                                    $isInitial = $req->status === \SolarPlantRequests\Enums\SolarPlantRequestStatus::INITIAL;
                                    $statusColors = [
                                        'initial_registration'   => ['bg'=>'#F3E5F5','color'=>'#6A1B9A','label'=>'ثبت اولیه'],
                                        'under_review'           => ['bg'=>'#E3F2FD','color'=>'#1565C0','label'=>'در حال بررسی'],
                                        'contractor_assigned'    => ['bg'=>'#EDE7F6','color'=>'#4527A0','label'=>'تخصیص پیمانکار'],
                                        'equipment_installation' => ['bg'=>'#FFF3E0','color'=>'#E65100','label'=>'نصب تجهیزات'],
                                        'inspection'             => ['bg'=>'#FFFDE7','color'=>'#F57F17','label'=>'بازرسی'],
                                        'certificate_issued'     => ['bg'=>'#E8F5E9','color'=>'#1B5E20','label'=>'صدور گواهی'],
                                    ];
                                    $sc = $statusColors[$req->status->value] ?? ['bg'=>'#F5F5F5','color'=>'#616161','label'=>$req->status_label];
                                @endphp
                                <tr style="border-bottom:1px solid #F5F5F5;{{ $isInitial ? 'background:#FAFAFA;' : '' }}">
                                    <td style="padding:14px 16px;vertical-align:middle;">{{ $loop->iteration }}</td>
                                    <td style="padding:14px 16px;vertical-align:middle;">
                                        <div class="fw-semibold" style="color:#263238;">
                                            @if ($req->applicant_type?->value === 'company')
                                                {{ $req->company_name }}
                                            @else
                                                {{ $req->first_name }} {{ $req->last_name }}
                                            @endif
                                        </div>
                                        <small class="text-muted" dir="ltr">{{ $req->mobile }}</small>
                                    </td>
                                    <td style="padding:14px 16px;vertical-align:middle;">
                                        <code style="background:#E8EAF6;color:#283593;padding:3px 8px;border-radius:6px;font-size:12px;">
                                            {{ $req->unique_code }}
                                        </code>
                                    </td>
                                    <td style="padding:14px 16px;vertical-align:middle;">
                                        <div style="color:#37474F;">{{ $req->province }}</div>
                                        <small class="text-muted">{{ $req->city }}</small>
                                    </td>
                                    <td style="padding:14px 16px;vertical-align:middle;">
                                        <span class="badge"
                                              style="background:{{ $sc['bg'] }};color:{{ $sc['color'] }};padding:6px 14px;border-radius:20px;font-weight:600;">
                                            {{ $sc['label'] }}
                                        </span>
                                    </td>
                                    <td style="padding:14px 16px;vertical-align:middle;">
                                        @if ($req->expert_name)
                                            <div class="d-flex align-items-center gap-2">
                                                <div style="width:30px;height:30px;background:linear-gradient(135deg,#7986CB,#5C6BC0);border-radius:50%;display:flex;align-items:center;justify-content:center;">
                                                    <i class="fa fa-user-tie text-white" style="font-size:12px;"></i>
                                                </div>
                                                <span class="fw-semibold" style="color:#283593;">{{ $req->expert_name }}</span>
                                            </div>
                                        @else
                                            <span class="text-muted small">اختصاص نیافته</span>
                                        @endif
                                    </td>
                                    <td style="padding:14px 16px;vertical-align:middle;font-family:'Vazir',monospace;color:#546E7A;">
                                        {{ \Morilog\Jalali\Jalalian::fromDateTime($req->created_at)->format('Y/m/d') }}
                                    </td>
                                    <td style="padding:14px 16px;vertical-align:middle;">
                                        @if ($isInitial)
                                            <form method="POST"
                                                  action="{{ route('request-expert-review.admin.assign-expert', $req) }}"
                                                  class="d-flex gap-2 align-items-center flex-wrap">
                                                @csrf
                                                <select name="expert_user_id"
                                                        class="form-control form-control-sm"
                                                        style="border-radius:8px;border:2px solid #C5CAE9;min-width:160px;padding:6px 10px;"
                                                        required>
                                                    <option value="">-- کارشناس --</option>
                                                    @foreach ($experts as $expert)
                                                        <option value="{{ $expert->id }}">{{ $expert->name }}</option>
                                                    @endforeach
                                                </select>
                                                <button type="submit"
                                                        class="btn btn-sm text-white"
                                                        style="background:linear-gradient(135deg,#7986CB,#5C6BC0);border-radius:8px;font-weight:600;padding:7px 16px;white-space:nowrap;">
                                                    <i class="fa fa-user-check ms-1"></i> تخصیص
                                                </button>
                                            </form>
                                        @else
                                            @php
                                                $visit = null;
                                                if (class_exists(\ExpertInitialVisit\Models\ExpertInitialVisit::class)) {
                                                    try {
                                                        $visit = \ExpertInitialVisit\Models\ExpertInitialVisit::query()
                                                            ->where('solar_plant_request_id', $req->id)
                                                            ->first();
                                                    } catch (\Throwable $e) {}
                                                }
                                            @endphp
                                            <div class="d-flex flex-column gap-2">
                                                @if ($visit)
                                                    <a href="{{ route('expert-initial-visit.show', $visit) }}"
                                                       class="btn btn-sm text-white"
                                                       style="background:linear-gradient(135deg,#4CAF50,#2E7D32);border-radius:8px;font-weight:600;padding:7px 16px;white-space:nowrap;">
                                                        <i class="fa fa-eye ms-1"></i> مشاهده فرم بازدید
                                                    </a>
                                                @else
                                                    <span class="text-muted small">
                                                        <i class="fa fa-clock-o ms-1" style="color:#FF9800;"></i>
                                                        منتظر ثبت فرم بازدید
                                                    </span>
                                                @endif
                                                <span class="text-muted" style="font-size:11px;">
                                                    <i class="fa fa-info-circle ms-1" style="color:#9E9E9E;"></i>
                                                    @if ($req->expert_name)
                                                        کارشناس: {{ $req->expert_name }}
                                                    @else
                                                        کارشناس اختصاص نیافته
                                                    @endif
                                                </span>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

</div>
@endsection

@section('script')
<style>
    #adminRequestsTable tbody tr:hover { background-color: #E8EAF6 !important; }
</style>
<script>
    $(document).ready(function () {
        $('#adminRequestsTable').DataTable({
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
