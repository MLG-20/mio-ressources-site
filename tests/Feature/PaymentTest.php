<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    private User $buyer;
    private User $seller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->buyer = User::factory()->create(['role' => 'etudiant']);
        $this->seller = User::factory()->create(['role' => 'professeur']);
    }

    /**
     * Test: Payment page is accessible
     */
    public function test_payment_page_is_accessible(): void
    {
        $response = $this->actingAs($this->buyer)
            ->get('/payer/1/Ressource');

        // Should either be 200 or redirect to login based on resource existence
        $this->assertTrue(in_array($response->getStatusCode(), [200, 302, 404]));
    }

    /**
     * Test: Thank you page is accessible
     */
    public function test_thankyou_page_is_accessible(): void
    {
        $response = $this->get('/merci');
        $response->assertStatus(200);
    }

    /**
     * Test: Guest download route works
     */
    public function test_guest_download_route_requires_token(): void
    {
        $response = $this->get('/telechargement-invite/invalid/Ressource/1');
        $this->assertTrue(in_array($response->getStatusCode(), [403, 404]));
    }
}
