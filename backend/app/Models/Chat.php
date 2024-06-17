<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Collection;
use App\Enums\ChatType;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'is_empty',
    ];

    public function data(): HasOne
    {
        return $this->isDialog()
            ? $this->hasOne(DialogChat::class)
            : $this->hasOne(GroupChat::class);
    }

    public function membersIds(): array
    {
        if ($this->isDialog()) {
            return $this->data->membersIds();
        } else {
            return $this->hasManyThrough(GroupChatMember::class, GroupChat::class)->get()->pluck('user_id')->toArray();
        }
    }

    public function isDialog(): bool
    {
        return $this->type === ChatType::Dialog->value;
    }

    public function isGroupChat(): bool
    {
        return $this->type === ChatType::Group->value;
    }
}
