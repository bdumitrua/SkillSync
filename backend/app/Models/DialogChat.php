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
        'first_user_id',
        'second_user_id'
    ];

    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class);
    }

    public function membersIds(): array
    {
        return [
            $this->first_user_id,
            $this->second_user_id,
        ];
    }
}
