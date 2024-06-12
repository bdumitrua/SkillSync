<?php

use App\Models\Post;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->text('text');
            $table->string('media_url')->nullable();
            $table->string('entity_type');
            $table->unsignedBigInteger('entity_id');
            $table->timestamps();

            $table->index(['entity_type', 'entity_id']);
        });

        Post::createElasticsearchIndex();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');

        Post::deleteElasticsearchIndex();
    }
};
