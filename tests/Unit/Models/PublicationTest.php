<?php

namespace Tests\Unit\Models;

use App\Models\Publication;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicationTest extends TestCase
{
    use RefreshDatabase;

    public function test_publication_model_exists(): void
    {
        $this->assertTrue(class_exists('App\\Models\\Publication'));
    }
}

