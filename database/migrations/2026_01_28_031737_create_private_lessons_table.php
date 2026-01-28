<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('private_lessons', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->text('description');
            $table->decimal('prix', 10, 2); // Prix du cours
            $table->integer('duree_minutes')->default(60); // Durée en minutes
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('matiere_id')->nullable()->constrained('matieres')->onDelete('set null');
            $table->json('disponibilites')->nullable(); // Créneaux disponibles
            $table->integer('places_max')->default(1); // Nombre max d'étudiants
            $table->enum('statut', ['actif', 'inactif', 'complet'])->default('actif');
            $table->timestamps();
        });

        // Table pivot pour les inscriptions aux cours particuliers
        Schema::create('private_lesson_enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('private_lesson_id')->constrained('private_lessons')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->dateTime('scheduled_at')->nullable(); // Date/heure réservée
            $table->string('payment_status')->default('pending'); // pending, paid, cancelled
            $table->string('payment_reference')->nullable();
            $table->decimal('amount_paid', 10, 2);
            $table->string('jitsi_room_name')->nullable(); // Salle Jitsi générée
            $table->enum('session_status', ['scheduled', 'active', 'completed', 'cancelled'])->default('scheduled');
            $table->dateTime('started_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->timestamps();

            $table->unique(['private_lesson_id', 'student_id', 'scheduled_at'], 'pl_enrollment_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('private_lesson_enrollments');
        Schema::dropIfExists('private_lessons');
    }
};
