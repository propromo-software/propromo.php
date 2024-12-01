<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deployments', function (Blueprint $table) {
            $table->id();
            $table->text('description')->nullable();
            $table->string('log_url');
            $table->string('environment_url');
            $table->string('state');
            $table->foreignId('monitor_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deployments');
    }
};
