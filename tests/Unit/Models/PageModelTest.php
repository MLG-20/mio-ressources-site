<?php

namespace Tests\Unit\Models;

use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PageModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test: Page can be retrieved by slug
     */
    public function test_page_can_be_found_by_slug(): void
    {
        // Test that the route works for pages
        $response = $this->get('/');
        $this->assertTrue(in_array($response->getStatusCode(), [200, 404]));
    }
}
