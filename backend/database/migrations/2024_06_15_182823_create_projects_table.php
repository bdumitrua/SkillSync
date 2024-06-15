<?php

use Illuminate\Support\Facades\Schema;
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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->morphs('author');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('cover_url')->nullable();
            $table->timestamps();
        });

        Project::createElasticsearchIndex();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');

        Project::deleteElasticsearchIndex();
    }
};
