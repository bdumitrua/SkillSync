<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GroupChat extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_id',
        'admin_type',
        'admin_id',
        'name',
        'avatar_url'
    ];

    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class);
    }

    public function admin(): MorphTo
    {
        return $this->morphTo();
    }

    public function isTeamChat(): bool
    {
        return $this->admin_type === config('entities.team');
    }

    public function isUserGroupChat(): bool
    {
        return $this->admin_type === config('entities.user');
    }

    public function createdByUser(int $userId): bool
    {
        return $this->isUserGroupChat() && $this->admin_id === $userId;
    }
}
