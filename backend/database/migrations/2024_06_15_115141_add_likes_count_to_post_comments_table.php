<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\PostComment;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('post_comments', function (Blueprint $table) {
            $table->integer('likes_count')->default(0);
        });

        $this->fillExistingPostsLikesCount();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('post_comments', function (Blueprint $table) {
            $table->dropColumn('likes_count');
        });
    }

    protected function fillExistingPostsLikesCount(): void
    {
        DB::table('post_comments')->get()->each(function ($postComment) {
            $likesCount = DB::table('post_comment_likes')
                ->where('post_comment_id', $postComment->id)
                ->count();

            DB::table('post_comments')
                ->where('id', $postComment->id)
                ->update(['likes_count' => $likesCount]);
        });

        Log::info('PostComment likes count updated successfully.');
    }
};
