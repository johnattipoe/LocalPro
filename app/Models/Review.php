<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = ['booking_id', 'customer_id', 'provider_profile_id', 'rating', 'comment', 'status'];
    public function booking() { return $this->belongsTo(Booking::class); }
    public function customer() { return $this->belongsTo(User::class, 'customer_id'); }
    public function provider() { return $this->belongsTo(ProviderProfile::class, 'provider_profile_id'); }
}

