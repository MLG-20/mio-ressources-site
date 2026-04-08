<?php

namespace Tests\Unit\Models;

use App\Models\Slider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SliderTest extends TestCase
{
    use RefreshDatabase;

    public function test_slider_model_exists(): void
    {
        $this->assertTrue(class_exists('App\\Models\\Slider'));
    }
}
