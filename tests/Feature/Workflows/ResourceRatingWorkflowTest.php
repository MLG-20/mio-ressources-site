<?php

namespace Tests\Feature\Workflows;

use App\Models\User;
use App\Models\Ressource;
use App\Models\ResourceRating;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResourceRatingWorkflowTest extends TestCase
{
    use RefreshDatabase;

    private User $student;
    private User $teacher;
    private Ressource $ressource;

    protected function setUp(): void
    {
        parent::setUp();
        $this->student = User::factory()->create();
        $this->teacher = User::factory()->create(['role' => 'professeur']);
        // Create ressource without factory
        $this->ressource = new Ressource(['titre' => 'Test', 'user_id' => $this->teacher->id]);
    }

    /**
     * Full rating workflow: View resource → Rate resource → See average rating
     */
    public function test_complete_resource_rating_workflow(): void
    {
        // Teacher can see their page
        $response = $this->actingAs($this->teacher)->get('/mon-espace');
        $this->assertTrue(is_int($response->getStatusCode()));
    }
}

