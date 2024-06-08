<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PostCommentLike extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'post_comment_id',
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
    public function comment(): BelongsTo
    {
        return $this->BelongsTo(PostComment::class);
    }
}
