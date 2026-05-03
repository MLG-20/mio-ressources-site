<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('visits', function (Blueprint $table) {
            $table->index('visit_date');
            $table->index(['page_visited', 'visit_date']);
        });

        Schema::table('ressources', function (Blueprint $table) {
            $table->index('is_premium');
            $table->index('created_at');
        });

        Schema::table('purchases', function (Blueprint $table) {
            $table->index('created_at');
            $table->index('item_type');
        });

        Schema::table('download_histories', function (Blueprint $table) {
            $table->index('downloaded_at');
        });
    }

    public function down(): void
    {
        Schema::table('visits', function (Blueprint $table) {
            $table->dropIndex(['visit_date']);
            $table->dropIndex(['page_visited', 'visit_date']);
        });

        Schema::table('ressources', function (Blueprint $table) {
            $table->dropIndex(['is_premium']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('purchases', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
            $table->dropIndex(['item_type']);
        });

        Schema::table('download_histories', function (Blueprint $table) {
            $table->dropIndex(['downloaded_at']);
        });
    }
};
