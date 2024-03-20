<?php

namespace App\Models;

use App\Prometheus\PrometheusServiceProxy;
use Database\Factories\AuthRegistrationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'confirmed',
        'first_name',
        'second_name',
        'email',
        'birth_date',
    ];

    protected static function newFactory()
    {
        return AuthRegistrationFactory::new();
    }

    /**
     * @return void
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($registrationData) {
            app(PrometheusServiceProxy::class)->incrementEntityCreatedCount('AuthRegistration');
        });
    }
}
