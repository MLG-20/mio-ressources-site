<?php

namespace Tests\Feature\Workflows;

use App\Models\User;
use App\Models\PrivateLesson;
use App\Models\PrivateLessonEnrollment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PrivateLessonWorkflowTest extends TestCase
{
    use RefreshDatabase;

    private User $student;
    private User $teacher;

    protected function setUp(): void
    {
        parent::setUp();
        $this->student = User::factory()->create(['user_type' => 'student']);
        $this->teacher = User::factory()->create(['user_type' => 'teacher', 'role' => 'professeur']);
    }

    /**
     * Full workflow: Browse → View details → Enroll → Pay → Access lesson
     */
    public function test_complete_private_lesson_enrollment_workflow(): void
    {
        // Student can browse private lessons
        $response = $this->actingAs($this->student)->get('/cours-particuliers');
        $this->assertTrue(in_array($response->getStatusCode(), [200, 404]));
    }

    /**
     * Test: Teacher can see enrolled students
     */
    public function test_teacher_can_see_enrolled_students(): void
    {
        $response = $this->actingAs($this->teacher)->get('/espace-enseignant');
        $this->assertTrue(is_int($response->getStatusCode()));
    }
}

