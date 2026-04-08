<?php

namespace Tests\Unit\Models;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test: User can be created
     */
    public function test_user_can_be_created(): void
    {
        $user = User::factory()->create();
        $this->assertNotNull($user->id);
    }

    /**
     * Test: User has email verified at
     */
    public function test_user_has_email_verified_at(): void
    {
        $user = User::factory()->create();
        $this->assertNotNull($user->email_verified_at);
    }

    /**
     * Test: User isTeacher method works
     */
    public function test_is_teacher_method(): void
    {
        $teacher = User::factory()->create(['user_type' => 'teacher']);
        $this->assertTrue($teacher->isTeacher());
    }

    /**
     * Test: Super admin can be detected
     */
    public function test_super_admin_detection(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@mio-ressources.com',
            'role' => 'admin'
        ]);
        $this->assertTrue($admin->isSuperAdmin());
    }

    /**
     * Test: Blocked user flag
     */
    public function test_blocked_user_flag(): void
    {
        $user = User::factory()->create(['is_blocked' => true]);
        $this->assertTrue($user->is_blocked);
    }
}
