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
        Schema::table('private_lessons', function (Blueprint $table) {
            // Ajouter la colonne type (payant/tutoriel)
            $table->enum('type', ['payant', 'tutoriel'])->default('payant')->after('statut');
            // La colonne is_free n'est pas nécessaire puisque type='tutoriel' signifie gratuit
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('private_lessons', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
