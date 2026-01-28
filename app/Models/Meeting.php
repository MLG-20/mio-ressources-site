<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Meeting extends Model
{
    protected $fillable = ['user_id', 'title', 'room_name', 'scheduled_at', 'status', 'started_at', 'closed_at'];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'started_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Étudiants inscrits à ce cours
    public function enrolledStudents(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'meeting_enrollments')
            ->withTimestamps();
    }
}
