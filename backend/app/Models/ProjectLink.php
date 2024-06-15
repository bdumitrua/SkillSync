<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProjectLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'url',
        'text',
        'icon_type'
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
