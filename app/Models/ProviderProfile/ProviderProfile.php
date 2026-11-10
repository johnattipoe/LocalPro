<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProviderProfile extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'profession', 'bio', 'years_experience', 'experience_summary', 'city', 'region', 'service_area', 'location_address', 'location_latitude', 'location_longitude', 'service_mode', 'starting_price', 'response_time_hours', 'verification_status', 'average_rating', 'review_count', 'availability', 'accepting_requests', 'profile_photo_path', 'profile_photo_disk'];

    protected $casts = ['starting_price' => 'decimal:2', 'average_rating' => 'decimal:1', 'location_latitude' => 'decimal:7', 'location_longitude' => 'decimal:7', 'response_time_hours' => 'integer', 'years_experience' => 'integer', 'availability' => 'array', 'accepting_requests' => 'boolean'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function serviceRequests()
    {
        return $this->hasMany(ServiceRequest::class);
    }

    public function services()
    {
        return $this->hasMany(ProviderService::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class)->where('status', 'published')->latest();
    }

    public function verifications()
    {
        return $this->hasMany(ProviderVerification::class);
    }

    public function payouts()
    {
        return $this->hasMany(ProviderPayout::class);
    }

    public function favoritedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favorite_providers', 'provider_profile_id', 'user_id')
            ->withTimestamps();
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(FavoriteProvider::class);
    }

    public function getDirectionsUrlAttribute(): string
    {
        $destination = $this->location_latitude !== null && $this->location_longitude !== null
            ? $this->location_latitude.','.$this->location_longitude
            : implode(', ', array_filter([
                $this->location_address,
                $this->city,
                $this->region ?: 'Volta Region',
                'Ghana',
            ]));

        return 'https://www.google.com/maps/dir/?'.http_build_query([
            'api' => '1',
            'destination' => $destination,
        ], '', '&', PHP_QUERY_RFC3986);
    }
}
