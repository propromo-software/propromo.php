<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreUserRequest;
use Illuminate\Database\QueryException;
use App\Http\Resources\V1\UserCollection;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\V1\UserResource;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return new UserCollection(User::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        try {
            $user = User::create([
                "name" => $request->name,
                "email" => $request->email,
                "auth_type" => $request->authType,
                "password" => $request->password
            ]);
            if ($user) {
                return response()->json([
                    'success' => true,
                    'message' => 'User created successfully',
                    'data' => $user
                ], 201);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create user'
                ], 500);
            }
        } catch (QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create user',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return UserResource::make($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
