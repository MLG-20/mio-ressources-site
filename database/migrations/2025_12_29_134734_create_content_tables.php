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
    // 1. Slider d'accueil
    Schema::create('sliders', function (Blueprint $table) {
        $table->id();
        $table->string('image_path');
        $table->string('titre')->nullable();
        $table->text('description')->nullable();
        $table->integer('ordre')->default(0);
        $table->timestamps();
    });

    // 2. Pages statiques (À propos, Club MIO)
    Schema::create('pages', function (Blueprint $table) {
        $table->id();
        $table->string('titre');
        $table->string('slug')->unique(); // ex: a-propos
        $table->text('contenu');
        $table->timestamps();
    });

    // 3. Paramètres globaux (Email contact, réseaux sociaux, etc.)
    Schema::create('settings', function (Blueprint $table) {
        $table->id();
        $table->string('key')->unique();
        $table->text('value')->nullable();
        $table->string('label'); // Nom lisible
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_tables');
    }
};
