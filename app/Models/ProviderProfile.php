<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProviderProfile extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'profession', 'bio', 'city', 'region', 'service_area', 'starting_price', 'verification_status', 'average_rating', 'review_count', 'availability'];
    protected $casts = ['starting_price' => 'decimal:2', 'average_rating' => 'decimal:1', 'availability' => 'array'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function serviceRequests()
    {
        return $this->hasMany(ServiceRequest::class);
    }

    public function services() { return $this->hasMany(ProviderService::class); }
    public function reviews() { return $this->hasMany(Review::class)->where('status', 'published')->latest(); }
    public function verifications() { return $this->hasMany(ProviderVerification::class); }
    public function payouts() { return $this->hasMany(ProviderPayout::class); }
}

