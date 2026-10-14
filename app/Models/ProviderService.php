<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProviderService extends Model
{
    use HasFactory;

    protected $fillable = ['provider_profile_id', 'service_category_id', 'title', 'description', 'pricing_type', 'price', 'is_active'];
    protected $casts = ['is_active' => 'boolean', 'price' => 'decimal:2'];

    public function provider() { return $this->belongsTo(ProviderProfile::class, 'provider_profile_id'); }
    public function category() { return $this->belongsTo(ServiceCategory::class, 'service_category_id'); }
}

