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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('activity_type');
            $table->text('description');
            $table->foreignId('document_id')->nullable();
            $table->foreignId('post_office_box_id')->nullable();
            $table->ipAddress()->nullable();
            $table->timestamps();

            $table->softDeletes();

            $table->index('user_id');
            $table->index('activity_type');
            $table->index('document_id');
            $table->index('post_office_box_id');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
