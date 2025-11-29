<?php

namespace App\Http\Controllers;

use App\Models\Couriers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // استيراد Hash

class CouriersController extends Controller
{
    /**
     * عرض قائمة جميع السعاة.
     */
    public function index()
    {
        $couriers = Couriers::all();
        return response()->json([
            'data' => $couriers,
            'message' => 'Couriers retrieved successfully',
            'status' => 200
        ]);
    }

    /**
     * تخزين ساعي جديد.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:couriers,email',
            'phone_number' => 'required|string|max:20',
            'password' => 'required|string|min:8',
        ]);

        $courier = Couriers::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password) // تشفير كلمة المرور
        ]);

        return response()->json([
            'data' => $courier,
            'message' => 'Courier created successfully',
            'status' => 201
        ], 201);
    }

    /**
     * عرض تفاصيل ساعي معين.
     */
    public function show($id)
    {
        $courier = Couriers::find($id);

        if (!$courier) {
            return response()->json([
                'message' => 'Courier not found',
                'status' => 404
            ], 404);
        }

        return response()->json([
            'data' => $courier,
            'message' => 'Courier retrieved successfully',
            'status' => 200
        ]);
    }

    /**
     * تحديث بيانات ساعي معين.
     */
    public function update(Request $request, $id)
    {
        $courier = Couriers::find($id);

        if (!$courier) {
            return response()->json([
                'message' => 'Courier not found',
                'status' => 404
            ], 404);
        }

        $validatedData = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:couriers,email,' . $courier->id,
            'phone_number' => 'sometimes|string|max:20',
            'password' => 'sometimes|string|min:8',
        ]);

        if (isset($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']); // تشفير كلمة المرور إذا تم تحديثها
        }

        $courier->update($validatedData);

        return response()->json([
            'data' => $courier,
            'message' => 'Courier updated successfully',
            'status' => 200
        ]);
    }

    /**
     * حذف ساعي معين.
     */
    public function destroy($id)
    {
        $courier = Couriers::find($id);

        if (!$courier) {
            return response()->json([
                'message' => 'Courier not found',
                'status' => 404
            ], 404);
        }

        $courier->delete();

        return response()->json([
            'message' => 'Courier deleted successfully',
            'status' => 200
        ]);
    }
}