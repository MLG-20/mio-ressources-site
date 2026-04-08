<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BackofficeTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $student;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create([
            'email' => env('ADMIN_EMAIL', 'admin@mio-ressources.com'),
            'role' => 'admin'
        ]);
        $this->student = User::factory()->create(['role' => 'etudiant']);
    }

    /**
     * Test: Only super admin can access backoffice
     */
    public function test_super_admin_can_access_backoffice(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin');
        $this->assertTrue(in_array($response->getStatusCode(), [200, 302]));
    }

    /**
     * Test: Student cannot access backoffice
     */
    public function test_student_cannot_access_backoffice(): void
    {
        $response = $this->actingAs($this->student)->get('/admin');
        $this->assertTrue(in_array($response->getStatusCode(), [302, 403]));
    }

    /**
     * Test: Unauthenticated user cannot access backoffice
     */
    public function test_unauthenticated_cannot_access_backoffice(): void
    {
        $response = $this->get('/admin');
        $response->assertRedirect();
    }
}
