<?php

namespace App\Service;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Mockery\Generator\StringManipulation\Pass\Pass;
use Ramsey\Uuid\Type\Integer;

class AuthService
{
    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ],200);
    }

    /**
     * Get the token array structure.
     */
    private function getExistentToken(array $credentials)
    {
        return DB::table(config('auth.passwords.users.table'))
            ->where('email', $credentials['email'])
            ->where('token', $credentials['token'])
            ->first();
    }

    /**
     * Destroy token.
     */
    private function destroyToken(array $credentials): void
    {
        DB::table(config('auth.passwords.users.table'))
            ->where('email', $credentials['email'])
            ->where('token', $credentials['token'])
            ->delete();
    }

    /**
     * Create and Register em database a token for autenticate.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    private function createAndRegisterToken(User $user): int | string
    {
        $token = substr(rand(0, 9999999), 0, 4); // Criar um codigo de 4 digitos

        DB::table(config('auth.passwords.users.table'))->insert([
            'email' => $user->email,
            'token' => $token
        ]);

        return $token;
    }

    /**
     * login with given credentials, return JWT token if authenticated.
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(array $credentials): \Illuminate\Http\JsonResponse
    {
        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Email ou senha incorreto'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(array $credentials): \Illuminate\Http\JsonResponse | \Illuminate\Http\Response
    {
        $user = User::create([
            'login' => $credentials['login'],
            'name' => $credentials['name'],
            'email' => $credentials['email'],
            'password' => Hash::make($credentials['password'])
        ]);

        return $user ? response()->noContent() : response()->json(['error' => 'Não foi possivel criar o Usuario!'], 401);
    }

    /**
     * Recover forgot password.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function forgotPassword(array $credentials): \Illuminate\Http\JsonResponse
    {
        $user = User::where('email', $credentials['email'])->first();
        $token = $this->createAndRegisterToken($user);
        /**
         * @var User $user
         */
        $user->sendPasswordResetNotification($token);

        return response()->json(['message' => 'Email enviado com sucesso!'], 200);
    }

    /**
     * Reset password.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPassword(array $credentials): \Illuminate\Http\JsonResponse
    {
        $token = $this->getExistentToken($credentials);

        if (!$token) {
            return response()->json(['error' => 'Token invalido!'], 401);
        }

        $user = User::where('email', $credentials['email'])->first();
        $user->password = Hash::make($credentials['password']);
        $user->save();
        
        $this->destroyToken($credentials);

        return response()->json(['message' => 'Senha alterada com sucesso!'], 200);
    }
}
