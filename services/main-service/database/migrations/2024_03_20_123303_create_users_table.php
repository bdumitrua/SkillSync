<?php

use Elastic\Elasticsearch\ClientBuilder;
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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('nick_name')->unique()->nullable();
            $table->string('phone')->unique()->nullable();
            $table->string('email')->unique()->index();
            $table->string('password');
            $table->string('about')->nullable();
            $table->string('avatar')->nullable();
            $table->string('address')->nullable();
            $table->string('gender')->nullable();
            $table->timestamp('birthdate');
            $table->timestamp('token_invalid_before')->default(now());

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Удаление таблицы пользователей из базы данных
        Schema::dropIfExists('users');
    }
};
