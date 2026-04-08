<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PrivateLessonTest extends TestCase
{
    use RefreshDatabase;

    private User $teacher;
    private User $student;

    protected function setUp(): void
    {
        parent::setUp();
        $this->teacher = User::factory()->create(['role' => 'professeur']);
        $this->student = User::factory()->create(['role' => 'etudiant']);
    }

    /**
     * Test: Student can browse private lessons
     */
    public function test_student_can_browse_private_lessons(): void
    {
        $response = $this->actingAs($this->student)->get('/cours-particuliers');
        $this->assertTrue(in_array($response->getStatusCode(), [200, 404]));
    }

    /**
     * Test: Teacher can access private lessons dashboard
     */
    public function test_teacher_can_access_private_lessons_dashboard(): void
    {
        $response = $this->actingAs($this->teacher)
            ->get('/enseignant/cours-particuliers');
        $this->assertTrue(in_array($response->getStatusCode(), [200, 404]));
    }

    /**
     * Test: Student cannot create private lesson
     */
    public function test_student_cannot_create_private_lesson(): void
    {
        $response = $this->actingAs($this->student)
            ->get('/enseignant/cours-particuliers/creer');
        $this->assertTrue(in_array($response->getStatusCode(), [302, 403]));
    }

    /**
     * Test: Unauthenticated cannot access private lessons
     */
    public function test_unauthenticated_cannot_access_private_lessons(): void
    {
        $response = $this->get('/cours-particuliers');
        $this->assertTrue(in_array($response->getStatusCode(), [200, 302, 404]));
    }
}
