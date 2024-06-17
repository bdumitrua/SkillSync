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
            $table->unsignedBigInteger('first_user')->index();
            $table->unsignedBigInteger('second_user')->index();
            $table->timestamps();

            $table->foreign('first_user')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('second_user')->references('id')->on('users')->onDelete('cascade');
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
