<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Service\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    /**
     * @var AuthService
     */
    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Make login
     *
     * @param LoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request): \Illuminate\Http\JsonResponse
    {
        return $this->authService->login($request->only(['email', 'password']));
    }

    /**
     * Make register
     *
     * @param RegisterRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request): \Illuminate\Http\JsonResponse | \Illuminate\Http\Response
    {
        return $this->authService->register($request->only(['login', 'name', 'email', 'password']));
    }

    /**
     * Recover forgot password
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function forgotPassword(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        return $this->authService->forgotPassword($request->only(['email']));
    }

    /**
     * Reset password
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPassword(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'token' => 'required|numeric|exists:password_reset_tokens,token',
            'password' => 'required|min:6'
        ]);

        return $this->authService->resetPassword($request->only(['token', 'password',"email"]));
    }
}
