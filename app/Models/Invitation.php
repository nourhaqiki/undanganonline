<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'edit_token',
        'category',
        'theme_slug',
        'title',
        'event_data',
    ];

    protected $casts = [
        'event_data' => 'array',
    ];

    public function theme(): BelongsTo
    {
        return $this->belongsTo(Theme::class, 'theme_slug', 'slug');
    }

    public function wishes(): HasMany
    {
        return $this->hasMany(InvitationWish::class)->latest();
    }
}
