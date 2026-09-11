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
        Schema::create('task_comments', function (Blueprint $table) {
            $table->id();
            
            // Foreign Keys
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('task_id')->constrained('tasks')->onDelete('cascade');
            
            // Self-referencing foreign key for nested/threaded comments
            $table->foreignId('parent_comment_id')->nullable()->constrained('task_comments')->onDelete('cascade');
            
            // Comment Content
            $table->text('body');
            
            // Timestamps & Soft Deletes
            $table->timestamp('edited_at')->nullable();
            $table->timestamps();
            $table->softDeletes(); // Adds deleted_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_comments');
    }
};