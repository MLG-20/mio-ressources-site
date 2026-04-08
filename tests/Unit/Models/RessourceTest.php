<?php

namespace Tests\Unit\Models;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RessourceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test: User can be a teacher
     */
    public function test_teacher_can_be_created(): void
    {
        $teacher = User::factory()->create(['role' => 'professeur']);
        $this->assertEquals('professeur', $teacher->role);
    }

    /**
     * Test: User relationships work
     */
    public function test_user_has_relationships(): void
    {
        $user = User::factory()->create();

        $this->assertIsInt($user->enrollments()->count());
        $this->assertIsInt($user->workGroups()->count());
    }
}
