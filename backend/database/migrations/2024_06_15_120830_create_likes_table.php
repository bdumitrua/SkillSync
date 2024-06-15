<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->morphs('likeable');
            $table->timestamps();
        });

        DB::transaction(function () {
            // Перенос данных из post_likes
            DB::table('post_likes')->orderBy('id')->chunk(500, function ($postLikes) {
                foreach ($postLikes as $postLike) {
                    DB::table('likes')->insert([
                        'user_id' => $postLike->user_id,
                        'likeable_id' => $postLike->post_id,
                        'likeable_type' => config('entities.post'),
                        'created_at' => $postLike->created_at,
                        'updated_at' => $postLike->updated_at,
                    ]);
                }
            });

            // Перенос данных из post_comment_likes
            DB::table('post_comment_likes')->orderBy('id')->chunk(500, function ($postCommentLikes) {
                foreach ($postCommentLikes as $postCommentLike) {
                    DB::table('likes')->insert([
                        'user_id' => $postCommentLike->user_id,
                        'likeable_id' => $postCommentLike->post_comment_id,
                        'likeable_type' => config('entities.postComment'),
                        'created_at' => $postCommentLike->created_at,
                        'updated_at' => $postCommentLike->updated_at,
                    ]);
                }
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('likes');
    }
};
