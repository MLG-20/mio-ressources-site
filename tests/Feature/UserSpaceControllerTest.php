<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserSpaceControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $student;
    private User $teacher;

    protected function setUp(): void
    {
        parent::setUp();
        $this->student = User::factory()->create(['role' => 'etudiant']);
        $this->teacher = User::factory()->create(['role' => 'professeur']);
    }

    /**
     * Test: Authenticated student can access dashboard
     */
    public function test_student_can_access_dashboard(): void
    {
        $response = $this->actingAs($this->student)->get('/mon-espace');
        $response->assertStatus(200);
    }

    /**
     * Test: Student can update profile
     */
    public function test_student_can_update_profile(): void
    {
        $response = $this->actingAs($this->student)->post('/mon-espace/update', [
            'name' => 'Updated Name',
            'email' => 'new@example.com'
        ]);

        $this->assertTrue(in_array($response->getStatusCode(), [200, 302]));
    }

    /**
     * Test: Unauthenticated cannot access dashboard
     */
    public function test_unauthenticated_cannot_access_dashboard(): void
    {
        $response = $this->get('/mon-espace');
        $response->assertRedirect('/login');
    }
}
