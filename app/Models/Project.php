<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'category_id',
        'assigned_user_id',
        'status',
        'start_date',
        'end_date',
        'budget',
    ];

    /**
     * Relationship with Category model.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relationship with User model (Assignee / Many-to-Many Members).
     */
    public function members()
    {
        return $this->belongsToMany(User::class, 'project_user');
    }
}