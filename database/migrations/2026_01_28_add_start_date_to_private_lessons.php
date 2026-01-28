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
        Schema::table('private_lessons', function (Blueprint $table) {
            $table->dateTime('start_date')->nullable()->comment('Date et heure de début du cours');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('private_lessons', function (Blueprint $table) {
            $table->dropColumn('start_date');
        });
    }
};
