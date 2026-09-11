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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            
            // Foreign Keys
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->foreignId('parent_task_id')->nullable()->constrained('tasks')->onDelete('cascade'); // For subtasks
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');

            // Task Details
            $table->string('title');
            $table->string('task_code')->unique()->nullable();
            $table->mediumText('description')->nullable();
            $table->string('type')->nullable(); // e.g., bug, feature, task, improvement

            // Priority & Status
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->string('status')->default('todo'); // e.g., todo, in_progress, review, completed

            // Dates & Tracking
            $table->date('start_date')->nullable();
            $table->date('due_date')->nullable();
            $table->timestamp('completed_at')->nullable();
            
            // Time & Metrics
            $table->decimal('estimated_hours', 5, 2)->default(0.00);
            $table->decimal('actual_hours', 5, 2)->default(0.00);
            $table->unsignedTinyInteger('progress')->default(0); // 0 to 100 percentage
            $table->integer('sort_order')->default(0);
            $table->boolean('is_billable')->default(true);
            $table->text('blocked_reason')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};