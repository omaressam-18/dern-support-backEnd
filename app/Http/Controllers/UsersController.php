<?php

namespace App\Http\Controllers;

use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = Users::all();
        return response()->json([
            'data' => $users,
            'message' => "ok",
            'status' => 200
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request){
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
                'role' => 'required|in:Individual,Business',
            ]);

            if (Users::where('email', $request->email)->exists()) {
                return response()->json([
                    'message' => 'Email is already in use',
                    'status' => 409
                ], 409);
            }

            $user = Users::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
            ]);
            
            return response()->json([
                'data' => $user,
                'message' => 'User created successfully',
                'status' => 201
            ], 201);
            } catch (ValidationException $e) {
            return response()->json([
                'message' => $e->errors(),
                'status' => 422
            ], 422);
            } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong',
                'status' => 500
            ], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = Users::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found', 'status' => 404], 404);
        }

        return response()->json([
            'data' => $user,
            'message' => 'ok',
            'status' => 200
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = Users::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found', 'status' => 404], 404);
        }

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $id,
            'password' => 'sometimes|string|min:8',
            'role' => 'sometimes|in:Individual,Business',
        ]);

        if ($request->has('name')) $user->name = $request->name;
        if ($request->has('email')) $user->email = $request->email;
        if ($request->has('password')) $user->password = Hash::make($request->password);
        if ($request->has('role')) $user->role = $request->role;

        $user->save();

        return response()->json([
            'data' => $user,
            'message' => 'User updated successfully',
            'status' => 200
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = Users::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found', 'status' => 404], 404);
        }

        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully',
            'status' => 200
        ]);
    }
}
