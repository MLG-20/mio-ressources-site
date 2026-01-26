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
    // 1. Catégories (Général, L1, L2...)
    Schema::create('forum_categories', function (Blueprint $table) {
        $table->id();
        $table->string('nom');
        $table->text('description')->nullable();
        $table->integer('ordre')->default(0);
        $table->timestamps();
    });

    // 2. Sujets (Les questions posées)
    Schema::create('forum_sujets', function (Blueprint $table) {
        $table->id();
        $table->string('titre');
        $table->foreignId('forum_category_id')->constrained()->cascadeOnDelete();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // L'auteur
        $table->integer('nombre_vues')->default(0);
        $table->boolean('est_epingle')->default(false);
        $table->timestamps();
    });

    // 3. Messages (Les réponses dans les sujets)
    Schema::create('forum_messages', function (Blueprint $table) {
        $table->id();
        $table->foreignId('forum_sujet_id')->constrained()->cascadeOnDelete();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->text('contenu');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forum_tables');
    }
};
