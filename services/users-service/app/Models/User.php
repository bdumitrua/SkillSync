<?php

namespace App\Models;

use App\Prometheus\PrometheusServiceProxy;
use Database\Factories\UserFactory;
use Elastic\ScoutDriverPlus\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * * Модель, относящаяся к таблице users
 * 
 * * Необходима для работы с основными данными пользователей и выстраивания связей.
 * 
 * * Также, соответственно, отвечает за аутентификацию пользователей и валидацию JWT токенов.
 */
class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable, Searchable, SoftDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'nick_name',
        'email',
        'password',
        'phone',
        'about',
        'avatar',
        'address',
        'birthdate',
        'gender',
        'token_invalid_before'
    ];

    protected $hidden = [
        'password',
    ];

    protected $searchable = [
        'first_name',
        'last_name',
        'nick_name',
    ];

    protected $casts = [
        'token_invalid_before' => 'datetime',
    ];

    public function toSearchableArray(): array
    {
        return [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'nick_name' => $this->nick_name,
        ];
    }

    public function searchableAs(): string
    {
        return 'users';
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [
            'userId' => $this->id,
            'email' => $this->email
        ];
    }

    protected static function newFactory()
    {
        return UserFactory::new();
    }

    protected static function boot(): void
    {
        parent::boot();

        static::created(function ($user) {
            app(PrometheusServiceProxy::class)->incrementEntityCreatedCount('User');
        });
    }

    /**
     * @return HasMany
     */
    public function subscribers(): HasMany
    {
        return $this->hasMany(UserSubscription::class, 'subscribed_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function subscribtions(): HasMany
    {
        return $this->hasMany(UserSubscription::class, 'subscriber_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function interests(): HasMany
    {
        return $this->hasMany(UserInterest::class, 'user_id', 'id');
    }
}
