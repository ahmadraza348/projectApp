<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expence extends Model
{
    /** @use HasFactory<\Database\Factories\ExpenceFactory> */
    use HasFactory, SoftDeletes;

    protected $table = 'expenses';

    protected $fillable = [
        'project_id', 'created_by', 'approved_by', 'category', 'title',
        'description', 'amount', 'currency', 'expense_date', 'vendor',
        'payment_method', 'receipt_path', 'status', 'approved_at', 'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'expense_date' => 'date',
        'approved_at' => 'datetime',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
