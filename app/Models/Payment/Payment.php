<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = ['booking_id','customer_id','amount','currency','provider_reference','status','paid_at'];
    protected $casts = ['amount'=>'decimal:2','paid_at'=>'datetime'];
    public function booking() { return $this->belongsTo(Booking::class); }
    public function customer() { return $this->belongsTo(User::class,'customer_id'); }
}

