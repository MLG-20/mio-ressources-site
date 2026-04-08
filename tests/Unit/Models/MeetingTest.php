<?php

namespace Tests\Unit\Models;

use App\Models\Meeting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MeetingTest extends TestCase
{
    use RefreshDatabase;

    public function test_meeting_model_exists(): void
    {
        $this->assertTrue(class_exists('App\\Models\\Meeting'));
    }
}
