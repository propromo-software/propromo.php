<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vulnerabilities', function (Blueprint $table) {
            $table->id();
            $table->string('repository_name');
            $table->string('package_name');
            $table->string('ecosystem');
            $table->string('classification');
            $table->text('summary');
            $table->text('description');
            $table->string('vulnerable_version_range');
            $table->string('first_patched_version')->nullable();
            $table->string('dependency_scope');
            $table->timestamp('fixed_at')->nullable();
            $table->timestamp('published_at');
            $table->timestamp('updated_at');
            $table->timestamp('created_at');
            $table->foreignId('monitor_id')->constrained()->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vulnerabilities');
    }
};
