<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PostLike extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'post_id',
    ];

    /**
     * @return BelongsTo
     */
    public function post(): BelongsTo
    {
        return $this->BelongsTo(Post::class);
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->BelongsTo(User::class);
    }
}
