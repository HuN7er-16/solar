<?php

namespace ExpertCatalog\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expert extends Model
{
    protected $table = 'experts';

    protected $fillable = [
        'user_id',
        'expert_code',
        'first_name',
        'last_name',
        'national_id',
        'mobile',
        'phone',
        'province',
        'city',
        'address',
        'is_certificated',
    ];

    protected $casts = [
        'is_certificated' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public static function getProvinces(): array
    {
        return config('expert-catalog.provinces', []);
    }
}
