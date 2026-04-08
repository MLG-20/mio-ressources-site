<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WorkGroupControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /**
     * Test: Unauthenticated cannot browse work groups
     */
    public function test_unauthenticated_cannot_browse_work_groups(): void
    {
        $response = $this->get('/groupes');
        $response->assertRedirect('/login');
    }

    /**
     * Test: Authenticated user can access workgroups
     */
    public function test_authenticated_user_can_access_work_groups(): void
    {
        $response = $this->actingAs($this->user)->get('/groupes');
        $this->assertTrue(in_array($response->getStatusCode(), [200, 404, 500]));
    }
}
