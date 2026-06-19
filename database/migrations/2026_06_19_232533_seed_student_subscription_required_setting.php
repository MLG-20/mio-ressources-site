<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Crée l'interrupteur global "Exiger l'abonnement étudiant".
     *
     * Par défaut OFF (is_enabled = false) : pendant la phase sandbox Paytech,
     * aucun étudiant n'est bloqué après ses 3 mois gratuits. L'admin l'activera
     * (Réglages) quand les vraies clés API seront en place.
     *
     * Idempotent : ne réinitialise pas le choix de l'admin si la ligne existe déjà.
     */
    public function up(): void
    {
        if (! DB::table('settings')->where('key', 'student_subscription_required')->exists()) {
            DB::table('settings')->insert([
                'key'        => 'student_subscription_required',
                'label'      => "Exiger l'abonnement étudiant",
                'value'      => '0',
                'is_enabled' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        DB::table('settings')->where('key', 'student_subscription_required')->delete();
    }
};
