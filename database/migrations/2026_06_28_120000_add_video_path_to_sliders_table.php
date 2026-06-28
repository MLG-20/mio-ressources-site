<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sliders', function (Blueprint $table) {
            // Vidéo optionnelle (mp4 muet en boucle). Si présente, elle remplace
            // l'image dans le hero ; l'image sert alors de poster / fallback.
            $table->string('video_path')->nullable()->after('image_path');
        });
    }

    public function down(): void
    {
        Schema::table('sliders', function (Blueprint $table) {
            $table->dropColumn('video_path');
        });
    }
};
