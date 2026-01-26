<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
    Schema::create('ressources', function (Blueprint $table) {
        $table->id();
        $table->string('titre');
        $table->enum('type', ['Cours', 'TD', 'Vidéo', 'Autre']);
        $table->string('file_path');
        $table->foreignId('matiere_id')->constrained()->cascadeOnDelete();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ressources');
    }
};
