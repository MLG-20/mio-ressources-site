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
        Schema::create('work_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nom du groupe
            $table->text('description')->nullable(); // Description du projet
            $table->string('room_name')->unique(); // Nom de la salle Jitsi permanente
            $table->foreignId('creator_id')->constrained('users')->onDelete('cascade'); // Créateur du groupe
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_groups');
    }
};
