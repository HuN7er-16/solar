@extends('behin-layouts.app')

@php
    $disableBackBtn = false;
@endphp

@section('content')
    <style>
        :root {
            --solar-primary: #FF9800;
            --solar-gradient-start: #FFB74D;
            --solar-gradient-end: #FF9800;
            --solar-card-radius: 12px;
        }

        * {
            direction: rtl;
        }

        body {
            background: linear-gradient(135deg, #FFF8E1 0%, #FFFFFF 100%);
            min-height: 100vh;
        }

        .dashboard-container {
            padding: 1.5rem 1rem;
        }

        .welcome-card {
            border-radius: var(--solar-card-radius);
            background: linear-gradient(135deg, var(--solar-gradient-start) 0%, var(--solar-gradient-end) 100%);
            color: white;
            padding: 2rem;
            box-shadow: 0 8px 32px rgba(255, 152, 0, 0.25);
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }

        .welcome-card::before {
            content: '';
            position: absolute;
            top: -60px;
            left: -60px;
            width: 240px;
            height: 240px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
        }

        .welcome-card::after {
            content: '';
            position: absolute;
            bottom: -80px;
            right: -40px;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.06);
            border-radius: 50%;
        }

        .welcome-content {
            position: relative;
            z-index: 2;
        }

        .welcome-icon {
            font-size: 64px;
            opacity: 0.9;
            animation: spin 20s linear infinite;
        }

        .welcome-logo {
            width: 96px;
            height: 96px;
            max-width: 96px;
            max-height: 96px;
            object-fit: contain;
            background: rgba(255, 255, 255, 0.95);
            border: 2px solid rgba(255, 255, 255, 0.6);
            border-radius: 16px;
            padding: 6px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.18);
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .welcome-title {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .welcome-subtitle {
            font-size: 1rem;
            opacity: 0.95;
            margin: 0;
        }

        .date-time-box {
            background: rgba(255, 255, 255, 0.18);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            padding: 1rem 1.5rem;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .date-time-label {
            font-size: 0.78rem;
            opacity: 0.85;
            margin: 0;
        }

        .date-time-value {
            font-size: 1.15rem;
            font-weight: 700;
            margin: 0.25rem 0 0 0;
        }

        .stat-card {
            border-radius: var(--solar-card-radius);
            background: white;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
            padding: 1.5rem;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 90px;
            height: 90px;
            opacity: 0.08;
            border-radius: 0 0 0 90px;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 36px rgba(0, 0, 0, 0.1);
        }

        .stat-icon-wrap {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            margin-bottom: 1rem;
            flex-shrink: 0;
        }

        .stat-number {
            font-size: 2.25rem;
            font-weight: 800;
            color: #263238;
            margin: 0;
            line-height: 1.1;
        }

        .stat-label {
            font-size: 0.92rem;
            color: #78909C;
            margin: 0.35rem 0 0 0;
            font-weight: 500;
        }

        .icon-grad-sun { background: linear-gradient(135deg, #FFB74D, #FF9800); color: white; }
        .stat-card.grad-sun::before { background: #FF9800; }
        .icon-grad-blue { background: linear-gradient(135deg, #64B5F6, #42A5F5); color: white; }
        .stat-card.grad-blue::before { background: #42A5F5; }
        .icon-grad-green { background: linear-gradient(135deg, #81C784, #66BB6A); color: white; }
        .stat-card.grad-green::before { background: #66BB6A; }
        .icon-grad-purple { background: linear-gradient(135deg, #BA68C8, #AB47BC); color: white; }
        .stat-card.grad-purple::before { background: #AB47BC; }
        .icon-grad-red { background: linear-gradient(135deg, #E57373, #EF5350); color: white; }
        .stat-card.grad-red::before { background: #EF5350; }
        .icon-grad-teal { background: linear-gradient(135deg, #4DB6AC, #26A69A); color: white; }
        .stat-card.grad-teal::before { background: #26A69A; }
        .icon-grad-cyan { background: linear-gradient(135deg, #4DD0E1, #26C6DA); color: white; }
        .stat-card.grad-cyan::before { background: #26C6DA; }
        .icon-grad-orange { background: linear-gradient(135deg, #FFB74D, #FB8C00); color: white; }
        .stat-card.grad-orange::before { background: #FB8C00; }

        .section-card {
            border-radius: var(--solar-card-radius);
            background: white;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
            border: none;
            overflow: hidden;
            margin-bottom: 1.5rem;
        }

        .section-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #F5F5F5;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .section-title {
            font-size: 1.15rem;
            font-weight: 700;
            color: #37474F;
            margin: 0;
            display: flex;
            align-items: center;
        }

        .section-title i {
            margin-left: 0.6rem;
            color: var(--solar-primary);
            font-size: 1.2rem;
        }

        .activity-card {
            background: linear-gradient(135deg, #E3F2FD 0%, #BBDEFB 100%);
        }

        .activity-card .section-header {
            border-bottom: 1px solid rgba(100, 181, 246, 0.3);
            background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
            color: white;
        }

        .activity-card .section-title,
        .activity-card .section-title i {
            color: white;
        }

        .activity-list {
            padding: 0.5rem 0;
        }

        .activity-item {
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            border-bottom: 1px solid rgba(100, 181, 246, 0.2);
            transition: background 0.2s ease;
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-item:hover {
            background: rgba(255, 255, 255, 0.5);
        }

        .activity-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: rgba(33, 150, 243, 0.15);
            color: #1976D2;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
            margin-left: 1rem;
        }

        .activity-content {
            flex-grow: 1;
        }

        .activity-text {
            font-size: 0.92rem;
            color: #37474F;
            font-weight: 500;
            margin: 0;
        }

        .activity-meta {
            font-size: 0.78rem;
            color: #78909C;
            margin: 0.2rem 0 0 0;
        }

        .quick-link-card {
            border-radius: var(--solar-card-radius);
            background: white;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
            border: none;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .quick-link-btn {
            display: flex;
            align-items: center;
            padding: 1.25rem 1.5rem;
            border-radius: var(--solar-card-radius);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            border: 2px solid transparent;
            position: relative;
            overflow: hidden;
            min-height: 80px;
        }

        .quick-link-btn::before {
            content: '';
            position: absolute;
            top: 0;
            right: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: right 0.6s ease;
        }

        .quick-link-btn:hover::before {
            right: 100%;
        }

        .quick-link-btn:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            color: white;
            text-decoration: none;
        }

        .ql-icon {
            width: 50px;
            height: 50px;
            border-radius: 14px;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-left: 1rem;
            flex-shrink: 0;
        }

        .ql-text {
            flex-grow: 1;
            font-size: 0.98rem;
            line-height: 1.4;
        }

        .ql-text small {
            display: block;
            opacity: 0.85;
            font-weight: 400;
            font-size: 0.78rem;
            margin-top: 0.2rem;
        }

        .ql-arrow {
            font-size: 20px;
            opacity: 0.9;
        }

        .ql-sun { background: linear-gradient(135deg, #FFB74D, #FF9800); color: white; }
        .ql-blue { background: linear-gradient(135deg, #64B5F6, #1976D2); color: white; }
        .ql-green { background: linear-gradient(135deg, #81C784, #388E3C); color: white; }
        .ql-purple { background: linear-gradient(135deg, #BA68C8, #7B1FA2); color: white; }
        .ql-teal { background: linear-gradient(135deg, #4DB6AC, #00796B); color: white; }
        .ql-red { background: linear-gradient(135deg, #EF5350, #C62828); color: white; }
        .ql-dark { background: linear-gradient(135deg, #5a2d82, #7b3fa8); color: white; }
        .ql-orange { background: linear-gradient(135deg, #FFA726, #F57C00); color: white; }

        .role-section-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #455A64;
            margin: 0 0 1rem 0;
            padding-right: 0.75rem;
            border-right: 4px solid var(--solar-primary);
        }

        .old-small-box-wrap {
            margin-top: 1rem;
        }

        .old-small-box-wrap .small-box {
            border-radius: var(--solar-card-radius);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            border: none;
        }
    </style>

    <div class="dashboard-container">
        @php
            $todayJalali = function_exists('jdate') ? jdate()->format('Y/m/d') : '';
            $currentTime = date('H:i');
        @endphp

        <div class="welcome-card">
            <div class="welcome-content">
                @php
                    $dashboardLogoPaths = [
                        'behin/images/logo-union.png',
                        'behin/images/logo.png',
                        'behin/images/ieeu-logo.png',
                        'behin/logo.png',
                    ];
                    $dashboardLogo = null;
                    foreach ($dashboardLogoPaths as $lp) {
                        if (file_exists(public_path($lp))) {
                            $dashboardLogo = $lp;
                            break;
                        }
                    }
                @endphp

                <div class="row align-items-center g-3">
                    {{-- ستون اول: لوگو --}}
                    @if ($dashboardLogo)
                        <div class="col-xl-2 col-lg-2 col-md-3 col-sm-4 col-12 d-flex justify-content-center justify-content-md-start">
                            <img src="{{ asset($dashboardLogo) }}" alt="لوگوی اتحادیه" class="welcome-logo">
                        </div>
                    @endif

                    {{-- ستون دوم: متن خوش آمد و آیکون خورشید --}}
                    <div class="@if ($dashboardLogo) col-xl-6 col-lg-6 col-md-6 col-sm-8 @else col-lg-7 col-md-8 @endif col-12">
                        <div class="d-flex align-items-start">
                            <i class="fa fa-sun-o welcome-icon ms-4 flex-shrink-0 mt-1"></i>
                            <div>
                                <h1 class="welcome-title">خوش آمدید، {{ auth()->user()->name ?? 'کاربر عزیز' }} 👋</h1>
                                <p class="welcome-subtitle">به پنل مدیریت سامانه جامع انرژی‌های تجدیدپذیر و خورشیدی اصناف (ساتا اصناف) خوش آمدید. امروز روز خوبی برای انرژی پاک است!</p>
                            </div>
                        </div>
                    </div>

                    {{-- ستون سوم: تاریخ و ساعت --}}
                    <div class="@if ($dashboardLogo) col-xl-4 col-lg-4 col-md-3 @else col-lg-5 col-md-4 @endif col-sm-12 mt-2 mt-lg-0">
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="date-time-box text-center">
                                    <p class="date-time-label"><i class="fa fa-calendar ms-1"></i>تاریخ امروز</p>
                                    <p class="date-time-value" id="todayDate">{{ $todayJalali }}</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="date-time-box text-center">
                                    <p class="date-time-label"><i class="fa fa-clock-o ms-1"></i>ساعت فعلی</p>
                                    <p class="date-time-value" id="currentTime">{{ $currentTime }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @php
            use SolarPlantEquipment\Models\SolarProject;
            use SolarPlantRequests\Models\SolarPlantRequest;
            use PanelCatalog\Models\PanelCatalog;
            use InverterCatalog\Models\InverterCatalog;
            use BatteryCatalog\Models\BatteryCatalog;
            use ContractorCatalog\Models\Contractor;
            use InspectorCatalog\Models\Inspector;
            use App\Models\User;

            $totalProjects = class_exists(SolarProject::class) ? SolarProject::query()->count() : 0;
            $totalRequests = class_exists(SolarPlantRequest::class) ? SolarPlantRequest::query()->count() : 0;
            $totalPanels = class_exists(PanelCatalog::class) ? PanelCatalog::query()->count() : 0;
            $totalInverters = class_exists(InverterCatalog::class) ? InverterCatalog::query()->count() : 0;
            $totalBatteries = class_exists(BatteryCatalog::class) ? BatteryCatalog::query()->count() : 0;
            $totalContractors = class_exists(Contractor::class) ? Contractor::query()->count() : 0;
            $activeContractors = class_exists(Contractor::class) ? Contractor::query()->where('license_expiry_date', '>', now())->count() : 0;
            $totalInspectors = class_exists(Inspector::class) ? Inspector::query()->count() : 0;
            $activeInspectors = class_exists(Inspector::class) ? Inspector::query()->where('is_certificated', true)->count() : 0;
            $totalUsers = class_exists(User::class) ? User::query()->count() : 0;
        @endphp

        @if (auth()->user()->role_id == 1 || auth()->user()->role_id == 13)
            <div class="row g-4 mb-4">
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="stat-card grad-sun">
                        <div class="stat-icon-wrap icon-grad-sun">
                            <i class="fa fa-solar-panel"></i>
                        </div>
                        <p class="stat-number">{{ $totalProjects }}</p>
                        <p class="stat-label">کل پروژه‌ها</p>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                    <div class="stat-card grad-blue">
                        <div class="stat-icon-wrap icon-grad-blue">
                            <i class="fa fa-file-text-o"></i>
                        </div>
                        <p class="stat-number">{{ $totalRequests }}</p>
                        <p class="stat-label">کل درخواست‌ها</p>
                    </div>
                </div>

                @if (auth()->user()->role_id == 1)
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                        <div class="stat-card grad-orange">
                            <div class="stat-icon-wrap icon-grad-orange">
                                <i class="fa fa-sun-o"></i>
                            </div>
                            <p class="stat-number">{{ $totalPanels }}</p>
                            <p class="stat-label">پنل‌های خورشیدی</p>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                        <div class="stat-card grad-teal">
                            <div class="stat-icon-wrap icon-grad-teal">
                                <i class="fa fa-bolt"></i>
                            </div>
                            <p class="stat-number">{{ $totalInverters }}</p>
                            <p class="stat-label">اینورترها</p>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                        <div class="stat-card grad-green">
                            <div class="stat-icon-wrap icon-grad-green">
                                <i class="fa fa-battery-full"></i>
                            </div>
                            <p class="stat-number">{{ $totalBatteries }}</p>
                            <p class="stat-label">باتری‌ها</p>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                        <div class="stat-card grad-purple">
                            <div class="stat-icon-wrap icon-grad-purple">
                                <i class="fa fa-building-o"></i>
                            </div>
                            <p class="stat-number">{{ $activeContractors }}<span style="font-size:1rem;font-weight:500;color:#90A4AE;margin-right:4px;">از {{ $totalContractors }}</span></p>
                            <p class="stat-label">پیمانکاران فعال</p>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                        <div class="stat-card grad-cyan">
                            <div class="stat-icon-wrap icon-grad-cyan">
                                <i class="fa fa-user-circle-o"></i>
                            </div>
                            <p class="stat-number">{{ $activeInspectors }}<span style="font-size:1rem;font-weight:500;color:#90A4AE;margin-right:4px;">از {{ $totalInspectors }}</span></p>
                            <p class="stat-label">بازرس‌های فعال</p>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                        <div class="stat-card grad-red">
                            <div class="stat-icon-wrap icon-grad-red">
                                <i class="fa fa-users"></i>
                            </div>
                            <p class="stat-number">{{ $totalUsers }}</p>
                            <p class="stat-label">کاربران سامانه</p>
                        </div>
                    </div>
                @endif
            </div>

            @if (auth()->user()->role_id == 1)
                <div class="row g-4 mb-4">
                    <div class="col-12">
                        <div class="section-card activity-card">
                            <div class="section-header">
                                <h3 class="section-title"><i class="fa fa-list-alt"></i>رویدادها و فعالیت‌های اخیر</h3>
                                <span class="badge" style="background: rgba(255,255,255,0.2); color: white;">نمای کلی</span>
                            </div>
                            <div class="activity-list">
                                <div class="activity-item">
                                    <div class="activity-icon"><i class="fa fa-solar-panel"></i></div>
                                    <div class="activity-content">
                                        <p class="activity-text">{{ $totalPanels }} پنل خورشیدی در کاتالوگ سیستم ثبت شده است</p>
                                        <p class="activity-meta">آخرین به‌روزرسانی: {{ $todayJalali }} ساعت {{ $currentTime }}</p>
                                    </div>
                                </div>
                                <div class="activity-item">
                                    <div class="activity-icon"><i class="fa fa-bolt"></i></div>
                                    <div class="activity-content">
                                        <p class="activity-text">{{ $totalInverters }} اینورتر و {{ $totalBatteries }} باتری در سیستم موجود می‌باشد</p>
                                        <p class="activity-meta">کاتالوگ تجهیزات</p>
                                    </div>
                                </div>
                                <div class="activity-item">
                                    <div class="activity-icon"><i class="fa fa-file-text-o"></i></div>
                                    <div class="activity-content">
                                        <p class="activity-text">{{ $totalRequests }} درخواست ثبت شده و {{ $totalProjects }} پروژه در حال پیگیری</p>
                                        <p class="activity-meta">مدیریت درخواست‌ها و پروژه‌ها</p>
                                    </div>
                                </div>
                                <div class="activity-item">
                                    <div class="activity-icon"><i class="fa fa-users"></i></div>
                                    <div class="activity-content">
                                        <p class="activity-text">{{ $activeContractors }} پیمانکار فعال و {{ $activeInspectors }} بازرس فعال در سامانه</p>
                                        <p class="activity-meta">منابع انسانی پروژه</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="quick-link-card">
                <h3 class="section-title mb-4"><i class="fa fa-compass"></i>دسترسی سریع</h3>
            @if (auth()->user()->role_id == 13 || auth()->user()->role_id == 1)
                <div class="old-small-box-wrap">
                    <h4 class="role-section-title">بخش‌های مخصوص بازرس</h4>
                        <h5 class="role-section-title mb-3 mt-2"><i class="fa fa-clipboard-check ms-2" style="color:#E53935;"></i>بازرسی پروژه</h5>
                        <div class="row g-3 mb-5">
                            <div class="col-12">
                                <a href="{{ route('project-inspection.inspections.index') }}" class="quick-link-btn ql-red">
                                    <div class="ql-icon"><i class="fa fa-clipboard-check"></i></div>
                                    <div class="ql-text">ثبت بازرسی پروژه<small>گزارش بازرسی و نظارت فنی بر نیروگاه خورشیدی</small></div>
                                    <i class="fa fa-angle-left ql-arrow"></i>
                                </a>
                            </div>
                        </div>
                </div>
            @endif                



                @if (auth()->user()->role_id == 1)
                    <h5 class="role-section-title mb-3"><i class="fa fa-folder-open ms-2" style="color:#1976D2;"></i>مدیریت پروژه‌ها و درخواست‌ها</h5>
                    <div class="row g-3 mb-5">
                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                            <a href="{{ route('solar-plant-equipment.projects.create') ?? '#' }}" class="quick-link-btn ql-sun" onerror="this.href='#'">
                                <div class="ql-icon"><i class="fa fa-plus-circle"></i></div>
                                <div class="ql-text">ثبت پروژه جدید<small>ایجاد پروژه نیروگاه خورشیدی</small></div>
                                <i class="fa fa-angle-left ql-arrow"></i>
                            </a>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                            <a href="{{ route('solar-plant-equipment.projects.index') }}" class="quick-link-btn ql-blue">
                                <div class="ql-icon"><i class="fa fa-list-ul"></i></div>
                                <div class="ql-text">لیست پروژه‌ها<small>مدیریت پروژه‌های نیروگاه</small></div>
                                <i class="fa fa-angle-left ql-arrow"></i>
                            </a>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                            <a href="{{ route('solar-plant-requests.all-requests.index') }}" class="quick-link-btn ql-orange">
                                <div class="ql-icon"><i class="ion ion-bag"></i></div>
                                <div class="ql-text">درخواست‌ها<small>مراحل تخصیص پیمانکار و ثبت بازرسی</small></div>
                                <i class="fa fa-angle-left ql-arrow"></i>
                            </a>
                        </div>
                    </div>

                    <h5 class="role-section-title mb-3"><i class="fa fa-users ms-2" style="color:#7B1FA2;"></i>لیست افراد</h5>
                    <div class="row g-3 mb-5">
                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                            <a href="{{ route('contractor-catalog.index') }}" class="quick-link-btn ql-purple">
                                <div class="ql-icon"><i class="ion ion-person-stalker"></i></div>
                                <div class="ql-text">لیست پیمانکاران<small>مدیریت و مشاهده اطلاعات پیمانکاران</small></div>
                                <i class="fa fa-angle-left ql-arrow"></i>
                            </a>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                            <a href="{{ route('inspector-catalog.index') }}" class="quick-link-btn ql-teal">
                                <div class="ql-icon"><i class="fa fa-user-check"></i></div>
                                <div class="ql-text">لیست بازرس‌ها<small>مدیریت و مشاهده اطلاعات بازرس‌های سامانه</small></div>
                                <i class="fa fa-angle-left ql-arrow"></i>
                            </a>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                            <a href="{{ route('expert-catalog.index') }}" class="quick-link-btn"
                               style="background:linear-gradient(135deg,#7986CB,#5C6BC0);color:white;">
                                <div class="ql-icon"><i class="fa fa-user-tie"></i></div>
                                <div class="ql-text">لیست کارشناسان<small>مدیریت و مشاهده اطلاعات کارشناسان سامانه</small></div>
                                <i class="fa fa-angle-left ql-arrow"></i>
                            </a>
                        </div>
                    </div>

                    <h5 class="role-section-title mb-3"><i class="fa fa-user-tag ms-2" style="color:#3949AB;"></i>مدیریت کارشناسان</h5>
                    <div class="row g-3 mb-5">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <a href="{{ route('request-expert-review.admin.index') }}" class="quick-link-btn"
                               style="background:linear-gradient(135deg,#5C6BC0,#3949AB);color:white;">
                                <div class="ql-icon"><i class="fa fa-tasks"></i></div>
                                <div class="ql-text">تخصیص کارشناس به تقاضاها<small>اختصاص کارشناس به تقاضاهای در وضعیت ثبت اولیه</small></div>
                                <i class="fa fa-angle-left ql-arrow"></i>
                            </a>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <a href="{{ route('expert-catalog.create') }}" class="quick-link-btn"
                               style="background:linear-gradient(135deg,#7986CB,#5C6BC0);color:white;">
                                <div class="ql-icon"><i class="fa fa-user-plus"></i></div>
                                <div class="ql-text">افزودن کارشناس جدید<small>ثبت اطلاعات کارشناس و اختصاص نقش</small></div>
                                <i class="fa fa-angle-left ql-arrow"></i>
                            </a>
                        </div>
                    </div>

                    <h5 class="role-section-title mb-3"><i class="fa fa-solar-panel ms-2" style="color:#FB8C00;"></i>کاتالوگ تجهیزات</h5>
                    <div class="row g-3 mb-5">
                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                            <a href="{{ route('panel-catalog.index') }}" class="quick-link-btn ql-orange">
                                <div class="ql-icon"><i class="fa fa-sun-o"></i></div>
                                <div class="ql-text">کاتالوگ پنل<small>مشاهده مشخصات فنی پنل‌های خورشیدی</small></div>
                                <i class="fa fa-angle-left ql-arrow"></i>
                            </a>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                            <a href="{{ route('inverter-catalog.index') }}" class="quick-link-btn ql-teal">
                                <div class="ql-icon"><i class="fa fa-bolt"></i></div>
                                <div class="ql-text">کاتالوگ اینورتر<small>مدیریت و مشاهده انواع اینورترها</small></div>
                                <i class="fa fa-angle-left ql-arrow"></i>
                            </a>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                            <a href="{{ route('battery-catalog.index') }}" class="quick-link-btn ql-green">
                                <div class="ql-icon"><i class="ion ion-battery-full"></i></div>
                                <div class="ql-text">کاتالوگ باتری<small>مدیریت و مشاهده مشخصات فنی باتری‌ها</small></div>
                                <i class="fa fa-angle-left ql-arrow"></i>
                            </a>
                        </div>
                    </div>
                @endif

                @if (auth()->user()->role_id == 1)
                    <div class="row g-3">
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                            <a href="{{ url('/admin/seed-mock-data') }}" class="quick-link-btn ql-dark">
                                <div class="ql-icon"><i class="fa fa-flask"></i></div>
                                <div class="ql-text">تولید داده Mock (تست)<small>ساخت رکوردهای نمونه برای جداول</small></div>
                                <i class="fa fa-angle-left ql-arrow"></i>
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        @endif

        @if (auth()->user()->role_id == 3)
            <div class="old-small-box-wrap">
                <h4 class="role-section-title">بخش‌های متقاضی</h4>
                <div class="row g-3">
                    <div class="col-sm-3">
                        <div class="small-box ql-green" style="color:white; padding: 1.25rem; border-radius: 12px;">
                            <div class="inner">
                                <h6 style="font-weight:700; margin:0;">{{ trans('درخواست‌های من') }}</h6>
                                <p style="opacity:0.9; margin:0.3rem 0 0 0; font-size:0.85rem;">{{ trans('پیگیری وضعیت درخواست‌ها و ثبت درخواست جدید') }}</p>
                            </div>
                            <div class="icon" style="position:absolute; top:1rem; left:1rem; font-size:48px; opacity:0.3;">
                                <i class="ion ion-stats-bars"></i>
                            </div>
                            <a href="{{ route('solar-plant-requests.index') }}"
                                class="small-box-footer" style="display:block; color:white; padding:0.6rem; background:rgba(0,0,0,0.15); text-align:center; margin-top:1rem; border-radius:8px; text-decoration:none;">
                                {{ trans('مشاهده') }} <i class="fa fa-arrow-circle-left"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if (auth()->user()->role_id == 5)
            <div class="old-small-box-wrap">
                <h4 class="role-section-title">بخش‌های پیمانکار</h4>
                <div class="row g-3">
                    <div class="col-sm-3">
                        <div class="small-box ql-blue" style="color:white; padding: 1.25rem; border-radius: 12px;">
                            <div class="inner">
                                <h6 style="font-weight:700; margin:0;">{{ trans('درخواست ها') }}</h6>
                                <p style="opacity:0.9; margin:0.3rem 0 0 0; font-size:0.85rem;">{{ trans('در مرحله نصب تجهیزات') }}</p>
                            </div>
                            <div class="icon" style="position:absolute; top:1rem; left:1rem; font-size:48px; opacity:0.3;">
                                <i class="ion ion-bag"></i>
                            </div>
                            <a href="{{ route('solar-plant-requests.contractor.index') }}"
                                class="small-box-footer" style="display:block; color:white; padding:0.6rem; background:rgba(0,0,0,0.15); text-align:center; margin-top:1rem; border-radius:8px; text-decoration:none;">
                                {{ trans('مشاهده') }} <i class="fa fa-arrow-circle-left"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @php
            $expertRoleId = config('request-expert-review.roles.expert', 14);
        @endphp

        @if ($expertRoleId && auth()->user()->role_id == $expertRoleId)
            <div class="quick-link-card">
                <h3 class="section-title mb-4"><i class="fa fa-compass ms-2" style="color:#5C6BC0;"></i>بخش‌های کارشناس</h3>

                @php
                    try {
                        $expertPendingCount = \SolarPlantRequests\Models\SolarPlantRequest::query()
                            ->where('status', \SolarPlantRequests\Enums\SolarPlantRequestStatus::UNDER_REVIEW)
                            ->where('expert_user_id', auth()->id())
                            ->count();
                    } catch (\Throwable $e) {
                        $expertPendingCount = 0;
                    }
                @endphp

                {{-- آمار کارشناس --}}
                <div class="row g-4 mb-4">
                    <div class="col-md-4 col-sm-6">
                        <div class="stat-card" style="border-right: 4px solid #5C6BC0;">
                            <div class="stat-icon-wrap" style="background:linear-gradient(135deg,#7986CB,#5C6BC0);">
                                <i class="fa fa-clipboard-list" style="color:white;font-size:24px;"></i>
                            </div>
                            <p class="stat-number" style="color:#283593;">{{ $expertPendingCount }}</p>
                            <p class="stat-label">تقاضا در انتظار بررسی</p>
                        </div>
                    </div>
                </div>

                {{-- دکمه‌های دسترسی سریع --}}
                <div class="row g-3">
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                        <a href="{{ route('request-expert-review.expert.index') }}"
                           class="quick-link-btn"
                           style="background:linear-gradient(135deg,#7986CB,#5C6BC0);color:white;">
                            <div class="ql-icon"><i class="fa fa-clipboard-list"></i></div>
                            <div class="ql-text">
                                تقاضاهای من
                                @if ($expertPendingCount > 0)
                                    <span class="badge ms-2"
                                          style="background:rgba(255,255,255,0.3);color:white;font-size:13px;padding:4px 10px;border-radius:20px;">
                                        {{ $expertPendingCount }}
                                    </span>
                                @endif
                                <small>تقاضاهایی که راهبر به شما اختصاص داده است</small>
                            </div>
                            <i class="fa fa-angle-left ql-arrow"></i>
                        </a>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                        @if(class_exists(\ExpertInitialVisit\Models\ExpertInitialVisit::class))
                        <a href="{{ route('expert-initial-visit.index') }}"
                           class="quick-link-btn"
                           style="background:linear-gradient(135deg,#5C6BC0,#3949AB);color:white;">
                            <div class="ql-icon"><i class="fa fa-clipboard-check"></i></div>
                            <div class="ql-text">
                                گزارش‌های بازدید اولیه
                                <small>ثبت و مشاهده فرم‌های بازدید اولیه از محل پروژه</small>
                            </div>
                            <i class="fa fa-angle-left ql-arrow"></i>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        @if (auth()->user()->role_id == 6)
            <div class="old-small-box-wrap">
                <h4 class="role-section-title">بخش‌های تأمین‌کننده پنل</h4>
                <div class="row g-3">
                    <div class="col-sm-3">
                        <div class="small-box ql-orange" style="color:white; padding: 1.25rem; border-radius: 12px;">
                            <div class="inner">
                                <h6 style="font-weight:700; margin:0;">{{ trans('پنل') }}</h6>
                                <p style="opacity:0.9; margin:0.3rem 0 0 0; font-size:0.85rem;">{{ trans('افزودن پنل جدید') }}</p>
                            </div>
                            <div class="icon" style="position:absolute; top:1rem; left:1rem; font-size:48px; opacity:0.3;">
                                <i class="ion ion-bag"></i>
                            </div>
                            <a href="{{ route('solar-plant-requests.panel.create') }}"
                                class="small-box-footer" style="display:block; color:white; padding:0.6rem; background:rgba(0,0,0,0.15); text-align:center; margin-top:1rem; border-radius:8px; text-decoration:none;">
                                {{ trans('مشاهده') }} <i class="fa fa-arrow-circle-left"></i>
                            </a>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="small-box ql-blue" style="color:white; padding: 1.25rem; border-radius: 12px;">
                            <div class="inner">
                                <h6 style="font-weight:700; margin:0;">{{ trans('پنل') }}</h6>
                                <p style="opacity:0.9; margin:0.3rem 0 0 0; font-size:0.85rem;">{{ trans('لیست پنل‌ها') }}</p>
                            </div>
                            <div class="icon" style="position:absolute; top:1rem; left:1rem; font-size:48px; opacity:0.3;">
                                <i class="ion ion-bag"></i>
                            </div>
                            <a href="{{ route('solar-plant-requests.panel.my-panels') }}" class="small-box-footer" style="display:block; color:white; padding:0.6rem; background:rgba(0,0,0,0.15); text-align:center; margin-top:1rem; border-radius:8px; text-decoration:none;">
                                {{ trans('مشاهده') }} <i class="fa fa-arrow-circle-left"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if (auth()->user()->role_id == 7)
            <div class="old-small-box-wrap">
                <h4 class="role-section-title">بخش‌های تأمین‌کننده اینورتر</h4>
                <div class="row g-3">
                    <div class="col-sm-3">
                        <div class="small-box ql-teal" style="color:white; padding: 1.25rem; border-radius: 12px;">
                            <div class="inner">
                                <h6 style="font-weight:700; margin:0;">{{ trans('اینورتر') }}</h6>
                                <p style="opacity:0.9; margin:0.3rem 0 0 0; font-size:0.85rem;">{{ trans('افزودن اینورتر جدید') }}</p>
                            </div>
                            <div class="icon" style="position:absolute; top:1rem; left:1rem; font-size:48px; opacity:0.3;">
                                <i class="ion ion-bag"></i>
                            </div>
                            <a href="{{ route('solar-plant-requests.inverter.create') }}"
                                class="small-box-footer" style="display:block; color:white; padding:0.6rem; background:rgba(0,0,0,0.15); text-align:center; margin-top:1rem; border-radius:8px; text-decoration:none;">
                                {{ trans('مشاهده') }} <i class="fa fa-arrow-circle-left"></i>
                            </a>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="small-box ql-blue" style="color:white; padding: 1.25rem; border-radius: 12px;">
                            <div class="inner">
                                <h6 style="font-weight:700; margin:0;">{{ trans('اینورتر') }}</h6>
                                <p style="opacity:0.9; margin:0.3rem 0 0 0; font-size:0.85rem;">{{ trans('لیست اینورترها') }}</p>
                            </div>
                            <div class="icon" style="position:absolute; top:1rem; left:1rem; font-size:48px; opacity:0.3;">
                                <i class="ion ion-bag"></i>
                            </div>
                            <a href="{{ route('solar-plant-requests.inverter.my-inverters') }}" class="small-box-footer" style="display:block; color:white; padding:0.6rem; background:rgba(0,0,0,0.15); text-align:center; margin-top:1rem; border-radius:8px; text-decoration:none;">
                                {{ trans('مشاهده') }} <i class="fa fa-arrow-circle-left"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if (auth()->user()->role_id == 8)
            <div class="old-small-box-wrap">
                <h4 class="role-section-title">بخش‌های تأمین‌کننده باتری</h4>
                <div class="row g-3">
                    <div class="col-sm-3">
                        <div class="small-box ql-green" style="color:white; padding: 1.25rem; border-radius: 12px;">
                            <div class="inner">
                                <h6 style="font-weight:700; margin:0;">{{ trans('باتری') }}</h6>
                                <p style="opacity:0.9; margin:0.3rem 0 0 0; font-size:0.85rem;">{{ trans('افزودن باتری جدید') }}</p>
                            </div>
                            <div class="icon" style="position:absolute; top:1rem; left:1rem; font-size:48px; opacity:0.3;">
                                <i class="ion ion-bag"></i>
                            </div>
                            <a href="{{ route('solar-plant-requests.battery.create') }}"
                                class="small-box-footer" style="display:block; color:white; padding:0.6rem; background:rgba(0,0,0,0.15); text-align:center; margin-top:1rem; border-radius:8px; text-decoration:none;">
                                {{ trans('مشاهده') }} <i class="fa fa-arrow-circle-left"></i>
                            </a>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="small-box ql-blue" style="color:white; padding: 1.25rem; border-radius: 12px;">
                            <div class="inner">
                                <h6 style="font-weight:700; margin:0;">{{ trans('باتری') }}</h6>
                                <p style="opacity:0.9; margin:0.3rem 0 0 0; font-size:0.85rem;">{{ trans('لیست باتریها') }}</p>
                            </div>
                            <div class="icon" style="position:absolute; top:1rem; left:1rem; font-size:48px; opacity:0.3;">
                                <i class="ion ion-bag"></i>
                            </div>
                            <a href="{{ route('solar-plant-requests.battery.my-batteries') }}" class="small-box-footer" style="display:block; color:white; padding:0.6rem; background:rgba(0,0,0,0.15); text-align:center; margin-top:1rem; border-radius:8px; text-decoration:none;">
                                {{ trans('مشاهده') }} <i class="fa fa-arrow-circle-left"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <script>
        function updateClock() {
            var now = new Date();
            var hours = String(now.getHours()).padStart(2, '0');
            var minutes = String(now.getMinutes()).padStart(2, '0');
            document.getElementById('currentTime').textContent = hours + ':' + minutes;
        }
        setInterval(updateClock, 60000);
    </script>
@endsection
