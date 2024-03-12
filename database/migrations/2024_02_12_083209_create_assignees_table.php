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
        Schema::create('assignees', function (Blueprint $table) {
            $table->id();
            $table->string("avatar_url")->nullable();
            $table->string("email")->nullable();
            $table->string("login")->nullable();
            $table->string("name")->nullable();
            $table->string("pronouns")->nullable();
            $table->string("url")->nullable();
            $table->string("website_url")->nullable();
            $table->foreignId('task_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignees');
    }
};
