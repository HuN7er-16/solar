<?php

namespace ExpertInitialVisit\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExpertVisitPhoto extends Model
{
    protected $table = 'expert_visit_photos';

    protected $fillable = [
        'expert_initial_visit_id',
        'photo_type',
        'path',
        'caption',
        'sort_order',
    ];

    public static function getPhotoTypes(): array
    {
        return [
            'general_view'     => 'عکس فضای کلی محل',
            'panel_location'   => 'محل پیشنهادی نصب پنل',
            'electrical_panel' => 'تابلو برق',
            'inverter_location'=> 'محل پیشنهادی نصب اینورتر',
            'battery_location' => 'محل پیشنهادی نصب باتری',
            'structure'        => 'وضعیت سازه',
            'obstacle'         => 'موانع',
            'shading_source'   => 'منابع سایه‌اندازی',
            'cable_route'      => 'مسیر کابل‌کشی',
            'other'            => 'سایر',
        ];
    }

    public function getPhotoTypeLabelAttribute(): string
    {
        return self::getPhotoTypes()[$this->photo_type] ?? $this->photo_type;
    }

    public function visit(): BelongsTo
    {
        return $this->belongsTo(ExpertInitialVisit::class, 'expert_initial_visit_id');
    }
}
