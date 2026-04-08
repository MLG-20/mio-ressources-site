<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeacherSpaceControllerTest extends TestCase
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
     * Test: Teacher can access dashboard
     */
    public function test_teacher_can_access_dashboard(): void
    {
        $response = $this->actingAs($this->teacher)->get('/espace-enseignant');
        $response->assertStatus(200);
    }

    /**
     * Test: Student cannot access teacher dashboard
     */
    public function test_student_cannot_access_teacher_dashboard(): void
    {
        $response = $this->actingAs($this->student)->get('/espace-enseignant');

        $this->assertTrue(in_array($response->getStatusCode(), [302, 403]));
    }

    /**
     * Test: Unauthenticated cannot access teacher dashboard
     */
    public function test_unauthenticated_cannot_access_teacher_dashboard(): void
    {
        $response = $this->get('/espace-enseignant');
        $response->assertRedirect('/login');
    }

    /**
     * Test: Teacher can update profile
     */
    public function test_teacher_can_update_profile(): void
    {
        $response = $this->actingAs($this->teacher)->post('/espace-enseignant/profil/update', [
            'name' => 'Prof Updated',
            'specialty' => 'Mathematics'
        ]);

        $this->assertTrue(in_array($response->getStatusCode(), [200, 302]));
    }
}
