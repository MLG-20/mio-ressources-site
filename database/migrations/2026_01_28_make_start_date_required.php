<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // D'abord, mettre à jour les cours existants sans start_date
        // Utiliser created_at + 7 jours comme date de début par défaut
        DB::table('private_lessons')
            ->whereNull('start_date')
            ->update([
                'start_date' => DB::raw("DATE_ADD(created_at, INTERVAL 7 DAY)")
            ]);

        // Ensuite, rendre le champ obligatoire
        Schema::table('private_lessons', function (Blueprint $table) {
            $table->dateTime('start_date')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('private_lessons', function (Blueprint $table) {
            $table->dateTime('start_date')->nullable()->change();
        });
    }
};
