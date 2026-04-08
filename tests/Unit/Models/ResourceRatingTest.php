<?php

namespace Tests\Unit\Models;

use App\Models\ResourceRating;
use App\Models\User;
use App\Models\Ressource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResourceRatingTest extends TestCase
{
    use RefreshDatabase;

    public function test_resource_rating_model_exists(): void
    {
        $this->assertTrue(class_exists('App\\Models\\ResourceRating'));
    }
}
