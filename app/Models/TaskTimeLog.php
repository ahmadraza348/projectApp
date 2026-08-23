<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskTimeLog extends Model
{
    use HasFactory;
    protected $fillable = ['task_id', 'user_id', 'hours', 'logged_at', 'description'];

    protected $casts = [
        'logged_at' => 'date',
        'hours'     => 'float',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
