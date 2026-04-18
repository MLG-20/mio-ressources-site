<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('settings')->where('key', 'cv_button_enabled')->delete();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('settings')->insert([
            'key' => 'cv_button_enabled',
            'label' => 'Bouton CV - Activé',
            'value' => '1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
};
