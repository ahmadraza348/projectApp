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
     Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('client_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('project_manager_id')->nullable()->constrained('users')->nullOnDelete();
            
            $table->string('name');
            $table->string('code')->unique()->nullable();
            $table->string('slug')->unique();
            $table->mediumText('description')->nullable();
            
            $table->date('start_date')->nullable();
            $table->date('deadline')->nullable();
            $table->timestamp('completed_at')->nullable();
            
            $table->decimal('budget', 12, 2)->default(0.00);
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->string('status')->default('planning'); // e.g., planning, in_progress, review, completed
            $table->string('health_status')->nullable(); // e.g., on_track, at_risk, off_track
            $table->unsignedTinyInteger('progress')->default(0); // Percentage from 0 to 100
            $table->boolean('is_billable')->default(true);
            
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};