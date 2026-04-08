<?php

namespace Tests\Unit\Models;

use App\Models\Semestre;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SemestreTest extends TestCase
{
    use RefreshDatabase;

    public function test_semestre_model_exists(): void
    {
        $this->assertTrue(class_exists('App\\Models\\Semestre'));
    }
}
