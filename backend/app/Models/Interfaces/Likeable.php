<?php

namespace App\Models\Interfaces;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use App\Models\Like;

interface Likeable
{
    /**
     * @return MorphMany
     * 
     * @see Like
     */
    public function likes(): MorphMany;

    /**
     * @return void
     */
    public function incrementLikesCount(): void;

    /**
     * @return void
     */
    public function decrementLikesCount(): void;
}
