<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contributions', function (Blueprint $table) {
            $table->string('id')->primary(); // GitHub commit hash
            $table->string('commit_url');
            $table->string('message_headline');
            $table->text('message_body')->nullable();
            $table->integer('additions');
            $table->integer('deletions');
            $table->integer('changed_files');
            $table->timestamp('committed_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contributions');
    }
};
