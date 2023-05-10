<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Support\Facades\DB;

class AuthenticateTest extends TestCase
{
    use RefreshDatabase;

    public string $email = "test@example.com";
    public string $name = "Test User";
    public string $login = "test";
    public string $password = "abc123";


    /**
     * Testa se o usuario pode fazer login na api
     */
    public function test_Anonymous_user_can_login(): void
    {
        // Run the DatabaseSeeder...
        $this->seed();

        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'abc123'
        ]);

        $response->assertOk();
        $response->assertJsonStructure([
            'access_token',
            'token_type',
            'expires_in'
        ]);
    }

    /**
     * Testa se o usuario pode se registar
     */
    public function test_Anonymous_user_can_register(): void
    {
        $dataWillSend = [
            'login' => $this->login,
            'name' => $this->name,
            'email' =>  $this->email,
            'password' => $this->password
        ];
        $response = $this->postJson('/api/register', $dataWillSend);

        $response->assertNoContent();
        array_pop($dataWillSend); // Remove password, ela esta criptografada e não pode ser comparada
        $this->assertDatabaseHas('users', $dataWillSend);
    }

    public function test_user_request_new_password(): void
    {
        $this->seed();
        Notification::fake();

        $response = $this->postJson('/api/forgot-password', [
            'email' => $this->email
        ]);

        Notification::assertCount(1);

        //Testa se o token foi criado e se pode resetar a senha
        $token = DB::table(config('auth.passwords.users.table'))
            ->select('token')
            ->where('email', "=", $this->email)
            ->first()->token;

        $this->assertNotNull($token);

        $response  = $this->postJson('/api/reset-password', [
            'email' => $this->email,
            'token' => $token,
            'password' => $this->password,
        ]);

        $response->assertOk();
    }
}
