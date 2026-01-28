<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrivateLesson extends Model
{
    protected $fillable = [
        'titre',
        'description',
        'prix',
        'duree_minutes',
        'teacher_id',
        'matiere_id',
        'disponibilites',
        'places_max',
        'statut',
        'type',  // payant ou tutoriel
        'start_date',  // Date/heure fixe du cours
    ];

    protected $casts = [
        'prix' => 'decimal:2',
        'disponibilites' => 'array',
        'duree_minutes' => 'integer',
        'places_max' => 'integer',
        'start_date' => 'datetime',
    ];

    /**
     * Relation avec le professeur
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Relation avec la matière
     */
    public function matiere()
    {
        return $this->belongsTo(Matiere::class);
    }

    /**
     * Relation avec les inscriptions
     */
    public function enrollments()
    {
        return $this->hasMany(PrivateLessonEnrollment::class);
    }

    /**
     * Scope pour les cours actifs
     */
    public function scopeActive($query)
    {
        return $query->where('statut', 'actif');
    }

    /**
     * Scope pour les cours disponibles
     */
    public function scopeAvailable($query)
    {
        return $query->whereIn('statut', ['actif'])->where('places_max', '>', function($q) {
            $q->selectRaw('count(*)')->from('private_lesson_enrollments')
                ->whereColumn('private_lesson_enrollments.private_lesson_id', 'private_lessons.id')
                ->where('payment_status', 'paid');
        });
    }
}
