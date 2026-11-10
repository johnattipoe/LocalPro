<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuickReplyTemplate extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'title', 'body', 'is_system'];

    protected $casts = ['is_system' => 'boolean'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function systemTemplates(): Builder
    {
        return self::where('is_system', true);
    }

    public static function userTemplates(int $userId): Builder
    {
        return self::where('user_id', $userId)->orWhere('is_system', true);
    }
}
