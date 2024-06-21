<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Interfaces\Likeable;

class PostComment extends Model implements Likeable
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'post_id',
        'text',
        'media_url',
        'likes_count',
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($comment) {
            $comment->likes()->delete();
        });
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
     * @return MorphMany
     */
    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->BelongsTo(User::class);
    }

    public function author(): BelongsTo
    {
        return $this->user();
    }

    public function getAuthorType(): string
    {
        return config('entities.user');
    }

    public function getAuthorId(): int
    {
        return $this->user_id;
    }

    /**
     * @return BelongsTo
     */
    public function post(): BelongsTo
    {
        return $this->BelongsTo(Post::class);
    }
}
