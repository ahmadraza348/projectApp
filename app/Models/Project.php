<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [

            'department_id',
            'client_id',
            'created_by',
            'project_manager_id',
            'name',
            'code',
            'slug',
            'description',
            'start_date',
            'deadline',
            'completed_at',
            'budget',
            'priority',
            'status',
            'health_status',
            'progress',
            'is_billable',
            'notes',
    ];

    // Added: start_date/end_date come back from the DB as plain strings without this,
    // so ->format() in the view crashes ("Call to a member function format() on string")
    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'budget'     => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'project_user');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
    public function timeLogs()
    {
        return $this->hasManyThrough(TaskTimeLog::class, Task::class);
    }
}