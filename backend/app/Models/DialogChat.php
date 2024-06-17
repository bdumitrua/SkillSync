<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Collection;

class DialogChat extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_id',
        'first_user',
        'second_user'
    ];

    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class);
    }

    public function membersIds(): array
    {
        return [
            'user_id' => $this->first_user,
            'user_id' => $this->second_user,
        ];
    }
}
