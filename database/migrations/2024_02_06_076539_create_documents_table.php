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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('page_number')->nullable();
            $table->dateTime('issue_date');
            $table->dateTime('post_date');
            $table->dateTime('receive_date')->nullable();
            $table->boolean('is_paid')->default(false);
            $table->string('path');
            $table->string('type', 100); // Adjusted length for MIME types
            $table->string('size');
            $table->string('amount')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('post_office_box_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            // Optional: Soft Deletes
            $table->softDeletes();

            // Optional: Index for quicker searches
            $table->index('user_id');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
