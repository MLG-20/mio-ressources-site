<?php

namespace Tests\Unit\Models;

use App\Models\DownloadHistory;
use App\Models\User;
use App\Models\Ressource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DownloadHistoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_download_history_model_exists(): void
    {
        $this->assertTrue(class_exists('App\\Models\\DownloadHistory'));
    }
}
