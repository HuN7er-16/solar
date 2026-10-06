<?php

namespace SolarPlantRequests\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class SolarPlantPackage extends Model
{
    protected $table = 'solar_plant_packages';

    protected $fillable = [
        'capacity_kw',
        'title',
        'description',
        'price',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'capacity_kw' => 'integer',
        'price'       => 'integer',
        'sort_order'  => 'integer',
        'is_active'   => 'boolean',
    ];

    /**
     * فقط پکیج‌های فعال
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order')->orderBy('capacity_kw');
    }

    /**
     * تمام پکیج‌های فعال گروه‌بندی‌شده بر اساس ظرفیت
     * مثال خروجی: [5 => Collection, 10 => Collection]
     */
    public static function allGroupedByCapacity(): array
    {
        return static::active()
            ->get()
            ->groupBy('capacity_kw')
            ->toArray();
    }

    /**
     * فرمت قیمت به صورت فارسی با جداکننده هزارگان
     */
    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->price) . ' ریال';
    }
}
