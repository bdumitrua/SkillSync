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
        Schema::create('dialog_chats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chat_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('first_user_id')->index();
            $table->unsignedBigInteger('second_user_id')->index();
            $table->timestamps();

            $table->foreign('first_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('second_user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dialog_chats');
    }
};
