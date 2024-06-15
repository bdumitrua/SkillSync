<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Interfaces\Likeable;

class Like extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'likeable_type',
        'likeable_id',
    ];


    /**
     * Decrement the likes count on the likeable model.
     *
     * @return void
     */
    public function decrementLikeableLikesCount(): void
    {
        DB::table($this->likeable()->getRelated()->getTable())
            ->where('id', $this->likeable_id)
            ->decrement('likes_count');
    }

    /**
     * @return MorphTo
     * 
     * @see Likeable
     */
    public function likeable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
