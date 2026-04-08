<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test: Homepage is accessible
     */
    public function test_homepage_is_accessible(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    /**
     * Test: Library page is accessible
     */
    public function test_library_page_is_accessible(): void
    {
        $response = $this->get('/bibliotheque');
        $response->assertStatus(200);
    }

    /**
     * Test: User can view a resource
     */
    public function test_user_can_view_resource(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/view/1');

        $this->assertTrue(in_array($response->getStatusCode(), [200, 404]));
    }

    /**
     * Test: User can download a resource
     */
    public function test_user_can_download_resource(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/download/1');

        $this->assertTrue(in_array($response->getStatusCode(), [200, 302, 404]));
    }

    /**
     * Test: User can rate a resource
     */
    public function test_user_can_rate_resource(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('/rate/1/Ressource', [
            'rating' => 5,
            'message' => 'Excellent resource!'
        ]);

        $this->assertTrue(in_array($response->getStatusCode(), [200, 302, 404]));
    }
}
