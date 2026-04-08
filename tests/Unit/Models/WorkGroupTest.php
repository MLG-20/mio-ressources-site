<?php

namespace Tests\Unit\Models;

use App\Models\WorkGroup;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WorkGroupTest extends TestCase
{
    use RefreshDatabase;

    public function test_workgroup_model_exists(): void
    {
        $this->assertTrue(class_exists('App\\Models\\WorkGroup'));
    }
}
