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
        Schema::create('monitors', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('login_name')->nullable();
            $table->string('project_url')->nullable();
            $table->string('organization_name')->nullable();
            $table->text('pat_token')->nullable();
            $table->string('readme')->nullable();
            $table->boolean('public')->nullable();
            $table->string('title')->nullable();
            $table->text('short_description')->nullable();
            $table->integer('project_identification');
            $table->string('monitor_hash');
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monitors');
    }
};
