<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Configurar headers por defecto para API
        $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ]);
    }
}
