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
        Schema::create('financial_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // Le prof concerné
            $table->enum('type', ['CREDIT_VENTE', 'DEBIT_RETRAIT', 'COMMISSION_PLATEFORME']);
            $table->integer('amount'); // Montant (ex: 700)
            $table->string('reference')->unique(); // Référence unique (ex: TRX-12345)
            $table->string('description')->nullable(); // "Vente du cours X"
            $table->timestamps();
        });

        // On ajoute aussi le solde actuel sur l'utilisateur
        Schema::table('users', function (Blueprint $table) {
            $table->integer('wallet_balance')->default(0);
            $table->string('mobile_money_number')->nullable(); // Son numéro Wave
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financial_transactions');
    }
};
