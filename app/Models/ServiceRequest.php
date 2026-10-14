<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id', 'provider_profile_id', 'service_category_id', 'description',
        'city', 'address', 'preferred_at', 'status',
    ];

    protected $casts = ['preferred_at' => 'datetime'];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function provider()
    {
        return $this->belongsTo(ProviderProfile::class, 'provider_profile_id');
    }

    public function category()
    {
        return $this->belongsTo(ServiceCategory::class, 'service_category_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class)->orderBy('created_at');
    }

    public function booking() { return $this->hasOne(Booking::class); }
}

