<?php

namespace ExpertInitialVisit\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use SolarPlantRequests\Models\SolarPlantRequest;

class ExpertInitialVisit extends Model
{
    protected $table = 'expert_initial_visits';

    protected $fillable = [
        'solar_plant_request_id',
        'expert_user_id',
        'visit_date',

        // بخش ۲
        'location_matches',
        'actual_address',
        'location_physically_confirmed',
        'location_access',

        // بخش ۳
        'suitable_space_exists',
        'installation_location_type',
        'installation_location_type_other',
        'total_area_sqm',
        'usable_area_sqm',
        'access_to_installation_site',
        'physical_obstacle_exists',
        'obstacle_types',
        'obstacle_notes',

        // بخش ۴
        'surface_type',
        'surface_orientation',
        'panel_direction',
        'shading_level',
        'shading_sources',
        'surface_condition',
        'surface_notes',

        // بخش ۵
        'structure_load_capacity',
        'reinforcement_needed',
        'special_structure_needed',
        'site_risks',
        'overall_risk_level',
        'structure_notes',

        // بخش ۶
        'electricity_type',
        'connection_capacity_ampere',
        'main_panel_accessible',
        'main_panel_condition',
        'electrical_installation_condition',
        'grid_connection_possible',
        'electrical_fix_needed',
        'electrical_notes',

        // بخش ۷
        'has_emergency_load',
        'total_emergency_load_kw',
        'emergency_supply_hours',
        'battery_need',
        'emergency_load_notes',

        // بخش ۸
        'inverter_location',
        'battery_location',
        'equipment_ventilation_ok',
        'cable_route_ok',
        'new_equipment_space_needed',
        'equipment_location_notes',

        // بخش ۹
        'applicant_requested_capacity_kw',
        'installable_capacity_kw',
        'expert_proposed_capacity_kw',
        'expert_proposed_inverter_kw',
        'battery_required',
        'expert_proposed_battery_kwh',
        'capacity_difference_reason',

        // بخش ۱۰
        'pre_execution_fix_needed',
        'pre_execution_fix_types',
        'pre_execution_fix_description',

        // بخش ۱۱
        'assessment_result',
        'not_feasible_reason',

        // بخش ۱۲
        'expert_summary',

        // بخش ۱۴
        'report_status',
        'submitted_at',
    ];

    protected $casts = [
        'visit_date'                     => 'date',
        'submitted_at'                   => 'datetime',
        'location_matches'               => 'boolean',
        'location_physically_confirmed'  => 'boolean',
        'suitable_space_exists'          => 'boolean',
        'access_to_installation_site'    => 'boolean',
        'physical_obstacle_exists'       => 'boolean',
        'obstacle_types'                 => 'array',
        'shading_sources'                => 'array',
        'site_risks'                     => 'array',
        'reinforcement_needed'           => 'boolean',
        'special_structure_needed'       => 'boolean',
        'main_panel_accessible'          => 'boolean',
        'grid_connection_possible'       => 'boolean',
        'electrical_fix_needed'          => 'boolean',
        'has_emergency_load'             => 'boolean',
        'equipment_ventilation_ok'       => 'boolean',
        'cable_route_ok'                 => 'boolean',
        'new_equipment_space_needed'     => 'boolean',
        'battery_required'               => 'boolean',
        'pre_execution_fix_needed'       => 'boolean',
        'pre_execution_fix_types'        => 'array',
    ];

    // برچسب‌های فارسی نتیجه ارزیابی
    public const RESULT_FEASIBLE          = 'feasible';
    public const RESULT_FEASIBLE_WITH_FIX = 'feasible_with_fix';
    public const RESULT_NOT_FEASIBLE      = 'not_feasible';

    public static function getAssessmentResults(): array
    {
        return [
            self::RESULT_FEASIBLE          => 'قابل اجرا',
            self::RESULT_FEASIBLE_WITH_FIX => 'قابل اجرا با اصلاح',
            self::RESULT_NOT_FEASIBLE      => 'عدم امکان اجرا',
        ];
    }

    public function getAssessmentResultLabelAttribute(): string
    {
        return match ($this->assessment_result) {
            self::RESULT_FEASIBLE          => '<span class="badge" style="background:#C8E6C9;color:#1B5E20;padding:5px 12px;border-radius:8px;">قابل اجرا</span>',
            self::RESULT_FEASIBLE_WITH_FIX => '<span class="badge" style="background:#FFF9C4;color:#F57F17;padding:5px 12px;border-radius:8px;">قابل اجرا با اصلاح</span>',
            self::RESULT_NOT_FEASIBLE      => '<span class="badge" style="background:#FFCDD2;color:#B71C1C;padding:5px 12px;border-radius:8px;">عدم امکان اجرا</span>',
            default                        => '<span class="badge" style="background:#E0E0E0;color:#616161;padding:5px 12px;border-radius:8px;">نامشخص</span>',
        };
    }

    public function getVisitDateJalaliAttribute(): string
    {
        if (! $this->visit_date) return '';
        return \Morilog\Jalali\Jalalian::fromDateTime($this->visit_date)->format('Y/m/d');
    }

    public function request(): BelongsTo
    {
        return $this->belongsTo(SolarPlantRequest::class, 'solar_plant_request_id');
    }

    public function expert(): BelongsTo
    {
        return $this->belongsTo(User::class, 'expert_user_id');
    }

    public function equipmentItems(): HasMany
    {
        return $this->hasMany(ExpertVisitEquipmentItem::class, 'expert_initial_visit_id');
    }

    public function photos(): HasMany
    {
        return $this->hasMany(ExpertVisitPhoto::class, 'expert_initial_visit_id')->orderBy('sort_order');
    }
}
