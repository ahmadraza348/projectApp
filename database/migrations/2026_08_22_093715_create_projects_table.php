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
            $table->string('name')->unique();
            $table->mediumText('description')->nullable();

            // Foreign keys with cascading options
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->foreignId('assigned_user_id')->nullable()->constrained('users')->onDelete('set null');

            $table->enum('status', ['planning', 'in_progress', 'review', 'complete'])->default('planning');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            
            // Decimal for monetary values (10 digits total, 2 decimal places)
            $table->decimal('budget', 10, 2)->default(0.00);

            $table->timestamps();
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