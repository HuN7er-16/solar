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
             style="background: linear-gradient(135deg, #1976D2 0%, #1565C0 100%); border-radius: 12px; box-shadow: 0 4px 20px rgba(21,101,192,0.25);">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h3 class="mb-1 fw-bold"><i class="fa fa-building ms-2"></i>پیمانکاران CRM</h3>
                    <p class="mb-0 opacity-90">لیست پیمانکاران همگام‌شده از سیستم CRM</p>
                </div>
                <form method="POST" action="{{ route('crm-contractors.sync') }}">
                    @csrf
                    <button type="submit"
                            class="btn btn-light btn-lg"
                            style="border-radius: 12px; color: #1565C0; font-weight: 600; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                        <i class="fa fa-sync-alt ms-1"></i> همگام‌سازی از CRM
                    </button>
                </form>
            </div>
        </div>

        {{-- Stats --}}
        <div class="row g-4 mb-4">
            <div class="col-md-4 col-sm-6">
                <div class="p-4 text-white"
                     style="background: linear-gradient(135deg, #FFB74D 0%, #FF9800 100%); border-radius: 12px; box-shadow: 0 4px 15px rgba(255,152,0,0.25);">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="mb-2 opacity-90">کل پیمانکاران</h6>
                            <h2 class="mb-0 fw-bold">{{ $contractors->total() }}</h2>
                        </div>
                        <div style="background: rgba(255,255,255,0.2); border-radius: 50%; padding: 12px;">
                            <i class="fa fa-users" style="font-size: 24px;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="p-4 text-white"
                     style="background: linear-gradient(135deg, #81C784 0%, #4CAF50 100%); border-radius: 12px; box-shadow: 0 4px 15px rgba(76,175,80,0.25);">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="mb-2 opacity-90">همگام‌شده این صفحه</h6>
                            <h2 class="mb-0 fw-bold">{{ $contractors->count() }}</h2>
                        </div>
                        <div style="background: rgba(255,255,255,0.2); border-radius: 50%; padding: 12px;">
                            <i class="fa fa-sync" style="font-size: 24px;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="p-4 text-white"
                     style="background: linear-gradient(135deg, #7986CB 0%, #5C6BC0 100%); border-radius: 12px; box-shadow: 0 4px 15px rgba(92,107,192,0.25);">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="mb-2 opacity-90">استان‌های تحت پوشش</h6>
                            <h2 class="mb-0 fw-bold">
                                {{ $contractors->getCollection()->pluck('province')->filter()->unique()->count() }}
                            </h2>
                        </div>
                        <div style="background: rgba(255,255,255,0.2); border-radius: 50%; padding: 12px;">
                            <i class="fa fa-map-marked-alt" style="font-size: 24px;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Table --}}
        <div class="card" style="border-radius: 12px; border: none; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
            <div class="card-body p-0">
                @if($contractors->isEmpty())
                    <div class="text-center py-5 px-4">
                        <div style="width: 100px; height: 100px; margin: 0 auto 20px;
                                    background: linear-gradient(135deg, #E3F2FD 0%, #BBDEFB 100%);
                                    border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fa fa-building" style="font-size: 42px; color: #1565C0;"></i>
                        </div>
                        <h5 class="mb-2 fw-bold" style="color: #0D47A1;">هنوز هیچ پیمانکاری همگام‌سازی نشده است</h5>
                        <p class="text-muted mb-4">با کلیک روی دکمه زیر، پیمانکاران را از CRM همگام‌سازی کنید</p>
                        <form method="POST" action="{{ route('crm-contractors.sync') }}" style="display: inline;">
                            @csrf
                            <button type="submit"
                                    class="btn text-white"
                                    style="background: linear-gradient(135deg, #1976D2 0%, #1565C0 100%); border-radius: 12px; font-weight: 600;">
                                <i class="fa fa-sync-alt ms-1"></i> همگام‌سازی از CRM
                            </button>
                        </form>
                    </div>
                @else
                    <div class="table-responsive">
                        <table id="contractorsTable" class="table table-hover mb-0"
                               style="width:100%; border-collapse: separate; border-spacing: 0;">
                            <thead>
                                <tr style="background: #E3F2FD;">
                                    <th style="padding: 14px 16px; font-weight: 700; color: #0D47A1; border: none;">#</th>
                                    <th style="padding: 14px 16px; font-weight: 700; color: #0D47A1; border: none;">نام پیمانکار</th>
                                    <th style="padding: 14px 16px; font-weight: 700; color: #0D47A1; border: none;">کد مرکز CRM</th>
                                    <th style="padding: 14px 16px; font-weight: 700; color: #0D47A1; border: none;">موبایل</th>
                                    <th style="padding: 14px 16px; font-weight: 700; color: #0D47A1; border: none;">استان</th>
                                    <th style="padding: 14px 16px; font-weight: 700; color: #0D47A1; border: none;">آخرین همگام‌سازی</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($contractors as $contractor)
                                    <tr style="border-bottom: 1px solid #F5F5F5; transition: all 0.2s;">
                                        <td style="padding: 14px 16px; vertical-align: middle;">
                                            {{ $loop->iteration + ($contractors->currentPage() - 1) * $contractors->perPage() }}
                                        </td>
                                        <td style="padding: 14px 16px; vertical-align: middle;">
                                            <div class="d-flex align-items-center">
                                                <div class="me-3 d-flex align-items-center justify-content-center"
                                                     style="width: 40px; height: 40px; background: linear-gradient(135deg, #E3F2FD 0%, #BBDEFB 100%); border-radius: 10px; flex-shrink: 0;">
                                                    <i class="fa fa-building" style="color: #1565C0;"></i>
                                                </div>
                                                <div>
                                                    <span class="fw-semibold d-block" style="color: #263238;">
                                                        {{ $contractor->user->name ?? '—' }}
                                                    </span>
                                                    @if($contractor->center_name)
                                                        <small class="text-muted" style="font-size: 12px;">{{ $contractor->center_name }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td style="padding: 14px 16px; vertical-align: middle;">
                                            <span class="badge"
                                                  style="background: #E3F2FD; color: #0D47A1; padding: 6px 12px; border-radius: 8px; font-size: 12px; font-family: monospace; letter-spacing: 0.5px;">
                                                {{ $contractor->crm_service_center_id }}
                                            </span>
                                        </td>
                                        <td style="padding: 14px 16px; vertical-align: middle; font-family: 'Vazir', monospace;">
                                            <div class="fw-semibold" style="color: #1565C0;">
                                                <i class="fa fa-mobile-alt ms-1" style="color: #1976D2;"></i>
                                                {{ $contractor->mobile }}
                                            </div>
                                        </td>
                                        <td style="padding: 14px 16px; vertical-align: middle;">
                                            @if($contractor->province)
                                                <div class="fw-semibold" style="color: #37474F;">
                                                    <i class="fa fa-map-marker-alt ms-1" style="color: #FF7043;"></i>
                                                    {{ $contractor->province }}
                                                </div>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td style="padding: 14px 16px; vertical-align: middle; font-family: 'Vazir', monospace; color: #546E7A;">
                                            @if($contractor->synced_at)
                                                <div>
                                                    <i class="fa fa-clock ms-1" style="color: #78909C;"></i>
                                                    {{ jdate($contractor->synced_at)->format('Y/m/d H:i') }}
                                                </div>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="p-4" style="background: #FAFAFA; border-radius: 0 0 12px 12px;">
                        {{ $contractors->links() }}
                    </div>
                @endif
            </div>
        </div>

    </div>
@endsection

@section('script')
<style>
    #contractorsTable tbody tr:hover { background-color: #E3F2FD !important; }
    .pagination { justify-content: center !important; }
    .page-link { border-radius: 8px !important; margin: 0 3px; border: none; background: #E3F2FD; color: #1565C0; padding: 8px 14px; font-weight: 600; }
    .page-item.active .page-link { background: linear-gradient(135deg, #1976D2 0%, #1565C0 100%); color: white; box-shadow: 0 2px 8px rgba(21,101,192,0.3); }
</style>
@endsection
