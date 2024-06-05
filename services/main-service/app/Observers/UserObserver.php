<?php

namespace App\Observers;

use App\Models\User;
use Elastic\Elasticsearch\Client;

class UserObserver
{
    public function created(User $user)
    {
        $user->searchable();
    }

    public function deleted(User $user)
    {
        $user->unsearchable();
    }
}
