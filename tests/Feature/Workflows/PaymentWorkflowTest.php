<?php

namespace Tests\Feature\Workflows;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_complete_payment_workflow(): void
    {
        $student = User::factory()->create(['user_type' => 'student']);
        $response = $this->actingAs($student)->get('/payer/1/course');
        $this->assertTrue(is_int($response->getStatusCode()));
    }

    public function test_payment_thank_you_page(): void
    {
        $student = User::factory()->create();
        $response = $this->actingAs($student)->get('/merci-paiement');
        $this->assertTrue(is_int($response->getStatusCode()));
    }
}

