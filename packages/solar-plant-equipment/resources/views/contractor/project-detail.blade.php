@extends('behin-layouts.app')

@section('title', 'جزئیات پروژه #' . $project->id)

@section('style')
<style>
    body { direction: rtl; text-align: right; }
    .sec-card { border: none; border-radius: 14px; box-shadow: 0 4px 18px rgba(0,0,0,0.07); overflow: hidden; margin-bottom: 1.5rem; }
    .sec-header { padding: 0.9rem 1.25rem; display: flex; align-items: center; justify-content: space-between; }
    .info-table tr th { width: 45%; padding: 0.65rem 1rem; color: #757575; font-weight: 600; background: #FAFAFA; border-bottom: 1px solid #F0F0F0; font-size: .87rem; }
    .info-table tr td { padding: 0.65rem 1rem; color: #374151; font-weight: 500; border-bottom: 1px solid #F0F0F0; font-size: .87rem; }
    .info-table tr:last-child th, .info-table tr:last-child td { border-bottom: none; }
    .data-table thead th { background:#FAFAFA;color:#616161;font-weight:700;font-size:.82rem;padding:.65rem .85rem;border-bottom:2px solid #E0E0E0; }
    .data-table tbody td { padding:.6rem .85rem;vertical-align:middle;font-size:.84rem;border-bottom:1px solid #F0F0F0; }
    .data-table tbody tr:last-child td { border-bottom: none; }
    .empty-state { padding: 2rem 1rem; text-align: center; color: #9E9E9E; font-size: .9rem; }
    .empty-state i { display: block; font-size: 2.5rem; margin-bottom: .75rem; opacity: .5; }
    .field-readonly { background: #F9FAFB; padding: .55rem .9rem; border-radius: 8px; border: 1px solid #E5E7EB; color: #374151; font-size: .87rem; }
</style>
@endsection

@section('content')
<div class="container-fluid" style="max-width:1000px;">

    {{-- Header --}}
    <div class="mb-4 p-4 text-white" style="background:linear-gradient(135deg,#FF9800,#E65100);border-radius:14px;">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h3 class="mb-1 fw-bold"><i class="fa fa-hard-hat ms-2"></i>پروژه #{{ $project->id }}</h3>
                @if ($project->request)
                <p class="mb-0 opacity-90">
                    درخواست {{ $project->request->unique_code }} —
                    @if ($project->request->applicant_type->value === 'company')
                        {{ $project->request->company_name }}
                    @else
                        {{ $project->request->first_name }} {{ $project->request->last_name }}
                    @endif
                </p>
                @endif
            </div>
            <div class="d-flex gap-2 flex-wrap">
                @if ($project->status === \SolarPlantEquipment\Models\SolarProject::STATUS_IN_PROGRESS)
                <form method="POST"
                      action="{{ route('solar-plant-equipment.contractor.projects.ready-for-inspection', $project) }}"
                      onsubmit="return confirm('آیا پروژه آماده بازرسی است؟')">
                    @csrf
                    <button type="submit" class="btn text-white fw-bold"
                            style="background:rgba(255,255,255,.25);border-radius:10px;backdrop-filter:blur(6px);">
                        <i class="fa fa-check-circle ms-1"></i> اعلام آماده‌ی بازرسی
                    </button>
                </form>
                @endif
                <a href="{{ route('solar-plant-equipment.contractor.projects.index') }}"
                   class="btn btn-light fw-semibold" style="border-radius:10px;color:#E65100;">
                    <i class="fa fa-arrow-right ms-1"></i> بازگشت به لیست
                </a>
                <a href="{{ route('admin.dashboard') }}"
                   class="btn btn-light fw-semibold" style="border-radius:10px;color:#555;">
                    <i class="fa fa-home ms-1"></i> داشبرد
                </a>
            </div>
        </div>
    </div>

    @if (session('success'))
    <div class="alert mb-4" style="border-radius:12px;border:none;background:linear-gradient(135deg,#C8E6C9,#A5D6A7);color:#1B5E20;">
        <i class="fa fa-check-circle ms-2"></i>{{ session('success') }}
    </div>
    @endif

    @if ($errors->any())
    <div class="alert alert-danger mb-4" style="border-radius:12px;">
        <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
        </ul>
    </div>
    @endif

    <div class="row g-4">

        {{-- ── ستون چپ: اطلاعات read-only ── --}}
        <div class="col-md-5">
            <div class="sec-card">
                <div class="sec-header" style="background:linear-gradient(90deg,#FFCC80,#FFB74D);">
                    <h6 class="mb-0 fw-bold text-white"><i class="fa fa-info-circle ms-1"></i>اطلاعات پروژه (توسط راهبر تعیین شده)</h6>
                </div>
                <div class="p-0">
                    <table class="table info-table mb-0">
                        <tr>
                            <th>وضعیت</th>
                            <td>{!! $project->status_label !!}</td>
                        </tr>
                        <tr>
                            <th>پیمانکار</th>
                            <td>{{ $project->contractor?->company_name ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>بازرس</th>
                            <td>{{ $project->inspector?->name ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>شماره قرارداد ساتبا</th>
                            <td>{{ $project->satba_contract_number ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>گواهی سلامت</th>
                            <td>{{ $project->health_card_no ?? '—' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        {{-- ── ستون راست: فرم ویرایش ── --}}
        <div class="col-md-7">
            <div class="sec-card">
                <div class="sec-header" style="background:linear-gradient(90deg,#64B5F6,#1976D2);">
                    <h6 class="mb-0 fw-bold text-white"><i class="fa fa-edit ms-1"></i>اطلاعات قابل ویرایش توسط پیمانکار</h6>
                </div>
                <div class="card-body p-4">
                    <form method="POST"
                          action="{{ route('solar-plant-equipment.contractor.projects.update', $project) }}">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold mb-1" style="color:#37474F;">تاریخ شروع نصب</label>
                                <input type="text" name="installation_start_date"
                                       class="form-control persian-date @error('installation_start_date') is-invalid @enderror"
                                       placeholder="مثال: ۱۴۰۳/۰۱/۰۱"
                                       value="{{ old('installation_start_date', $project->installation_start_date ? \Morilog\Jalali\Jalalian::fromDateTime($project->installation_start_date)->format('Y/m/d') : '') }}"
                                       style="border-radius:8px;">
                                @error('installation_start_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold mb-1" style="color:#37474F;">تاریخ پایان نصب</label>
                                <input type="text" name="installation_end_date"
                                       class="form-control persian-date @error('installation_end_date') is-invalid @enderror"
                                       placeholder="مثال: ۱۴۰۳/۰۶/۳۱"
                                       value="{{ old('installation_end_date', $project->installation_end_date ? \Morilog\Jalali\Jalalian::fromDateTime($project->installation_end_date)->format('Y/m/d') : '') }}"
                                       style="border-radius:8px;">
                                @error('installation_end_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold mb-1" style="color:#37474F;">تاریخ بهره‌برداری</label>
                                <input type="text" name="commissioning_date"
                                       class="form-control persian-date @error('commissioning_date') is-invalid @enderror"
                                       placeholder="مثال: ۱۴۰۳/۰۷/۰۱"
                                       value="{{ old('commissioning_date', $project->commissioning_date ? \Morilog\Jalali\Jalalian::fromDateTime($project->commissioning_date)->format('Y/m/d') : '') }}"
                                       style="border-radius:8px;">
                                @error('commissioning_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-semibold mb-1" style="color:#37474F;">عرض جغرافیایی</label>
                                <input type="number" step="any" name="latitude"
                                       class="form-control @error('latitude') is-invalid @enderror"
                                       placeholder="35.68..."
                                       value="{{ old('latitude', $project->latitude) }}"
                                       style="border-radius:8px;">
                                @error('latitude') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-semibold mb-1" style="color:#37474F;">طول جغرافیایی</label>
                                <input type="number" step="any" name="longitude"
                                       class="form-control @error('longitude') is-invalid @enderror"
                                       placeholder="51.38..."
                                       value="{{ old('longitude', $project->longitude) }}"
                                       style="border-radius:8px;">
                                @error('longitude') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold mb-1" style="color:#37474F;">توضیحات</label>
                                <textarea name="description" rows="3"
                                          class="form-control @error('description') is-invalid @enderror"
                                          style="border-radius:8px;resize:vertical;">{{ old('description', $project->description) }}</textarea>
                                @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="mt-4 d-flex justify-content-end">
                            <button type="submit" class="btn text-white fw-bold"
                                    style="background:linear-gradient(135deg,#64B5F6,#1976D2);border-radius:10px;padding:10px 32px;">
                                <i class="fa fa-save ms-1"></i> ذخیره تغییرات
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

    {{-- ── پنل‌ها ── --}}
    <div class="sec-card">
        <div class="sec-header" style="background:linear-gradient(90deg,#42A5F5,#2196F3);">
            <h6 class="mb-0 fw-bold text-white">
                <i class="fa fa-sun-o ms-1"></i>
                پنل‌های نصب‌شده
                <span class="badge ms-1" style="background:rgba(255,255,255,.25);border-radius:20px;">{{ $project->installedPanels->count() }}</span>
            </h6>
            <a href="{{ route('solar-plant-equipment.contractor.projects.panels.create', $project) }}"
               class="btn btn-sm fw-semibold" style="background:#fff;color:#1976D2;border-radius:8px;">
                <i class="fa fa-plus ms-1"></i> افزودن پنل
            </a>
        </div>
        <div class="p-0">
            @if ($project->installedPanels->isEmpty())
            <div class="empty-state"><i class="fa fa-solar-panel"></i>پنلی ثبت نشده است.</div>
            @else
            <div class="table-responsive">
                <table class="table data-table mb-0">
                    <thead>
                        <tr>
                            <th>#</th><th>مدل</th><th>سریال</th><th>بخش</th><th>استرینگ</th><th>شماره</th><th>وضعیت</th><th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($project->installedPanels as $p)
                        @php $sl = \SolarPlantEquipment\Models\InstalledPanel::STATUSES; @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $p->catalog?->brand ?? '#'.$p->panel_model_id }}</td>
                            <td><code dir="ltr">{{ $p->serial_number }}</code></td>
                            <td>{{ $p->section_number }}</td>
                            <td>{{ $p->string_number }}</td>
                            <td>{{ $p->panel_number }}</td>
                            <td><span class="badge badge-secondary">{{ $sl[$p->status] ?? $p->status }}</span></td>
                            <td>
                                <form method="POST"
                                      action="{{ route('solar-plant-equipment.contractor.projects.panels.destroy', [$project, $p]) }}"
                                      onsubmit="return confirm('حذف شود؟')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-xs" style="background:#FFEBEE;color:#C62828;border-radius:6px;padding:3px 9px;">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>

    {{-- ── اینورترها ── --}}
    <div class="sec-card">
        <div class="sec-header" style="background:linear-gradient(90deg,#FFD54F,#FFC107);">
            <h6 class="mb-0 fw-bold text-white">
                <i class="fa fa-bolt ms-1"></i>
                اینورترهای نصب‌شده
                <span class="badge ms-1" style="background:rgba(255,255,255,.25);border-radius:20px;">{{ $project->installedInverters->count() }}</span>
            </h6>
            <a href="{{ route('solar-plant-equipment.contractor.projects.inverters.create', $project) }}"
               class="btn btn-sm fw-semibold" style="background:#fff;color:#FF9800;border-radius:8px;">
                <i class="fa fa-plus ms-1"></i> افزودن اینورتر
            </a>
        </div>
        <div class="p-0">
            @if ($project->installedInverters->isEmpty())
            <div class="empty-state"><i class="fa fa-bolt"></i>اینورتری ثبت نشده است.</div>
            @else
            <div class="table-responsive">
                <table class="table data-table mb-0">
                    <thead>
                        <tr><th>#</th><th>مدل</th><th>سریال</th><th>تگ تجهیز</th><th>محل نصب</th><th>وضعیت</th><th></th></tr>
                    </thead>
                    <tbody>
                        @foreach ($project->installedInverters as $inv)
                        @php $sl = \SolarPlantEquipment\Models\InstalledInverter::STATUSES;
                             $ll = \SolarPlantEquipment\Models\InstalledInverter::INSTALLATION_LOCATIONS; @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $inv->catalog?->brand ?? '#'.$inv->inverter_model_id }}</td>
                            <td><code dir="ltr">{{ $inv->serial_number }}</code></td>
                            <td>{{ $inv->equipment_tag }}</td>
                            <td>{{ $ll[$inv->installation_location] ?? $inv->installation_location }}</td>
                            <td><span class="badge badge-secondary">{{ $sl[$inv->status] ?? $inv->status }}</span></td>
                            <td>
                                <form method="POST"
                                      action="{{ route('solar-plant-equipment.contractor.projects.inverters.destroy', [$project, $inv]) }}"
                                      onsubmit="return confirm('حذف شود؟')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-xs" style="background:#FFEBEE;color:#C62828;border-radius:6px;padding:3px 9px;">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>

    {{-- ── باتری‌ها ── --}}
    <div class="sec-card">
        <div class="sec-header" style="background:linear-gradient(90deg,#81C784,#66BB6A);">
            <h6 class="mb-0 fw-bold text-white">
                <i class="fa fa-battery-full ms-1"></i>
                باتری‌های نصب‌شده
                <span class="badge ms-1" style="background:rgba(255,255,255,.25);border-radius:20px;">{{ $project->installedBatteries->count() }}</span>
            </h6>
            <a href="{{ route('solar-plant-equipment.contractor.projects.batteries.create', $project) }}"
               class="btn btn-sm fw-semibold" style="background:#fff;color:#2E7D32;border-radius:8px;">
                <i class="fa fa-plus ms-1"></i> افزودن باتری
            </a>
        </div>
        <div class="p-0">
            @if ($project->installedBatteries->isEmpty())
            <div class="empty-state"><i class="fa fa-battery-full"></i>باتری‌ای ثبت نشده است.</div>
            @else
            <div class="table-responsive">
                <table class="table data-table mb-0">
                    <thead>
                        <tr><th>#</th><th>مدل</th><th>سریال</th><th>تگ تجهیز</th><th>محل نصب</th><th>وضعیت</th><th></th></tr>
                    </thead>
                    <tbody>
                        @foreach ($project->installedBatteries as $bat)
                        @php $sl = \SolarPlantEquipment\Models\InstalledBattery::STATUSES;
                             $ll = \SolarPlantEquipment\Models\InstalledBattery::INSTALLATION_LOCATIONS; @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $bat->catalog?->brand ?? '#'.$bat->battery_model_id }}</td>
                            <td><code dir="ltr">{{ $bat->serial_number }}</code></td>
                            <td>{{ $bat->equipment_tag }}</td>
                            <td>{{ $ll[$bat->installation_location] ?? $bat->installation_location }}</td>
                            <td><span class="badge badge-secondary">{{ $sl[$bat->status] ?? $bat->status }}</span></td>
                            <td>
                                <form method="POST"
                                      action="{{ route('solar-plant-equipment.contractor.projects.batteries.destroy', [$project, $bat]) }}"
                                      onsubmit="return confirm('حذف شود؟')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-xs" style="background:#FFEBEE;color:#C62828;border-radius:6px;padding:3px 9px;">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
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
