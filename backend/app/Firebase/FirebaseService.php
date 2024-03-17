<?php

namespace App\Firebase;

use Kreait\Firebase\Database;

class FirebaseService
{
    protected Database $database;
    protected string $bucket;

    public function __construct()
    {
        $this->database = app('firebase.database');
        $this->bucket = config('firebase.projects.app.storage.bucket');
    }

    public function wipeMyData(): bool
    {
        $bucketReference = $this->database->getReference($this->bucket);
        if (!$bucketReference->getSnapshot()->exists()) {
            return false;
        }

        $bucketReference->remove();
        return true;
    }
}
