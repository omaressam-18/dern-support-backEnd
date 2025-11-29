<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Auth;
use App\Models\Users;
use App\Models\Admins;
use App\Models\Requests;
use App\Models\Couriers;
use App\Http\Controllers\RequestsController;
use App\Http\Controllers\CouriersController;
use App\Http\Controllers\AdminsController;

Route::post('/login', function (Request $request) {
    $user = Users::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['error' => 'Invalid email or password'], 401);
    }

    return response()->json(['message' => 'Login successful', 'user' => $user], 200);
});




Route::apiResource('users', UsersController::class);
Route::apiResource('requests', RequestsController::class);
Route::apiResource('admin', AdminsController::class);

Route::post('/Admin/login', function (Request $request) {
    try {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $admin = Admins::where('email', $request->email)->first();

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return response()->json(['error' => 'Invalid email or password'], 401);
        }

        return response()->json(['message' => 'Login successful', 'admin' => $admin], 200);
    } catch (\Exception $e) {
        \Log::error('Login error: ' . $e->getMessage());
        return response()->json(['error' => 'An internal server error occurred. Please try again later.'], 500);
    }
});

Route::apiResource('couriers', CouriersController::class);
Route::post('/courier/login', function (Request $request) {
    try {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $courier = Couriers::where('email', $request->email)->first();

        if (!$courier || !Hash::check($request->password, $courier->password)) {
            return response()->json(['error' => 'Invalid email or password'], 401);
        }

        return response()->json(['message' => 'Login successful', 'courier' => $courier], 200);
    } catch (\Exception $e) {
        \Log::error('Login error: ' . $e->getMessage());
        return response()->json(['error' => 'An internal server error occurred. Please try again later.'], 500);
    }
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


