<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'receiver_id',
        'type',
        'status',
        'from_id',
        'from_type',
        'to_id',
        'to_type',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function from(): MorphTo
    {
        return $this->morphTo('from');
    }

    public function to(): MorphTo
    {
        return $this->morphTo('to');
    }
}
