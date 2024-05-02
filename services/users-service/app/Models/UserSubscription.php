<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'subscriber_id',
        'subscribed_id'
    ];

    /**
     * @return BelongsTo
     */
    public function subscriber(): BelongsTo
    {
        return $this->belongsTo(User::class, 'subscriber_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function subscribed(): BelongsTo
    {
        return $this->belongsTo(User::class, 'subscribed_id', 'id');
    }
}
