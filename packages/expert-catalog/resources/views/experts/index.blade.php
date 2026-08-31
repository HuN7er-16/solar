@extends('behin-layouts.app')

@section('content')
    <div class="container-fluid" style="direction: rtl; text-align: right;">

        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert"
                 style="border-radius: 12px; border: none; background: linear-gradient(135deg, #C8E6C9 0%, #A5D6A7 100%); color: #1B5E20;">
                <i class="fa fa-check-circle ms-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" style="float: left;"></button>
            </div>
        @endif
        @if (session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert"
                 style="border-radius: 12px; border: none; background: linear-gradient(135deg, #FFCDD2 0%, #EF9A9A 100%); color: #B71C1C;">
                <i class="fa fa-exclamation-circle ms-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" style="float: left;"></button>
            </div>
        @endif

        {{-- Header --}}
        <div class="mb-4 p-4 text-white"
             style="background: linear-gradient(135deg, #7986CB 0%, #5C6BC0 100%); border-radius: 12px; box-shadow: 0 4px 20px rgba(92,107,192,0.25);">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h3 class="mb-1 fw-bold"><i class="fa fa-user-tie ms-2"></i>کاتالوگ کارشناسان</h3>
                    <p class="mb-0 opacity-90">مدیریت کارشناسان بررسی اولیه تقاضاها</p>
                </div>
                <a href="{{ route('expert-catalog.create') }}"
                   class="btn btn-light btn-lg"
                   style="border-radius: 12px; color: #3949AB; font-weight: 600; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                    <i class="fa fa-plus ms-1"></i> افزودن کارشناس جدید
                </a>
            </div>
        </div>

        {{-- Stats --}}
        @php
            $totalExperts  = $experts->total();
            $coveredCities = collect();
            foreach ($experts as $e) { $coveredCities->push($e->city); }
            $coveredCities = $coveredCities->unique()->count();
        @endphp

        <div class="row g-4 mb-4">
            <div class="col-md-3 col-sm-6">
                <div class="p-4 text-white"
                     style="background: linear-gradient(135deg, #FFB74D 0%, #FF9800 100%); border-radius: 12px; box-shadow: 0 4px 15px rgba(255,152,0,0.25);">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="mb-2 opacity-90">کل کارشناسان</h6>
                            <h2 class="mb-0 fw-bold">{{ $totalExperts }}</h2>
                        </div>
                        <div style="background: rgba(255,255,255,0.2); border-radius: 50%; padding: 12px;">
                            <i class="fa fa-users" style="font-size: 24px;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="p-4 text-white"
                     style="background: linear-gradient(135deg, #81C784 0%, #4CAF50 100%); border-radius: 12px; box-shadow: 0 4px 15px rgba(76,175,80,0.25);">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="mb-2 opacity-90">کارشناس فعال</h6>
                            <h2 class="mb-0 fw-bold">{{ $experts->count() }}</h2>
                        </div>
                        <div style="background: rgba(255,255,255,0.2); border-radius: 50%; padding: 12px;">
                            <i class="fa fa-user-check" style="font-size: 24px;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="p-4 text-white"
                     style="background: linear-gradient(135deg, #7986CB 0%, #5C6BC0 100%); border-radius: 12px; box-shadow: 0 4px 15px rgba(92,107,192,0.25);">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="mb-2 opacity-90">شهرهای تحت پوشش</h6>
                            <h2 class="mb-0 fw-bold">{{ $coveredCities }}</h2>
                        </div>
                        <div style="background: rgba(255,255,255,0.2); border-radius: 50%; padding: 12px;">
                            <i class="fa fa-city" style="font-size: 24px;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="p-4 text-white"
                     style="background: linear-gradient(135deg, #FF8A65 0%, #FF5722 100%); border-radius: 12px; box-shadow: 0 4px 15px rgba(255,87,34,0.25);">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="mb-2 opacity-90">دارای گواهی</h6>
                            <h2 class="mb-0 fw-bold">{{ $experts->where('is_certificated', true)->count() }}</h2>
                        </div>
                        <div style="background: rgba(255,255,255,0.2); border-radius: 50%; padding: 12px;">
                            <i class="fa fa-certificate" style="font-size: 24px;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Table --}}
        <div class="card" style="border-radius: 12px; border: none; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
            <div class="card-body p-0">
                @if($experts->isEmpty())
                    <div class="text-center py-5 px-4">
                        <div style="width: 100px; height: 100px; margin: 0 auto 20px;
                                    background: linear-gradient(135deg, #E8EAF6 0%, #C5CAE9 100%);
                                    border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fa fa-user-tie" style="font-size: 42px; color: #5C6BC0;"></i>
                        </div>
                        <h5 class="mb-2 fw-bold" style="color: #1A237E;">هنوز کارشناسی ثبت نشده است</h5>
                        <p class="text-muted mb-4">با کلیک روی دکمه زیر، اولین کارشناس را ثبت کنید</p>
                        <a href="{{ route('expert-catalog.create') }}"
                           class="btn text-white"
                           style="background: linear-gradient(135deg, #7986CB 0%, #5C6BC0 100%); border-radius: 12px; font-weight: 600;">
                            <i class="fa fa-plus ms-1"></i> ثبت کارشناس اول
                        </a>
                    </div>
                @else
                    <div class="table-responsive">
                        <table id="expertsTable" class="table table-hover mb-0"
                               style="width:100%; border-collapse: separate; border-spacing: 0;">
                            <thead>
                                <tr style="background: #E8EAF6;">
                                    <th style="padding: 14px 16px; font-weight: 700; color: #283593; border: none;">#</th>
                                    <th style="padding: 14px 16px; font-weight: 700; color: #283593; border: none;">نام و نام خانوادگی</th>
                                    <th style="padding: 14px 16px; font-weight: 700; color: #283593; border: none;">کد کارشناس</th>
                                    <th style="padding: 14px 16px; font-weight: 700; color: #283593; border: none;">کد ملی</th>
                                    <th style="padding: 14px 16px; font-weight: 700; color: #283593; border: none;">شماره تماس</th>
                                    <th style="padding: 14px 16px; font-weight: 700; color: #283593; border: none;">گواهی صلاحیت</th>
                                    <th style="padding: 14px 16px; font-weight: 700; color: #283593; border: none;">استان/شهر</th>
                                    <th style="padding: 14px 16px; font-weight: 700; color: #283593; border: none;">تاریخ ثبت</th>
                                    <th style="padding: 14px 16px; font-weight: 700; color: #283593; border: none;">عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($experts as $expert)
                                    <tr style="border-bottom: 1px solid #F5F5F5; transition: all 0.2s;">
                                        <td style="padding: 14px 16px; vertical-align: middle;">
                                            {{ $loop->iteration + ($experts->currentPage() - 1) * $experts->perPage() }}
                                        </td>
                                        <td style="padding: 14px 16px; vertical-align: middle;">
                                            <div class="d-flex align-items-center">
                                                <div class="me-3 d-flex align-items-center justify-content-center"
                                                     style="width: 40px; height: 40px; background: linear-gradient(135deg, #E8EAF6 0%, #C5CAE9 100%); border-radius: 10px;">
                                                    <i class="fa fa-user-tie" style="color: #5C6BC0;"></i>
                                                </div>
                                                <div>
                                                    <span class="fw-semibold d-block" style="color: #263238;">
                                                        {{ $expert->first_name }} {{ $expert->last_name }}
                                                    </span>
                                                    @if($expert->user?->email)
                                                        <small class="text-muted" style="font-size: 12px;">{{ $expert->user->email }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td style="padding: 14px 16px; vertical-align: middle; font-family: 'Vazir', monospace;">
                                            <span class="badge"
                                                  style="background: #E8EAF6; color: #283593; padding: 6px 12px; border-radius: 8px; font-size: 13px;">
                                                {{ $expert->expert_code }}
                                            </span>
                                        </td>
                                        <td style="padding: 14px 16px; vertical-align: middle; font-family: 'Vazir', monospace;">
                                            {{ $expert->national_id }}
                                        </td>
                                        <td style="padding: 14px 16px; vertical-align: middle; font-family: 'Vazir', monospace;">
                                            <div class="fw-semibold" style="color: #5C6BC0;">
                                                <i class="fa fa-mobile-alt ms-1" style="color: #7986CB;"></i>
                                                {{ $expert->mobile }}
                                            </div>
                                            @if($expert->phone)
                                                <small class="text-muted d-block mt-1" style="font-size: 12px;">ثابت: {{ $expert->phone }}</small>
                                            @endif
                                        </td>
                                        <td style="padding: 14px 16px; vertical-align: middle;">
                                            @if($expert->is_certificated)
                                                <span class="badge"
                                                      style="background: linear-gradient(135deg, #C8E6C9 0%, #A5D6A7 100%); color: #1B5E20; padding: 6px 14px; border-radius: 20px; font-weight: 600;">
                                                    <i class="fa fa-check ms-1"></i>داراست
                                                </span>
                                            @else
                                                <span class="badge"
                                                      style="background: linear-gradient(135deg, #E0E0E0 0%, #BDBDBD 100%); color: #424242; padding: 6px 14px; border-radius: 20px; font-weight: 600;">
                                                    <i class="fa fa-times ms-1"></i>ندارد
                                                </span>
                                            @endif
                                        </td>
                                        <td style="padding: 14px 16px; vertical-align: middle;">
                                            <div class="fw-semibold" style="color: #37474F;">
                                                <i class="fa fa-map-marker-alt ms-1" style="color: #FF7043;"></i>
                                                {{ $expert->province }}
                                            </div>
                                            <small class="text-muted d-block mt-1" style="font-size: 12px;">{{ $expert->city }}</small>
                                        </td>
                                        <td style="padding: 14px 16px; vertical-align: middle; font-family: 'Vazir', monospace; color: #546E7A;">
                                            {{ jdate($expert->created_at)->format('Y/m/d') }}
                                        </td>
                                        <td style="padding: 14px 16px; vertical-align: middle;">
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('expert-catalog.show', $expert) }}"
                                                   class="btn btn-sm"
                                                   style="width:36px;height:36px;padding:0;border-radius:50%;background:linear-gradient(135deg,#B2EBF2 0%,#4DD0E1 100%);color:#006064;display:inline-flex;align-items:center;justify-content:center;box-shadow:0 2px 8px rgba(77,208,225,0.3);"
                                                   title="مشاهده">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <a href="{{ route('expert-catalog.edit', $expert) }}"
                                                   class="btn btn-sm"
                                                   style="width:36px;height:36px;padding:0;border-radius:50%;background:linear-gradient(135deg,#FFE0B2 0%,#FFB74D 100%);color:#E65100;display:inline-flex;align-items:center;justify-content:center;box-shadow:0 2px 8px rgba(255,183,77,0.3);"
                                                   title="ویرایش">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <form method="POST"
                                                      action="{{ route('expert-catalog.destroy', $expert) }}"
                                                      style="display:inline;"
                                                      onsubmit="return confirm('آیا از حذف این کارشناس اطمینان دارید؟');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="btn btn-sm"
                                                            style="width:36px;height:36px;padding:0;border-radius:50%;background:linear-gradient(135deg,#FFCDD2 0%,#EF9A9A 100%);color:#B71C1C;display:inline-flex;align-items:center;justify-content:center;box-shadow:0 2px 8px rgba(239,154,154,0.3);"
                                                            title="حذف">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="p-4" style="background: #FAFAFA; border-radius: 0 0 12px 12px;">
                        {{ $experts->links() }}
                    </div>
                @endif
            </div>
        </div>

    </div>
@endsection

@section('script')
<style>
    #expertsTable tbody tr:hover { background-color: #E8EAF6 !important; }
    .pagination { justify-content: center !important; }
    .page-link { border-radius: 8px !important; margin: 0 3px; border: none; background: #E8EAF6; color: #3949AB; padding: 8px 14px; font-weight: 600; }
    .page-item.active .page-link { background: linear-gradient(135deg, #7986CB 0%, #5C6BC0 100%); color: white; box-shadow: 0 2px 8px rgba(92,107,192,0.3); }
</style>
<script>
    $(document).ready(function () {
        $('#expertsTable').DataTable({
            responsive: true,
            paging: false,
            searching: true,
            info: true,
            order: [[0, 'desc']],
            language: {
                search: "جستجو:",
                lengthMenu: "نمایش _MENU_ رکورد",
                info: "نمایش _START_ تا _END_ از _TOTAL_ رکورد",
                emptyTable: "هیچ داده‌ای موجود نیست"
            }
        });
    });
</script>
@endsection
