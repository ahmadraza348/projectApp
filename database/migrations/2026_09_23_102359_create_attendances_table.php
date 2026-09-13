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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            
            // Foreign Key
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Date & Times
            $table->date('attendance_date');
            $table->timestamp('clock_in_at')->nullable();
            $table->timestamp('clock_out_at')->nullable();
            
            // Metrics (stored in minutes for precision)
            $table->unsignedInteger('break_minutes')->default(0);
            $table->unsignedInteger('worked_minutes')->default(0);
            
            // Status & Notes
            $table->string('status')->default('present'); // e.g., present, late, half_day, absent, on_leave
            $table->text('notes')->nullable();
            
            $table->timestamps();

            // Prevent duplicate records for the same user on the same date
            $table->unique(['user_id', 'attendance_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};