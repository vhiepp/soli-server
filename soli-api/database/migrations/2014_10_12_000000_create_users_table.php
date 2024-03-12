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
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('full_name');
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('uid')->unique();
            $table->string('email')->unique();
            $table->dateTime('date_of_birth')->nullable();
            $table->enum('gender', ["male", "female", "other"])->default("other");
            $table->string('address')->nullable();
            $table->enum('role', ["user", "admin"])->default("user");
            $table->string('maxim')->nullable();
            $table->string('created_at', 20);
            $table->string('updated_at', 20);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
