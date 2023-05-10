<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'users' => User::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        return User::create($request->validated()) ? response()->noContent() : response()->json(['error' => 'Error to create user'], 500);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return response()->json([
            'user' => User::findOrFail($user->id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        return User::findOrFail($user->id)->update($request->validated()) ? response()->noContent() : response()->json(['error' => 'Error to update user'], 500);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        return User::findOrFail($user->id)->delete() ? response()->noContent() : response()->json(['error' => 'Error to delete user'], 500);
    }
}
