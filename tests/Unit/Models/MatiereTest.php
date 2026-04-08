<?php

namespace Tests\Unit\Models;

use App\Models\Matiere;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MatiereTest extends TestCase
{
    use RefreshDatabase;

    public function test_matiere_model_exists(): void
    {
        $this->assertTrue(class_exists('App\\Models\\Matiere'));
    }
}

