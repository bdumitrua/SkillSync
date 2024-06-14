<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Post;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->integer('likes_count')->default(0);
        });

        $this->fillExistingPostsLikesCount();

        Post::updateElasticsearchIndex();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('likes_count');
        });

        Post::updateElasticsearchIndex();
    }

    protected function fillExistingPostsLikesCount(): void
    {
        DB::table('posts')->get()->each(function ($post) {
            $likesCount = DB::table('post_likes')
                ->where('post_id', $post->id)
                ->count();

            DB::table('posts')
                ->where('id', $post->id)
                ->update(['likes_count' => $likesCount]);
        });

        Log::info('Likes count updated successfully.');
    }
};
