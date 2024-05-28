<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'avatar',
        'description',
        'email',
        'site',
        'chat_id',
        'admin_id'
    ];

    /**
     * @return BelongsTo
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * @return HasMany
     */
    public function members(): HasMany
    {
        return $this->hasMany(TeamMember::class);
    }

    /**
     * @return HasMany
     */
    public function links(): HasMany
    {
        return $this->hasMany(TeamLink::class);
    }

    /**
     * @return HasMany
     */
    public function vacancies(): HasMany
    {
        return $this->hasMany(TeamVacancy::class);
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

    /**
     * @return MorphMany
     */
    public function subscribers(): MorphMany
    {
        return $this->morphMany(Subscription::class, 'entity');
    }
}
