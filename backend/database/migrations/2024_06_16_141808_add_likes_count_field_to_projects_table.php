<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Project;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->integer('likes_count')->default(0);
        });

        Project::updateElasticsearchIndex();
        $this->fillExistingPostsLikesCount();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('likes_count');
        });
    }

    protected function fillExistingPostsLikesCount(): void
    {
        DB::table('projects')->get()->each(function ($project) {
            $likesCount = DB::table('likes')
                ->where('likeable_type', config('entities.project'))
                ->where('likeable_id', $project->id)
                ->count();

            DB::table('projects')
                ->where('id', $project->id)
                ->update(['likes_count' => $likesCount]);
        });

        Log::info('Projects likes count updated successfully.');
    }
};
