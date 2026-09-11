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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            
            // Foreign Keys
            $table->foreignId('project_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            
            // Expense Details
            $table->string('category'); // e.g., travel, software, hardware, meals
            $table->string('title');
            $table->text('description')->nullable();
            
            // Financials
            $table->decimal('amount', 12, 2)->default(0.00);
            $table->string('currency', 3)->default('USD'); // ISO currency code e.g., USD, EUR, PKR
            $table->date('expense_date');
            
            // Vendor & Payment Info
            $table->string('vendor')->nullable();
            $table->string('payment_method')->nullable(); // e.g., credit_card, cash, bank_transfer
            $table->string('receipt_path')->nullable();
            
            // Approval Status & Notes
            $table->string('status')->default('pending'); // e.g., pending, approved, rejected
            $table->timestamp('approved_at')->nullable();
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
        Schema::dropIfExists('expenses');
    }
};