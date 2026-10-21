<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProviderPayout extends Model
{
    protected $fillable = [
        'provider_profile_id', 'requested_by', 'amount', 'currency', 'status',
        'transfer_reference', 'admin_note', 'processed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'processed_at' => 'datetime',
    ];

    public function provider()
    {
        return $this->belongsTo(ProviderProfile::class, 'provider_profile_id');
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }
}
