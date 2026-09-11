<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
       Schema::create('task_attachments', function (Blueprint $table) {
            $table->id();
            
            // Uploader reference
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            
            // Polymorphic columns (creates attachable_id and attachable_type with an index)
            $table->morphs('attachable');
            
            // File metadata
            $table->string('original_name');
            $table->string('file_name');
            $table->string('file_path');
            $table->string('disk')->default('public');
            $table->string('mime_type')->nullable();
            $table->string('extension')->nullable();
            $table->unsignedBigInteger('size'); // File size in bytes
            $table->string('visibility')->default('private'); // e.g., public, private
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('task_attachments');
    }
};
