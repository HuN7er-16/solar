<?php

namespace ExpertInitialVisit\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExpertVisitEquipmentItem extends Model
{
    protected $table = 'expert_visit_equipment_items';

    protected $fillable = [
        'expert_initial_visit_id',
        'name',
        'quantity',
        'power_watts',
        'total_power_watts',
        'usage_hours',
        'is_critical',
        'notes',
    ];

    protected $casts = [
        'is_critical' => 'boolean',
    ];

    public function visit(): BelongsTo
    {
        return $this->belongsTo(ExpertInitialVisit::class, 'expert_initial_visit_id');
    }
}
