<?php

namespace App\Http\Controllers;

use App\Models\Admins;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class AdminsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $admin = Admins::all();
        return response()->json([
            'data' => $admin,
            'message' => "ok",
            'status' => 200
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|string|email|max:255|unique:admins',
                'password' => 'required|string|min:8',
            ]);

            $admin = Admins::create([
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            
            return response()->json([
                'data' => $admin,
                'message' => 'admin created successfully',
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
    public function show(Admins $admins)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Admins $admins)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Admins $admins)
    {
        //
    }
}
