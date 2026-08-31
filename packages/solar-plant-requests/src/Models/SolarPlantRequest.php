<?php

namespace SolarPlantRequests\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use SolarPlantRequests\Enums\ApplicantType;
use SolarPlantRequests\Enums\PurposeType;
use SolarPlantRequests\Enums\SolarPlantRequestStatus;
use SolarPlantRequests\Enums\SurfaceType;
use SolarPlantRequests\Enums\UsageType;

class SolarPlantRequest extends Model
{
    protected $fillable = [
        'user_id',
        'applicant_type',
        'first_name',
        'last_name',
        'mobile',
        'national_code',
        'company_name',
        'registration_number',
        'ceo_national_id',
        'immigration_code',
        'landline',
        'province',
        'city',
        'postal_code',
        'address',
        'bill_identifier',
        'area',
        'usage_type',
        'is_shared_property',
        'installation_area',
        'surface_type',
        'purpose',
        'capacity_kw',
        'has_three_phase',
        'wants_loan',
        'description',
        'images',
        'documents',
        'unique_code',
        'contractor_id',
        'contractor_name',
        'inspector_user_id',
        'inspector_name',
        'expert_user_id',
        'expert_name',
        'status',
    ];

    protected $casts = [
        'applicant_type' => ApplicantType::class,
        'usage_type' => UsageType::class,
        'surface_type' => SurfaceType::class,
        'purpose' => PurposeType::class,
        'status' => SolarPlantRequestStatus::class,
        'is_shared_property' => 'boolean',
        'has_three_phase' => 'boolean',
        'wants_loan' => 'boolean',
        'images' => 'array',
        'documents' => 'array',
    ];

    protected $appends = ['status_label'];

    protected static function booted(): void
    {
        static::creating(function (SolarPlantRequest $model) {
            if (empty($model->unique_code)) {
                $model->unique_code = self::generateUniqueCode();
            }
        });
    }

    public static function generateUniqueCode(): string
    {
        $prefix = 'SPR';
        $timestamp = time();
        $random = strtoupper(substr(bin2hex(random_bytes(3)), 0, 4));
        return $prefix . $timestamp . $random;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function contractor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'contractor_id');
    }

    public function inspector(): BelongsTo
    {
        return $this->belongsTo(User::class, 'inspector_user_id');
    }

    public function expert(): BelongsTo
    {
        return $this->belongsTo(User::class, 'expert_user_id');
    }

    public function panels(): HasMany
    {
        return $this->hasMany(Panel::class, 'solar_plant_request_id');
    }

    public function inverters(): HasMany
    {
        return $this->hasMany(Inverter::class, 'solar_plant_request_id');
    }

    public function batteries(): HasMany
    {
        return $this->hasMany(Battery::class, 'solar_plant_request_id');
    }

    public function scopeVisibleTo(Builder $query, User $user): Builder
    {
        if (self::userHasRole($user, 'leader')) {
            return $query;
        }

        if (self::userHasRole($user, 'contractor')) {
            return $query
                ->where('contractor_id', $user->id)
                ->where('status', SolarPlantRequestStatus::EQUIPMENT_INSTALLATION);
        }

        if (self::userHasRole($user, 'inspector')) {
            return $query->where('inspector_user_id', $user->id);
        }

        if (self::userHasRole($user, 'expert')) {
            return $query->where('expert_user_id', $user->id);
        }

        return $query->where('user_id', $user->id);
    }

    public static function userHasRole(User $user, string $roleKey): bool
    {
        $roleIds = collect(config("solar-plant-requests.roles.{$roleKey}", []))
            ->map(fn ($id) => (string) $id)
            ->all();

        return in_array((string) $user->role_id, $roleIds, true);
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->status?->label() ?? '';
    }
}
