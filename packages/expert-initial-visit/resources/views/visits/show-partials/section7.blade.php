@php $v = $expertInitialVisit; @endphp
<div class="card mb-4" style="border-radius:12px;border:none;box-shadow:0 4px 20px rgba(0,0,0,0.08);">
    <div class="card-header" style="background:linear-gradient(135deg,#E8EAF6,#C5CAE9);border-radius:12px 12px 0 0;border:none;">
        <h5 class="mb-0 fw-bold" style="color:#283593;"><i class="fa fa-plug ms-2"></i>بخش ۷ — بار اضطراری</h5>
    </div>
    <div class="card-body p-4">
        @if(!$v->has_emergency_load)
            <p class="text-muted mb-0">متقاضی نیاز به بار اضطراری ندارد.</p>
        @else
            <div class="row g-3 mb-4">
                <div class="col-md-3"><label class="text-muted small d-block">مجموع بار ضروری</label><strong>{{ $v->total_emergency_load_kw ? $v->total_emergency_load_kw.' kW' : '-' }}</strong></div>
                <div class="col-md-3"><label class="text-muted small d-block">مدت تأمین برق</label><strong>{{ $v->emergency_supply_hours ? $v->emergency_supply_hours.' ساعت' : '-' }}</strong></div>
                <div class="col-md-3"><label class="text-muted small d-block">نیاز به باتری</label>
                    @php $bn=['yes'=>'بله','no'=>'خیر','optional'=>'اختیاری']; @endphp
                    <strong>{{ $bn[$v->battery_need] ?? '-' }}</strong>
                </div>
            </div>

            @if($v->equipmentItems->isNotEmpty())
            <div class="table-responsive">
                <table class="table table-bordered table-sm" style="font-size:14px;">
                    <thead style="background:#E8EAF6;">
                        <tr>
                            <th style="color:#283593;">نام تجهیز</th>
                            <th style="color:#283593;width:70px;">تعداد</th>
                            <th style="color:#283593;width:100px;">توان (W)</th>
                            <th style="color:#283593;width:100px;">توان کل (W)</th>
                            <th style="color:#283593;width:90px;">مدت (ساعت)</th>
                            <th style="color:#283593;width:70px;">ضروری</th>
                            <th style="color:#283593;">توضیحات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($v->equipmentItems as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-center">{{ $item->power_watts ?? '-' }}</td>
                            <td class="text-center">{{ $item->total_power_watts ?? '-' }}</td>
                            <td class="text-center">{{ $item->usage_hours ?? '-' }}</td>
                            <td class="text-center">
                                @if($item->is_critical)
                                    <span class="badge" style="background:#C8E6C9;color:#1B5E20;">بله</span>
                                @else
                                    <span class="badge" style="background:#E0E0E0;color:#616161;">خیر</span>
                                @endif
                            </td>
                            <td>{{ $item->notes ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif

            @if($v->emergency_load_notes)
            <p class="text-muted small mt-2 mb-0">{{ $v->emergency_load_notes }}</p>
            @endif
        @endif
    </div>
</div>
