<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UsersTest extends TestCase
{
    use RefreshDatabase;

    public string $email = "test@example.com";
    public string $name = "Test User";
    public string $login = "test";
    public string $password = "abc123";

    private function login(): string
    {
        // Run the DatabaseSeeder...
        $this->seed();

        return $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'abc123'
        ])->json()['access_token'];
    }

    public function test_user_auth_cam_list_all_users()
    {
        $token = $this->login();

        $this->withHeader('Authorization', 'Bearer ' . $token)->getJson('api/users')
            ->assertOk()
            ->assertJsonStructure([
                'users' => [
                    '*' => [
                        'id',
                        'login',
                        'name',
                        'email',
                        'status',
                    ]
                ]
            ]);
    }

    public function test_user_auth_cam_create_outher_users()
    {
        $token = $this->login();

        $this->withHeader('Authorization', 'Bearer ' . $token)->postJson('api/users', [
            'login' => 'test2',
            'name' => 'Test User 2',
            'status' => 'active', // 'active', 'inactive
            'email' => 'teste2@example.com',
            'password' => 'abc123',
        ])->assertNoContent();

        $this->assertDatabaseHas('users', [
            'login' => 'test2',
            'name' => 'Test User 2',
            'status' => 'active', // 'active', 'inactive
            'email' => 'teste2@example.com',
        ]);
    }

    public function test_it_can_update_a_user()
    {
        $user = User::factory()->create();
        $newUser = [
            'name' => 'New Name',
            'email' => 'new.email@example.com'
        ];
        $token = $this->login();
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->putJson("/api/users/{$user->id}", $newUser);

        $response->assertNoContent();
        $this->assertDatabaseHas('users', $newUser);
    }

    public function test_it_can_delete_a_user()
    {
        $user = User::factory()->create();

        $token = $this->login();

        $response =  $this->withHeader('Authorization', 'Bearer ' . $token)->deleteJson("/api/users/{$user->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
