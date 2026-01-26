<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
     public function up()
    {
        Schema::table('purchases', function (Blueprint $table) {
            // On rend ressource_id optionnel (nullable)
            $table->unsignedBigInteger('ressource_id')->nullable()->change();
            
            // On ajoute la colonne pour les publications
            $table->foreignId('publication_id')->nullable()->constrained()->cascadeOnDelete();
            
            // Pour savoir ce qu'on a acheté (Ressource ou Publication)
            $table->string('item_type')->default('Ressource'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            //
        });
    }
};
