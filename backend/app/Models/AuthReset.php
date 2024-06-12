<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Database\Factories\AuthResetFactory;
use App\Prometheus\PrometheusServiceProxy;

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

    /**
     * @return void
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            app(PrometheusServiceProxy::class)->incrementEntityCreatedCount('AuthReset');
        });
    }
}
