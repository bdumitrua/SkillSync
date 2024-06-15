<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('post_likes');
        Schema::dropIfExists('post_comment_likes');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Nah
    }
};
