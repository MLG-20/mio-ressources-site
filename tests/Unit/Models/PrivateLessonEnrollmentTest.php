<?php

namespace Tests\Unit\Models;

use App\Models\PrivateLessonEnrollment;
use App\Models\PrivateLesson;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PrivateLessonEnrollmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_enrollment_model_exists(): void
    {
        $this->assertTrue(class_exists('App\\Models\\PrivateLessonEnrollment'));
    }
}
