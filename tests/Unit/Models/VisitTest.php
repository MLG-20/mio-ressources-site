<?php

namespace Tests\Unit\Models;

use App\Models\Visit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VisitTest extends TestCase
{
    use RefreshDatabase;

    public function test_visit_model_exists(): void
    {
        $this->assertTrue(class_exists('App\\Models\\Visit'));
    }
}
