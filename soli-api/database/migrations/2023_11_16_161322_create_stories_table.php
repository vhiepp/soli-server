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
        Schema::create('stories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->longText('description');
            $table->string('media_id', 36);
            $table->enum('status', ['showing', 'await', 'deleted', 'hidden'])->default('await');
            $table->enum('limit', ['all', 'friends', 'only_me'])->default('all');
            $table->string('created_at', 20);
            $table->string('updated_at', 20);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stories');
    }
};
