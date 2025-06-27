<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\JsonResponse;
use ReflectionClass;

class ApiControllerTest extends TestCase
{
    protected ApiController $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new ApiController();
    }

    /**
     * Test success response method using reflection
     */
    public function test_success_response_method(): void
    {
        $data = ['test' => 'data'];
        $message = 'Test message';
        $status = 200;

        $reflection = new ReflectionClass($this->controller);
        $method = $reflection->getMethod('successResponse');
        $method->setAccessible(true);

        $response = $method->invoke($this->controller, $data, $message, $status);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals($status, $response->getStatusCode());

        $content = json_decode($response->getContent(), true);
        $this->assertTrue($content['success']);
        $this->assertEquals($message, $content['message']);
        $this->assertEquals($data, $content['data']);
    }

    /**
     * Test error response method using reflection
     */
    public function test_error_response_method(): void
    {
        $message = 'Test error';
        $status = 400;
        $errors = ['field' => ['error message']];

        $reflection = new ReflectionClass($this->controller);
        $method = $reflection->getMethod('errorResponse');
        $method->setAccessible(true);

        $response = $method->invoke($this->controller, $message, $status, $errors);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals($status, $response->getStatusCode());

        $content = json_decode($response->getContent(), true);
        $this->assertFalse($content['success']);
        $this->assertEquals($message, $content['message']);
        $this->assertEquals($errors, $content['errors']);
    }

    /**
     * Test info method
     */
    public function test_info_method(): void
    {
        $response = $this->controller->info();

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());

        $content = json_decode($response->getContent(), true);
        $this->assertTrue($content['success']);
        $this->assertEquals('API Information retrieved successfully', $content['message']);
        $this->assertArrayHasKey('api_version', $content['data']);
        $this->assertArrayHasKey('laravel_version', $content['data']);
        $this->assertArrayHasKey('environment', $content['data']);
        $this->assertArrayHasKey('debug', $content['data']);
    }

    /**
     * Test health method
     */
    public function test_health_method(): void
    {
        $response = $this->controller->health();

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());

        $content = json_decode($response->getContent(), true);
        $this->assertTrue($content['success']);
        $this->assertEquals('API is healthy', $content['message']);
        $this->assertArrayHasKey('status', $content['data']);
        $this->assertArrayHasKey('timestamp', $content['data']);
        $this->assertArrayHasKey('uptime', $content['data']);
        $this->assertEquals('healthy', $content['data']['status']);
    }
}
