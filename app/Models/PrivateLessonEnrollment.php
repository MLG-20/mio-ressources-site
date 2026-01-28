<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PrivateLessonEnrollment extends Model
{
    protected $fillable = [
        'private_lesson_id',
        'student_id',
        'scheduled_at',
        'payment_status',
        'payment_reference',
        'amount_paid',
        'jitsi_room_name',
        'session_status',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'amount_paid' => 'decimal:2',
        'scheduled_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Boot du modèle pour générer automatiquement le nom de la salle Jitsi
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($enrollment) {
            if (empty($enrollment->jitsi_room_name)) {
                $enrollment->jitsi_room_name = 'PL-' . Str::upper(Str::random(8));
            }
        });
    }

    /**
     * Relation avec le cours particulier
     */
    public function privateLesson()
    {
        return $this->belongsTo(PrivateLesson::class);
    }

    /**
     * Relation avec l'étudiant
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
