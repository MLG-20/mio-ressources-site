<?php

namespace Tests\Unit\Models;

use App\Models\ForumMessage;
use App\Models\ForumSujet;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ForumMessageTest extends TestCase
{
    use RefreshDatabase;

    public function test_forum_message_model_exists(): void
    {
        $this->assertTrue(class_exists('App\\Models\\ForumMessage'));
    }
}
