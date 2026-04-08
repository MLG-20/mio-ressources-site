<?php

namespace Tests\Unit\Models;

use App\Models\ForumSujet;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ForumSujetTest extends TestCase
{
    use RefreshDatabase;

    public function test_forum_subject_model_exists(): void
    {
        $this->assertTrue(class_exists('App\\Models\\ForumSujet'));
    }
}
