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
         Schema::create('publications', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->text('description')->nullable();
            $table->enum('type', ['Livre', 'Mémoire', 'Recherche', 'Article']);
            $table->string('file_path');
            $table->string('cover_image')->nullable(); // Image de couverture pour les livres
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // L'auteur (le prof)
            $table->boolean('is_verified')->default(false); // Pour que l'admin valide avant publication
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('publications');
    }
};
