<?php

namespace ContractorCatalog\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contractor extends Model
{
    protected $table = 'contractors';

    protected $fillable = [
        'user_id',
        'company_name',
        'national_id',
        'ceo_name',
        'ceo_national_code',
        'ceo_mobile',
        'contact_person_name',
        'contact_person_mobile',
        'company_phone',
        'province',
        'city',
        'address',
        'license_number',
        'license_issue_date',
        'license_expiry_date',
        'registered_projects_count',
    ];

    protected $casts = [
        'license_issue_date'        => 'date',
        'license_expiry_date'       => 'date',
        'registered_projects_count' => 'integer',
    ];

    /** رابطه با کاربر مرتبط */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /** آیا پروانه معتبر است؟ */
    public function getIsLicenseValidAttribute(): bool
    {
        return $this->license_expiry_date && $this->license_expiry_date->isFuture();
    }

    /** لیست استان‌ها */
    public static function getProvinces(): array
    {
        return config('contractor-catalog.provinces', []);
    }
}
