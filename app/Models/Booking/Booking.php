<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id', 'provider_profile_id', 'service_request_id', 'scheduled_at', 'quoted_price', 'final_price', 'status'];
    protected $casts = ['scheduled_at' => 'datetime', 'quoted_price' => 'decimal:2', 'final_price' => 'decimal:2'];

    public function serviceRequest() { return $this->belongsTo(ServiceRequest::class); }
    public function customer() { return $this->belongsTo(User::class, 'customer_id'); }
    public function provider() { return $this->belongsTo(ProviderProfile::class, 'provider_profile_id'); }
    public function review() { return $this->hasOne(Review::class); }
    public function payments() { return $this->hasMany(Payment::class); }
}

