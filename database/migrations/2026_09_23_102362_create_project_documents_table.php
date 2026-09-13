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
        Schema::create('project_documents', function (Blueprint $table) {
            $table->id();
            
            // Foreign Keys
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            
            // Document Details
            $table->string('name');
            $table->mediumText('description')->nullable();
            
            // File Metadata & Storage
            $table->string('file_path');
            $table->string('file_type')->nullable(); // e.g., pdf, spreadsheet, contract
            $table->unsignedBigInteger('size'); // File size in bytes
            $table->string('version')->default('1.0'); // Supports semantic versions
            $table->string('visibility')->default('internal'); // e.g., internal, client
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_documents');
    }
};