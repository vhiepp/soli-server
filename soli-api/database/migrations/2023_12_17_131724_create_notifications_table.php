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
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('user_id', 36);
            $table->string('done_by_user_id', 36);
            $table->string('type', 50);
            $table->string('message');
            $table->json('detail_data')->nullable();
            $table->enum('status', ['not_seen', 'seen', 'deleted'])->default('not_seen');
            $table->string('created_at', 20);
            $table->string('updated_at', 20);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
