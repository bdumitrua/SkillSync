<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeamApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'vacancy_id',
        'team_id',
        'text',
        'status'
    ];

    /**
     * @return BelongsTo
     */
    public function vacancy(): BelongsTo
    {
        return $this->belongsTo(TeamVacancy::class, 'vacancy_id');
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
