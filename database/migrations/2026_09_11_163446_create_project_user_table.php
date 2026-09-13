<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
       Schema::create('project_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Project-specific responsibility role (separate from global Spatie roles/permissions)
            $table->enum('role', ['manager', 'developer', 'designer', 'qa', 'member'])->default('member');
            
            $table->timestamp('joined_at')->nullable();
            $table->timestamp('left_at')->nullable();
            $table->string('status')->default('active'); // e.g., active, inactive, suspended
            
            $table->timestamps();

            // Prevent duplicate entries for the same user on a project
            $table->unique(['project_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_user');
    }
};