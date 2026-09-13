<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Project extends Model
{
    use HasFactory, SoftDeletes;

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

    // The migration has 'deadline' (not 'end_date') and 'completed_at' is a datetime.
    // Casting these means ->format() works directly in Blade without extra parsing.
    protected $casts = [
        'start_date'   => 'date',
        'deadline'     => 'date',
        'completed_at' => 'datetime',
        'budget'       => 'decimal:2',
        'progress'     => 'integer',
        'is_billable'  => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (Project $project) {
            if (empty($project->slug)) {
                $project->slug = static::generateUniqueSlug($project->name);
            }
        });
    }

    protected static function generateUniqueSlug(string $name): string
    {
        $base = Str::slug($name) ?: 'project';
        $slug = $base;
        $i = 1;

        while (static::withTrashed()->where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'project_manager_id');
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
