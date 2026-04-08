<?php

namespace Tests\Unit\Models;

use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_review_model_exists(): void
    {
        $this->assertTrue(class_exists('App\\Models\\Review'));
    }
}
