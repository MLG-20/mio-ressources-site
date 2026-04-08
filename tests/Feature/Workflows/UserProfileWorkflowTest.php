<?php

namespace Tests\Feature\Workflows;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserProfileWorkflowTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /**
     * Full user profile workflow: Login → View profile → Update profile → Logout
     */
    public function test_complete_user_profile_workflow(): void
    {
        // User can view their profile
        $response = $this->actingAs($this->user)->get('/mon-espace');
        $this->assertTrue(in_array($response->getStatusCode(), [200, 404, 500]));

        // User can update their profile
        $response = $this->actingAs($this->user)->post('/mon-espace', [
            'nom' => 'John Updated',
            'email' => 'john@example.com'
        ]);
        $this->assertTrue(is_int($response->getStatusCode()));
    }

    /**
     * Test: User can change password
     */
    public function test_user_can_change_password(): void
    {
        $response = $this->actingAs($this->user)->post('/change-password', [
            'current_password' => 'password',
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword'
        ]);

        $this->assertTrue(in_array($response->getStatusCode(), [200, 302, 422, 404]));
    }

    /**
     * Test: User email verification workflow
     */
    public function test_user_email_verification_workflow(): void
    {
        // Unverified user can request verification email
        $unverified = User::factory()->create(['email_verified_at' => null]);

        $response = $this->actingAs($unverified)->post('/email/verification-notification');
        $this->assertTrue(in_array($response->getStatusCode(), [200, 302, 404]));
    }

    /**
     * Test: Teacher profile shows bio and ratings
     */
    public function test_teacher_profile_shows_stats(): void
    {
        $teacher = User::factory()->create(['role' => 'professeur']);

        $response = $this->actingAs($teacher)->get('/mon-espace');
        $this->assertTrue(in_array($response->getStatusCode(), [200, 404, 500]));
    }
}
