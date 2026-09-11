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
        Schema::create('time_logs', function (Blueprint $table) {
            $table->id();
            
            // Foreign Keys
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('project_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('task_id')->nullable()->constrained()->onDelete('cascade');
            
            // Time Trackers
            $table->dateTime('started_at')->nullable();
            $table->dateTime('ended_at')->nullable();
            $table->integer('duration_minutes')->default(0); // Total duration calculated or logged
            
            // Details & Financials
            $table->text('description')->nullable();
            $table->boolean('is_billable')->default(true);
            $table->decimal('hourly_rate', 8, 2)->default(0.00);
            $table->decimal('total_amount', 10, 2)->default(0.00);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('time_logs');
    }
};