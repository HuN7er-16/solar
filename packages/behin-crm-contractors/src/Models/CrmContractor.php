<?php

namespace BehinCrmContractors\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class CrmContractor extends Model
{
    protected $table = 'crm_contractors';

    protected $fillable = [
        'crm_service_center_id',
        'user_id',
        'center_name',
        'mobile',
        'province',
        'synced_at',
    ];

    protected $casts = [
        'synced_at' => 'datetime',
    ];

    /**
     * رابطه با کاربر
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
