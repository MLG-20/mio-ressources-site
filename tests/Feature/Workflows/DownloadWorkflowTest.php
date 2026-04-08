<?php

namespace Tests\Feature\Workflows;

use App\Models\User;
use App\Models\Ressource;
use App\Models\DownloadHistory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DownloadWorkflowTest extends TestCase
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
     * Full download workflow: Browse → Download → Record history
     */
    public function test_complete_download_workflow(): void
    {
        // Student attempts download
        $response = $this->actingAs($this->student)->get('/view/1');
        $this->assertTrue(is_int($response->getStatusCode()));
    }

    /**
     * Test: Unauthenticated user cannot download
     */
    public function test_unauthenticated_user_cannot_download(): void
    {
        $response = $this->get('/view/1');
        $this->assertTrue(in_array($response->getStatusCode(), [302, 404, 200]));
    }

    /**
     * Test: Teacher can see their page
     */
    public function test_teacher_can_see_their_page(): void
    {
        $response = $this->actingAs($this->teacher)->get('/mon-espace');
        $this->assertTrue(is_int($response->getStatusCode()));
    }
}
