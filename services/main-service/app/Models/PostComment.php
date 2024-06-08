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
    ];


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
