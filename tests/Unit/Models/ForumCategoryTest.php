<?php

namespace Tests\Unit\Models;

use App\Models\ForumCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ForumCategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_forum_category_model_exists(): void
    {
        $this->assertTrue(class_exists('App\\Models\\ForumCategory'));
    }
}

