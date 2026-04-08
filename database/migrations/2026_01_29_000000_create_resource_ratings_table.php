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
    Schema::create('resource_ratings', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        
        // On rend l'ID de ressource optionnel
        $table->foreignId('ressource_id')->nullable()->constrained()->cascadeOnDelete();
        
        // On ajoute l'ID de publication (livre) optionnel
        $table->foreignId('publication_id')->nullable()->constrained()->cascadeOnDelete();
        
        $table->integer('stars'); // Note de 1 à 5
        $table->text('comment')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resource_ratings');
    }
};
