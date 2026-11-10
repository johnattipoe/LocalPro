<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'provider_profile_id',
        'service_request_id',
        'scheduled_at',
        'quoted_price',
        'final_price',
        'status',
        'recurrence_pattern',
        'parent_booking_id',
        'next_occurrence',
        'occurrence_count',
        'calendar_synced',
        'external_calendar_id',
        'reminder_sent_at',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'quoted_price' => 'decimal:2',
        'final_price' => 'decimal:2',
        'next_occurrence' => 'datetime',
        'occurrence_count' => 'integer',
        'calendar_synced' => 'boolean',
        'reminder_sent_at' => 'datetime',
    ];

    public function serviceRequest()
    {
        return $this->belongsTo(ServiceRequest::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function provider()
    {
        return $this->belongsTo(ProviderProfile::class, 'provider_profile_id');
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'parent_booking_id');
    }

    public function occurrences(): HasMany
    {
        return $this->hasMany(Booking::class, 'parent_booking_id');
    }

    public function isRecurring(): bool
    {
        return $this->recurrence_pattern !== 'none';
    }

    public function nextOccurrenceDate(): ?Carbon
    {
        if (! $this->isRecurring() || ! $this->scheduled_at) {
            return null;
        }

        $base = $this->next_occurrence ?? $this->scheduled_at;

        return match ($this->recurrence_pattern) {
            'weekly' => $base->copy()->addWeek(),
            'monthly' => $base->copy()->addMonth(),
            default => null,
        };
    }
}
