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
        Schema::create('private_lesson_reminders_sent', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->constrained('private_lessons')->onDelete('cascade');
            $table->string('reminder_type'); // fifteen_minutes, one_hour, etc.
            $table->dateTime('sent_at')->nullable();
            $table->timestamps();

            $table->unique(['lesson_id', 'reminder_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('private_lesson_reminders_sent');
    }
};
