<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = ['service_request_id', 'sender_id', 'recipient_id', 'body', 'read_at'];
    protected $casts = ['read_at' => 'datetime'];

    public function serviceRequest()
    {
        return $this->belongsTo(ServiceRequest::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }
}

