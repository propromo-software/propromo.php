<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contribution_author', function (Blueprint $table) {
            $table->string('contribution_id');
            $table->unsignedBigInteger('author_id');
            $table->timestamps();
            
            $table->foreign('contribution_id')->references('id')->on('contributions')->onDelete('cascade');
            $table->foreign('author_id')->references('id')->on('authors')->onDelete('cascade');
            $table->primary(['contribution_id', 'author_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contribution_author');
    }
};
