<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'project_id',
        'title',
        'description',
        'member_id',
        'priority',
        'status',
        'due_date',
        'estimated_hours',
    ];

    protected $attributes = [
        'status' => 'todo',
    ];

    protected $casts = [
        'due_date'        => 'date',
        'estimated_hours' => 'float',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'member_id');
    }

    public function comments()
    {
        return $this->hasMany(TaskComment::class)->with('user')->latest();
    }

    public function timeLogs()
    {
        return $this->hasMany(TaskTimeLog::class)->with('user')->latest('logged_at');
    }

    public function attachments()
    {
        return $this->hasMany(TaskAttachment::class)->latest();
    }
}
