<?php

namespace Tests\Unit\Models;

use App\Models\FinancialTransaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FinancialTransactionTest extends TestCase
{
    use RefreshDatabase;

    public function test_financial_transaction_model_exists(): void
    {
        $this->assertTrue(class_exists('App\\Models\\FinancialTransaction'));
    }
}
