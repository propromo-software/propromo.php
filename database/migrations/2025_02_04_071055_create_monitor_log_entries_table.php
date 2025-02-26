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
        Schema::create('monitor_log_entries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('monitor_log_id');
            $table->foreign('monitor_log_id')->references('id')->on('monitor_logs');
            $table->text('message');                   // Detailed log message
            $table->string('level')->default('info');  // Log level: info, warning, error, etc.
            $table->json('context')->nullable();       // Optional extra context stored as JSON
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monitor_log_entries');
    }
};
