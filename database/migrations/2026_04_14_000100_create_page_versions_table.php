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
        Schema::create('page_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained('pages')->onDelete('cascade');
            $table->longText('contenu_ancien')->nullable();
            $table->longText('contenu_nouveau');
            $table->string('titre_ancien')->nullable();
            $table->string('titre_nouveau');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('description_changement')->nullable();
            $table->timestamps();

            // Index pour les recherches rapides
            $table->index('page_id');
            $table->index('user_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_versions');
    }
};
