<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_active')->nullable();
            $table->string('body_url')->nullable();

            $table->date('created_at')->nullable();
            $table->date('updated_at')->nullable();
            $table->date('last_edited_at')->nullable();
            $table->date('closed_at')->nullable();

            $table->string('body')->nullable();
            $table->string('title')->nullable();
            $table->string('url')->nullable();
            $table->foreignId('milestone_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
