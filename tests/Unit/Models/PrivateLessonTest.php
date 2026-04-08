<?php

namespace Tests\Unit\Models;

use App\Models\PrivateLesson;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PrivateLessonTest extends TestCase
{
    use RefreshDatabase;

    public function test_private_lesson_model_exists(): void
    {
        $this->assertTrue(class_exists('App\\Models\\PrivateLesson'));
    }
}
