<?php

namespace App\Models;

use App\Prometheus\PrometheusServiceProxy;
use Database\Factories\UserFactory;
use Elastic\ScoutDriverPlus\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
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
     * @return MorphMany
     */
    public function subscribers(): MorphMany
    {
        return $this->morphMany(Subscription::class, 'entity');
    }

    /**
     * @return hasMany
     */
    public function subscriptions(): hasMany
    {
        return $this->hasMany(Subscription::class, 'user_id', 'id');
    }

    /**
     * @return MorphMany
     */
    public function posts(): MorphMany
    {
        return $this->morphMany(Post::class, 'entity');
    }

    /**
     * @return MorphMany
     */
    public function tags(): MorphMany
    {
        return $this->morphMany(Tag::class, 'entity');
    }
}
