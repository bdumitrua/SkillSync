<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PostComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'post_id',
        'text',
        'media_url',
        'likes_count',
    ];

    /**
     * @return int
     */
    public function likesCount(): int
    {
        return $this->likes_count;
    }

    /**
     * @return void
     */
    public function incrementLikesCount(): void
    {
        $this->timestamps = false; // To prevent updated_at change
        $this->increment('likes_count');
        $this->timestamps = true;
    }

    /**
     * @return void
     */
    public function decrementLikesCount(): void
    {
        $this->timestamps = false; // To prevent updated_at change
        $this->decrement('likes_count');
        $this->timestamps = true;
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->BelongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function post(): BelongsTo
    {
        return $this->BelongsTo(Post::class);
    }

    /**
     * @return HasMany
     */
    public function likes(): HasMany
    {
        return $this->hasMany(PostCommentLike::class);
    }
}
