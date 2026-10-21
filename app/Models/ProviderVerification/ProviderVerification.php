<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProviderVerification extends Model
{
    use HasFactory;

    protected $fillable = ['provider_profile_id','reviewer_id','document_type','document_path','status','review_notes','reviewed_at'];
    protected $casts = ['reviewed_at'=>'datetime'];
    public function provider() { return $this->belongsTo(ProviderProfile::class, 'provider_profile_id'); }
    public function reviewer() { return $this->belongsTo(User::class, 'reviewer_id'); }
}

