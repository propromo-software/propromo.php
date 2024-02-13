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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('project_url')->nullable();
            $table->string('organisation_name')->nullable();
            $table->string('readme')->nullable();
            $table->boolean('public')->nullable();
            $table->string('title')->nullable();
            $table->string('url')->nullable();
            $table->string('short_description')->nullable();
            $table->integer('project_identification');
            $table->integer('project_view');
            $table->string('project_hash');
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
