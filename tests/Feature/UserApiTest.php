<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class UserApiTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Test getting all users when database is empty
     */
    public function test_can_get_empty_users_list(): void
    {
        $response = $this->get('/api/v1/users');

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'Users retrieved successfully',
                    'data' => []
                ]);
    }

    /**
     * Test getting all users with data
     */
    public function test_can_get_users_list(): void
    {
        // Crear usuarios de prueba
        User::factory()->count(3)->create();

        $response = $this->get('/api/v1/users');

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'Users retrieved successfully',
                ])
                ->assertJsonCount(3, 'data')
                ->assertJsonStructure([
                    'success',
                    'message',
                    'data' => [
                        '*' => [
                            'id',
                            'name',
                            'email',
                            'created_at'
                        ]
                    ]
                ]);
    }

    /**
     * Test creating a new user
     */
    public function test_can_create_user(): void
    {
        $userData = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'password123'
        ];

        $response = $this->postJson(route('users.store', $userData));

        $response->assertStatus(201)
                ->assertJson([
                    'success' => true,
                    'message' => 'User created successfully',
                ])
                ->assertJsonStructure([
                    'success',
                    'message',
                    'data' => [
                        'id',
                        'name',
                        'email',
                        'created_at'
                    ]
                ]);

        // Verificar que el usuario fue creado en la base de datos
        $this->assertDatabaseHas('users', [
            'name' => $userData['name'],
            'email' => $userData['email']
        ]);
    }

    /**
     * Test creating user with invalid data
     */
    public function test_cannot_create_user_with_invalid_data(): void
    {
        $userData = [
            'name' => '', // Nombre vacío
            'email' => 'invalid-email', // Email inválido
            'password' => '123' // Password muy corto
        ];

        $response = $this->postJson(route('users.store', $userData));

        $response->assertStatus(422)
                ->assertJson([
                    'success' => false,
                    'message' => 'Validation failed',
                ])
                ->assertJsonStructure([
                    'success',
                    'message',
                    'errors'
                ]);
    }

    /**
     * Test getting a specific user
     */
    public function test_can_get_specific_user(): void
    {
        $user = User::factory()->create();

        $response = $this->get("/api/v1/users/{$user->id}");

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'User retrieved successfully',
                    'data' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email
                    ]
                ]);
    }

    /**
     * Test getting non-existent user
     */
    public function test_cannot_get_nonexistent_user(): void
    {
        $response = $this->get('/api/v1/users/999');

        $response->assertStatus(404)
                ->assertJson([
                    'success' => false,
                    'message' => 'User not found'
                ]);
    }

    /**
     * Test updating a user
     */
    public function test_can_update_user(): void
    {
        $user = User::factory()->create();

        $updateData = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com'
        ];

        $response = $this->putJson(route('users.update', $user->id), $updateData);

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'User updated successfully',
                    'data' => [
                        'id' => $user->id,
                        'name' => 'Updated Name',
                        'email' => 'updated@example.com'
                    ]
                ]);

        // Verificar que el usuario fue actualizado en la base de datos
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'email' => 'updated@example.com'
        ]);
    }

    /**
     * Test updating non-existent user
     */
    public function test_cannot_update_nonexistent_user(): void
    {
        $updateData = [
            'name' => 'Updated Name'
        ];

        $response = $this->putJson(route('users.update', 999), $updateData);

        $response->assertStatus(404)
                ->assertJson([
                    'success' => false,
                    'message' => 'User not found'
                ]);
    }

    /**
     * Test deleting a user
     */
    public function test_can_delete_user(): void
    {
        $user = User::factory()->create();

        $response = $this->deleteJson(route('users.destroy', $user->id));

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'User deleted successfully'
                ]);

        // Verificar que el usuario fue eliminado de la base de datos
        $this->assertDatabaseMissing('users', [
            'id' => $user->id
        ]);
    }

    /**
     * Test deleting non-existent user
     */
    public function test_cannot_delete_nonexistent_user(): void
    {
        $response = $this->deleteJson(route('users.destroy', 999));

        $response->assertStatus(404)
                ->assertJson([
                    'success' => false,
                    'message' => 'User not found'
                ]);
    }

    /**
     * Test duplicate email validation
     */
    public function test_cannot_create_user_with_duplicate_email(): void
    {
        $user = User::factory()->create();

        $userData = [
            'name' => 'New User',
            'email' => $user->email, // Email duplicado
            'password' => 'password123'
        ];

        $response = $this->postJson('/api/v1/users', $userData);

        $response->assertStatus(422)
                ->assertJson([
                    'success' => false,
                    'message' => 'Validation failed'
                ])
                ->assertJsonPath('errors.email', ['The email has already been taken.']);
    }
}
