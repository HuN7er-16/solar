@extends('behin-layouts.app')

@section('content')
    <div class="container-fluid" style="direction: rtl; text-align: right;">
        <div class="mb-4 p-4 text-white" style="background: linear-gradient(135deg, #4FC3F7 0%, #03A9F4 100%); border-radius: 12px; box-shadow: 0 4px 20px rgba(3, 169, 244, 0.25);">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h3 class="mb-1 fw-bold"><i class="fa fa-id-card ms-2"></i>جزئیات پیمانکار</h3>
                    <p class="mb-0 opacity-90">مشاهده کامل اطلاعات ثبت‌شده برای {{ $contractor->company_name }}</p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <form method="POST" action="{{ route('contractor-catalog.destroy', $contractor) }}" style="display:inline;" onsubmit="return confirm('آیا از حذف این پیمانکار اطمینان دارید؟');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn text-white" style="background: rgba(244, 67, 54, 0.85); border-radius: 12px; font-weight: 600;">
                            <i class="fa fa-trash ms-1"></i> حذف
                        </button>
                    </form>
                    <a href="{{ route('contractor-catalog.edit', $contractor) }}" class="btn text-white" style="background: rgba(255, 193, 7, 0.9); border-radius: 12px; font-weight: 600; color: #3E2723 !important;">
                        <i class="fa fa-edit ms-1"></i> ویرایش
                    </a>
                    <a href="{{ route('contractor-catalog.index') }}" class="btn btn-light" style="border-radius: 12px; color: #0277BD; font-weight: 600;">
                        <i class="fa fa-arrow-right ms-1"></i> بازگشت
                    </a>
                </div>
            </div>
        </div>

        <div class="mb-4 p-5 text-white" style="background: linear-gradient(135deg, #81D4FA 0%, #29B6F6 100%); border-radius: 12px; box-shadow: 0 4px 20px rgba(41, 182, 246, 0.2);">
            <div class="d-flex align-items-start flex-wrap gap-4">
                <div class="me-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 100px; height: 100px; background: rgba(255,255,255,0.25); border-radius: 20px; backdrop-filter: blur(10px);">
                    <i class="fa fa-building" style="font-size: 48px;"></i>
                </div>
                <div class="flex-grow-1">
                    <h2 class="mb-2 fw-bold">{{ $contractor->company_name }}</h2>
                    <div class="row g-4 mt-3">
                        <div class="col-md-3">
                            <div class="d-flex align-items-center">
                                <i class="fa fa-id-badge ms-2 opacity-80"></i>
                                <div>
                                    <small class="opacity-80 d-block">شناسه ملی</small>
                                    <span class="fw-semibold" style="font-family: 'Vazir', monospace;">{{ $contractor->national_id }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex align-items-center">
                                <i class="fa fa-phone ms-2 opacity-80"></i>
                                <div>
                                    <small class="opacity-80 d-block">تلفن شرکت</small>
                                    <span class="fw-semibold" style="font-family: 'Vazir', monospace;">{{ $contractor->company_phone ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex align-items-center">
                                <i class="fa fa-map-marker-alt ms-2 opacity-80"></i>
                                <div>
                                    <small class="opacity-80 d-block">موقعیت</small>
                                    <span class="fw-semibold">{{ $contractor->province }} - {{ $contractor->city }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex align-items-center">
                                <i class="fa fa-calendar ms-2 opacity-80"></i>
                                <div>
                                    <small class="opacity-80 d-block">تاریخ ثبت</small>
                                    <span class="fw-semibold" style="font-family: 'Vazir', monospace;">{{ jdate($contractor->created_at)->format('Y/m/d') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            {{-- کارت کاربر سیستم --}}
            @if ($contractor->user)
            <div class="col-12">
                <div class="p-4" style="background: linear-gradient(135deg, #EDE7F6 0%, #D1C4E9 100%); border-radius: 12px; border-right: 4px solid #7986CB;">
                    <div class="d-flex align-items-center gap-3 flex-wrap">
                        <div class="d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; background: linear-gradient(135deg, #7986CB 0%, #3F51B5 100%); border-radius: 12px; flex-shrink:0;">
                            <i class="fa fa-user-circle text-white" style="font-size: 18px;"></i>
                        </div>
                        <div>
                            <small class="text-muted fw-semibold d-block">کاربر سیستم (نقش پیمانکار)</small>
                            <span class="fw-bold" style="color: #283593; font-size: 17px;">{{ $contractor->user->name }}</span>
                        </div>
                        @if ($contractor->user->email)
                        <div class="me-4">
                            <small class="text-muted fw-semibold d-block">ایمیل</small>
                            <span style="color: #37474F;">{{ $contractor->user->email }}</span>
                        </div>
                        @endif
                        @if ($contractor->user->phone)
                        <div>
                            <small class="text-muted fw-semibold d-block">شماره تماس</small>
                            <span style="color: #37474F; font-family: monospace;">{{ $contractor->user->phone }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <div class="col-md-4">
                <div class="p-4 h-100" style="background: linear-gradient(135deg, #FFF8E1 0%, #FFECB3 100%); border-radius: 12px; border-right: 4px solid #FFB300; box-shadow: 0 4px 15px rgba(255, 179, 0, 0.15);">
                    <div class="d-flex align-items-center mb-4">
                        <div class="me-3 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; background: linear-gradient(135deg, #FFB74D 0%, #FF9800 100%); border-radius: 12px;">
                            <i class="fa fa-user-tie text-white" style="font-size: 18px;"></i>
                        </div>
                        <h5 class="mb-0 fw-bold" style="color: #E65100;">مدیر عامل</h5>
                    </div>
                    <div class="space-y-3">
                        <div class="mb-3">
                            <small class="text-muted d-block mb-1 fw-semibold">نام و نام خانوادگی</small>
                            <div class="fw-bold" style="color: #3E2723; font-size: 17px;">{{ $contractor->ceo_name }}</div>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted d-block mb-1 fw-semibold">کد ملی</small>
                            <div style="color: #5D4037; font-family: 'Vazir', monospace;">{{ $contractor->ceo_national_code }}</div>
                        </div>
                        <div>
                            <small class="text-muted d-block mb-1 fw-semibold">شماره همراه</small>
                            <div style="color: #5D4037; font-family: 'Vazir', monospace;">
                                <i class="fa fa-whatsapp ms-1" style="color: #25D366;"></i>
                                {{ $contractor->ceo_mobile }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4 h-100" style="background: linear-gradient(135deg, #E8F5E9 0%, #C8E6C9 100%); border-radius: 12px; border-right: 4px solid #66BB6A; box-shadow: 0 4px 15px rgba(102, 187, 106, 0.15);">
                    <div class="d-flex align-items-center mb-4">
                        <div class="me-3 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; background: linear-gradient(135deg, #81C784 0%, #4CAF50 100%); border-radius: 12px;">
                            <i class="fa fa-user text-white" style="font-size: 18px;"></i>
                        </div>
                        <h5 class="mb-0 fw-bold" style="color: #1B5E20;">شخص رابط</h5>
                    </div>
                    <div class="space-y-3">
                        <div class="mb-3">
                            <small class="text-muted d-block mb-1 fw-semibold">نام و نام خانوادگی</small>
                            <div class="fw-bold" style="color: #1B5E20; font-size: 17px;">{{ $contractor->contact_person_name }}</div>
                        </div>
                        <div>
                            <small class="text-muted d-block mb-1 fw-semibold">شماره همراه</small>
                            <div style="color: #2E7D32; font-family: 'Vazir', monospace;">
                                <i class="fa fa-phone ms-1" style="color: #4CAF50;"></i>
                                {{ $contractor->contact_person_mobile }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4 h-100" style="background: linear-gradient(135deg, #E0F7FA 0%, #B2EBF2 100%); border-radius: 12px; border-right: 4px solid #4DD0E1; box-shadow: 0 4px 15px rgba(77, 208, 225, 0.15);">
                    <div class="d-flex align-items-center mb-4">
                        <div class="me-3 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; background: linear-gradient(135deg, #4DD0E1 0%, #00BCD4 100%); border-radius: 12px;">
                            <i class="fa fa-certificate text-white" style="font-size: 18px;"></i>
                        </div>
                        <h5 class="mb-0 fw-bold" style="color: #006064;">پروانه فعالیت</h5>
                    </div>
                    <div class="space-y-3">
                        <div class="mb-3">
                            <small class="text-muted d-block mb-1 fw-semibold">شماره پروانه</small>
                            <div class="fw-bold" style="color: #006064; font-size: 17px; font-family: 'Vazir', monospace;">{{ $contractor->license_number }}</div>
                        </div>
                        <div class="mb-3">
                            <div class="row">
                                <div class="col-6">
                                    <small class="text-muted d-block mb-1 fw-semibold">تاریخ صدور</small>
                                    <div style="color: #00838F; font-family: 'Vazir', monospace; font-size: 13px;">{{ $contractor->license_issue_date ? toJalaliFormatted($contractor->license_issue_date, 'Y/m/d') : '-' }}</div>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted d-block mb-1 fw-semibold">تاریخ انقضا</small>
                                    <div style="color: #00838F; font-family: 'Vazir', monospace; font-size: 13px;">{{ $contractor->license_expiry_date ? toJalaliFormatted($contractor->license_expiry_date, 'Y/m/d') : '-' }}</div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <small class="text-muted d-block mb-2 fw-semibold">وضعیت پروانه</small>
                            @if($contractor->is_license_valid)
                                <span class="badge w-100 text-center" style="background: linear-gradient(135deg, #C8E6C9 0%, #A5D6A7 100%); color: #1B5E20; padding: 10px 14px; border-radius: 10px; font-weight: 700; font-size: 14px;">
                                    <i class="fa fa-check-circle ms-1"></i> معتبر
                                </span>
                            @else
                                <span class="badge w-100 text-center" style="background: linear-gradient(135deg, #FFCDD2 0%, #EF9A9A 100%); color: #B71C1C; padding: 10px 14px; border-radius: 10px; font-weight: 700; font-size: 14px;">
                                    <i class="fa fa-times-circle ms-1"></i> منقضی شده
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-8">
                <div class="card h-100" style="border-radius: 12px; border: none; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
                    <div class="card-header d-flex align-items-center" style="background: linear-gradient(135deg, #F3E5F5 0%, #E1BEE7 100%); border-radius: 12px 12px 0 0; border: none; padding: 16px 20px;">
                        <div class="me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: linear-gradient(135deg, #BA68C8 0%, #9C27B0 100%); border-radius: 10px;">
                            <i class="fa fa-map-marker-alt text-white" style="font-size: 16px;"></i>
                        </div>
                        <h5 class="mb-0 fw-bold" style="color: #4A148C;">آدرس کامل شرکت</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <small class="text-muted d-block mb-1 fw-semibold">استان</small>
                                <div class="fw-semibold" style="color: #37474F;">{{ $contractor->province }}</div>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted d-block mb-1 fw-semibold">شهر</small>
                                <div class="fw-semibold" style="color: #37474F;">{{ $contractor->city }}</div>
                            </div>
                        </div>
                        <div style="background: #F3E5F5; padding: 16px; border-radius: 10px; border-right: 4px solid #CE93D8;">
                            <i class="fa fa-location-arrow ms-2" style="color: #7B1FA2;"></i>
                            <span class="fw-semibold" style="color: #4A148C;">{{ $contractor->address }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100" style="border-radius: 12px; border: none; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
                    <div class="card-header d-flex align-items-center" style="background: linear-gradient(135deg, #FFF3E0 0%, #FFE0B2 100%); border-radius: 12px 12px 0 0; border: none; padding: 16px 20px;">
                        <div class="me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: linear-gradient(135deg, #FFB74D 0%, #FF9800 100%); border-radius: 10px;">
                            <i class="fa fa-chart-bar text-white" style="font-size: 16px;"></i>
                        </div>
                        <h5 class="mb-0 fw-bold" style="color: #E65100;">آمار و اطلاعات سیستم</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between mb-4 p-3" style="background: #FFF3E0; border-radius: 10px;">
                            <span class="fw-semibold" style="color: #5D4037;">تعداد پروژه‌های ثبت شده</span>
                            <span class="badge" style="background: linear-gradient(135deg, #FFB74D, #FF9800); color: white; padding: 8px 18px; border-radius: 20px; font-size: 16px; font-weight: 700;">{{ $contractor->registered_projects_count }}</span>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted d-block mb-1 fw-semibold">تاریخ ثبت در سیستم</small>
                            <div style="color: #37474F; font-family: 'Vazir', monospace;">
                                <i class="fa fa-calendar-check ms-1" style="color: #FF9800;"></i>
                                {{ jdate($contractor->created_at)->format('Y/m/d H:i') }}
                            </div>
                        </div>
                        <div>
                            <small class="text-muted d-block mb-1 fw-semibold">آخرین ویرایش</small>
                            <div style="color: #37474F; font-family: 'Vazir', monospace;">
                                <i class="fa fa-edit ms-1" style="color: #2196F3;"></i>
                                {{ jdate($contractor->updated_at)->format('Y/m/d H:i') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if(isset($contractor->projects) && $contractor->projects->isNotEmpty())
        <div class="card" style="border-radius: 12px; border: none; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
            <div class="card-header d-flex align-items-center justify-content-between" style="background: linear-gradient(135deg, #E3F2FD 0%, #BBDEFB 100%); border-radius: 12px 12px 0 0; border: none; padding: 16px 20px;">
                <div class="d-flex align-items-center">
                    <div class="me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: linear-gradient(135deg, #64B5F6 0%, #1976D2 100%); border-radius: 10px;">
                        <i class="fa fa-project-diagram text-white" style="font-size: 16px;"></i>
                    </div>
                    <h5 class="mb-0 fw-bold" style="color: #0D47A1;">پروژه‌های مربوط به این پیمانکار</h5>
                </div>
                <span class="badge" style="background: white; color: #1976D2; padding: 8px 16px; border-radius: 20px; font-weight: 700;">{{ $contractor->projects->count() }} پروژه</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" style="border-collapse: separate; border-spacing: 0;">
                        <thead>
                            <tr style="background: #FFF3E0;">
                                <th style="padding: 14px 16px; font-weight: 700; color: #E65100; border: none;">#</th>
                                <th style="padding: 14px 16px; font-weight: 700; color: #E65100; border: none;">نام پروژه</th>
                                <th style="padding: 14px 16px; font-weight: 700; color: #E65100; border: none;">موقعیت</th>
                                <th style="padding: 14px 16px; font-weight: 700; color: #E65100; border: none;">وضعیت</th>
                                <th style="padding: 14px 16px; font-weight: 700; color: #E65100; border: none;">تاریخ شروع</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($contractor->projects as $project)
                                <tr style="border-bottom: 1px solid #F5F5F5;">
                                    <td style="padding: 12px 16px;">{{ $loop->iteration }}</td>
                                    <td style="padding: 12px 16px; font-weight: 600;">{{ $project->name ?? 'پروژه ' . $loop->iteration }}</td>
                                    <td style="padding: 12px 16px;">{{ $project->province ?? '-' }} - {{ $project->city ?? '-' }}</td>
                                    <td style="padding: 12px 16px;">
                                        <span class="badge" style="background: linear-gradient(135deg, #BBDEFB, #64B5F6); color: #0D47A1; padding: 6px 12px; border-radius: 20px;">{{ $project->status ?? 'در حال اجرا' }}</span>
                                    </td>
                                    <td style="padding: 12px 16px; font-family: 'Vazir', monospace;">{{ isset($project->start_date) ? toJalaliFormatted($project->start_date, 'Y/m/d') : '-' }}</td>
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
