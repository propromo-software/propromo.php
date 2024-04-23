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
        Schema::create('milestones', function (Blueprint $table) {
            $table->id();
            $table->string("title");
            $table->string("url");
            $table->string("state");
            $table->string("description")->nullable();
            $table->dateTime("due_on")->nullable();
            $table->integer("milestone_id");
            $table->integer("open_issues_count")->nullable();
            $table->integer("closed_issues_count")->nullable();
            $table->double("progress");
            $table->foreignId("repository_id");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('milestones');
    }
};
