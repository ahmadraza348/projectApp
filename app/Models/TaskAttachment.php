<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class TaskAttachment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'attachable_id',
        'attachable_type',
        'original_name',
        'file_name',
        'file_path',
        'disk',
        'mime_type',
        'extension',
        'size',
        'visibility',
    ];

    protected $casts = [
        'size' => 'integer',
    ];

    public function attachable()
    {
        return $this->morphTo();
    }

    // Convenience accessor: attachments are only ever created for tasks right
    // now, so this reads naturally as $attachment->task in controllers/views.
    public function task()
    {
        return $this->belongsTo(Task::class, 'attachable_id')->where('attachable_type', Task::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Human-readable size for the view, e.g. "240 KB"
    public function getSizeForHumansAttribute(): string
    {
        return $this->size >= 1048576
            ? round($this->size / 1048576, 1) . ' MB'
            : round($this->size / 1024) . ' KB';
    }

    public function getUrlAttribute(): string
    {
        return Storage::disk($this->disk ?? 'public')->url($this->file_path);
    }
}
