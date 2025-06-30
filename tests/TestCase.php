<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Configurar headers por defecto para API
        $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ]);

        // Asegurar que estamos usando la base de datos de testing correcta
        // if (app()->environment('testing')) {
        //     config(['database.default' => 'mysql_testing']);
        // }
    }
}
