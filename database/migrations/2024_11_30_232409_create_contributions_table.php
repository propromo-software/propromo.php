<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contributions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('author_id')->constrained()->cascadeOnDelete();
            $table->string('commit_url');
            $table->text('message_headline');
            $table->text('message_body')->nullable();
            $table->integer('additions')->default(0);
            $table->integer('deletions')->default(0);
            $table->integer('changed_files')->default(0);
            $table->timestamp('committed_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contributions');
    }
};
