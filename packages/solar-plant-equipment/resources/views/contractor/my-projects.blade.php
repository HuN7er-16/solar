@extends('behin-layouts.app')

@section('title', 'پروژه‌های من')

@section('style')
<style>
    body { direction: rtl; text-align: right; }
    .proj-card {
        border: none;
        border-radius: 14px;
        box-shadow: 0 4px 18px rgba(0,0,0,0.07);
        overflow: hidden;
        margin-bottom: 1.25rem;
        transition: box-shadow 0.2s;
    }
    .proj-card:hover { box-shadow: 0 6px 24px rgba(0,0,0,0.12); }
    .status-badge {
        border-radius: 20px;
        padding: 5px 14px;
        font-weight: 700;
        font-size: 0.8rem;
    }
</style>
@endsection

@section('content')
<div class="container-fluid" style="max-width:960px;">

    {{-- Header --}}
    <div class="mb-4 p-4 text-white" style="background:linear-gradient(135deg,#FF9800,#E65100);border-radius:14px;box-shadow:0 4px 20px rgba(230,81,0,.25);">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h3 class="mb-1 fw-bold"><i class="fa fa-hard-hat ms-2"></i>پروژه‌های من</h3>
                <p class="mb-0 opacity-90">{{ $contractor->company_name }}</p>
            </div>
            <a href="{{ route('admin.dashboard') }}"
               class="btn btn-light fw-semibold" style="border-radius:10px;color:#E65100;">
                <i class="fa fa-home ms-1"></i> داشبرد
            </a>
        </div>
    </div>

    @if (session('success'))
    <div class="alert" style="border-radius:12px;border:none;background:linear-gradient(135deg,#C8E6C9,#A5D6A7);color:#1B5E20;">
        <i class="fa fa-check-circle ms-2"></i>{{ session('success') }}
    </div>
    @endif

    @if ($projects->isEmpty())
    <div class="text-center py-5 text-muted">
        <i class="fa fa-folder-open" style="font-size:3rem;opacity:.4;display:block;margin-bottom:1rem;"></i>
        هنوز پروژه‌ای به شما تخصیص داده نشده است.
    </div>
    @else

    @php
    $statusColors = [
        'in_progress'          => 'background:#E3F2FD;color:#1565C0',
        'ready_for_inspection' => 'background:#FFF3E0;color:#E65100',
        'approved'             => 'background:#E8F5E9;color:#2E7D32',
        'rejected'             => 'background:#FFEBEE;color:#C62828',
        'active'               => 'background:#E0F2F1;color:#00695C',
        'inactive'             => 'background:#F5F5F5;color:#757575',
    ];
    $statusLabels = \SolarPlantEquipment\Models\SolarProject::getStatuses();
    @endphp

    @foreach ($projects as $project)
    @php $statusStyle = $statusColors[$project->status] ?? 'background:#F5F5F5;color:#757575'; @endphp
    <div class="proj-card">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                <div>
                    <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                        <span class="fw-bold" style="font-size:1.05rem;">پروژه #{{ $project->id }}</span>
                        <span class="status-badge" style="{{ $statusStyle }}">
                            {{ $statusLabels[$project->status] ?? $project->status }}
                        </span>
                    </div>
                    @if ($project->request)
                    <div class="text-muted" style="font-size:.88rem;">
                        <i class="fa fa-file-alt ms-1"></i>
                        درخواست: <span class="badge" style="background:#FFF3E0;color:#E65100;border:1px solid #FFE0B2;">{{ $project->request->unique_code }}</span>
                        —
                        @if ($project->request->applicant_type->value === 'company')
                            {{ $project->request->company_name }}
                        @else
                            {{ $project->request->first_name }} {{ $project->request->last_name }}
                        @endif
                        — {{ $project->request->province }} / {{ $project->request->city }}
                    </div>
                    @endif
                    <div class="mt-2 d-flex gap-4 flex-wrap" style="font-size:.85rem;color:#616161;">
                        @if ($project->installation_start_date)
                        <span><i class="fa fa-calendar ms-1 text-orange"></i>
                            شروع: {{ \Morilog\Jalali\Jalalian::fromDateTime($project->installation_start_date)->format('Y/m/d') }}
                        </span>
                        @endif
                        @if ($project->installation_end_date)
                        <span><i class="fa fa-calendar-check ms-1 text-green"></i>
                            پایان: {{ \Morilog\Jalali\Jalalian::fromDateTime($project->installation_end_date)->format('Y/m/d') }}
                        </span>
                        @endif
                    </div>
                </div>
                <a href="{{ route('solar-plant-equipment.contractor.projects.show', $project) }}"
                   class="btn text-white"
                   style="background:linear-gradient(135deg,#FF9800,#F57C00);border-radius:10px;font-weight:600;white-space:nowrap;">
                    <i class="fa fa-arrow-left ms-1"></i> مشاهده و ویرایش
                </a>
            </div>

            {{-- خلاصه تجهیزات --}}
            <div class="mt-3 pt-3 border-top d-flex gap-4" style="font-size:.85rem;color:#424242;">
                <span><i class="fa fa-sun-o ms-1" style="color:#2196F3;"></i>
                    {{ $project->installedPanels->count() ?? 0 }} پنل
                </span>
                <span><i class="fa fa-bolt ms-1" style="color:#FFC107;"></i>
                    {{ $project->installedInverters->count() ?? 0 }} اینورتر
                </span>
                <span><i class="fa fa-battery-full ms-1" style="color:#4CAF50;"></i>
                    {{ $project->installedBatteries->count() ?? 0 }} باتری
                </span>
            </div>
        </div>
    </div>
    @endforeach

    @endif
</div>
@endsection
