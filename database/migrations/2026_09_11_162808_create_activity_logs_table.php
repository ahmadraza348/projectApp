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
            
            // Who performed the action (nullable in case of system/cron triggers)
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            
            // Polymorphic relation for what was affected (creates subject_id and subject_type)
            $table->morphs('subject'); 
            
            $table->string('event'); // e.g., task_updated, project_created
            $table->text('description')->nullable();
            
            // Storing changes as JSON for easy inspection and formatting
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            
            // Request context
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            
            $table->timestamps();
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
