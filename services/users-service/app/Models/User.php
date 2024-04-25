<?php

namespace App\Models;

use App\Prometheus\PrometheusServiceProxy;
use Database\Factories\UserFactory;
use Elastic\ScoutDriverPlus\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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
        'second_name',
        'nick_name',
        'email',
        'password',
        'about',
        'avatar',
        'address',
        'birth_date',
        'token_invalid_before'
    ];

    protected $hidden = [
        'password',
    ];

    protected $searchable = [
        'first_name',
        'second_name',
        'nick_name',
    ];

    protected $casts = [
        'token_invalid_before' => 'datetime:YYYY-MM-DDTHH:MM:SS.uuuuuuZ',
    ];

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray(): array
    {
        return [
            'first_name' => $this->first_name,
            'second_name' => $this->second_name,
            'nick_name' => $this->nick_name,
        ];
    }

    /**
     * @return string
     */
    public function searchableAs(): string
    {
        return 'users';
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
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

    /**
     * @return void
     */
    protected static function boot(): void
    {
        parent::boot();

        static::created(function ($user) {
            app(PrometheusServiceProxy::class)->incrementEntityCreatedCount('User');
        });
    }
}
