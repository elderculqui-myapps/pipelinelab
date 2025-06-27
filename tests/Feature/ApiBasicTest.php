<?php

namespace Tests\Feature;

use Tests\TestCase;

class ApiBasicTest extends TestCase
{
    /**
     * Test API health endpoint
     */
    public function test_api_health_endpoint(): void
    {
        $response = $this->get('/api/health');

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'API is healthy',
                ])
                ->assertJsonStructure([
                    'success',
                    'message',
                    'data' => [
                        'status',
                        'timestamp',
                        'uptime'
                    ]
                ]);
    }

    /**
     * Test API info endpoint
     */
    public function test_api_info_endpoint(): void
    {
        $response = $this->get('/api/info');

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'API Information retrieved successfully',
                ])
                ->assertJsonStructure([
                    'success',
                    'message',
                    'data' => [
                        'api_version',
                        'laravel_version',
                        'environment',
                        'debug'
                    ]
                ]);
    }

    /**
     * Test API returns JSON for invalid routes
     */
    public function test_api_returns_json_for_invalid_routes(): void
    {
        $response = $this->get('/api/nonexistent');

        $response->assertStatus(404);
        // Verificar que la respuesta es JSON
        $this->assertJson($response->getContent());
    }

    /**
     * Test example endpoint
     */
    public function test_api_v1_example_endpoint(): void
    {
        $response = $this->get('/api/v1/example');

        $response->assertStatus(200)
                ->assertJson([
                    'message' => 'This is an example API endpoint',
                    'version' => 'v1'
                ]);
    }
}
