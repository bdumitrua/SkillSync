<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Database\Factories\AuthResetFactory;

class AuthReset extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'code',
        'confirmed'
    ];

    protected static function newFactory()
    {
        return AuthResetFactory::new();
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
