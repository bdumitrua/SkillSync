<?php

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
        Schema::create('team_links', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('url');
            $table->text('text')->nullable();
            $table->string('icon_type')->nullable();
            $table->boolean('is_private')->default(false);
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_links');
    }
};
