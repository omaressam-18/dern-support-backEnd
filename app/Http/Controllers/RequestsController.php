<?php

namespace App\Http\Controllers;

use App\Models\Requests;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use App\Models\Users;

class RequestsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $courierId = $request->query('courier_id');

    // If courier_id is provided, filter the results
        if ($courierId) {
            $requests = Requests::with(['users_dern', 'couriers'])
            ->where('courier_id', $courierId)
            ->get();
        } else {
            // If no courier_id is provided, return all requests
            $requests = Requests::with(['users_dern', 'couriers'])->get();
        }
        return response()->json([
            'data' => $requests,
            'message' => "ok",
            'status' => 200
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
        'title' => 'required|string|max:255',
        'user_id' => 'required|exists:users_dern,id',
        'description' => 'required|string',
        'type' => 'required|in:drop-off,courier,on-site',
        'address' => 'nullable|string', 
        'pickup_date' => 'required|date|after:today',
        'courier_id' => 'nullable|exists:couriers,id',
        ]);
        $requests = Requests::create([
            'title' => $request->title,
            'user_id'=> $request->user_id,
            'description' => $request->description,
            'type' => $request->type,
            'address' => $request->address,
            'pickup_date' => $request->pickup_date,
            'courier_id' => $request->courier_id,
        ]);
        return response()->json(['message' => 'request aded '], 201);
    }

    

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $request = Requests::find($id);

        if (!$request) {
            return response()->json(['message' => 'Request not found', 'status' => 404], 404);
        }

        return response()->json([
            'data' => $request,
            'message' => 'ok',
            'status' => 200
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $existingRequest = Requests::find($id);

        if (!$existingRequest) {
            return response()->json(['message' => 'Request not found', 'status' => 404, 'success' => false], 404);
        }

        $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'type' => 'sometimes|in:drop-off,courier,on-site',
            'pickup_date' => 'sometimes|date',
            'status' => 'sometimes|in:Pending,Completed,In Progress,Rejected',
            'courier_id' => 'nullable|exists:couriers,id',
        ]);

        if ($request->has('title')) $existingRequest->title = $request->title;
        if ($request->has('description')) $existingRequest->description = $request->description;
        if ($request->has('type')) $existingRequest->type = $request->type;
        if ($request->has('pickup_date')) $existingRequest->pickup_date = $request->pickup_date;
        if ($request->has('status')) $existingRequest->status = $request->status;
        if ($request->has('courier_id')) $existingRequest->courier_id = $request->courier_id;

        if ($existingRequest->save()) {
            return response()->json([
                'data' => $existingRequest,
                'message' => 'Request updated successfully',
                'status' => 200,
                'success' => true
            ]);
        } else {
            return response()->json([
                'message' => 'Failed to update request',
                'status' => 500,
                'success' => false
            ], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $request = Requsts::find($id);

        if (!$request) {
            return response()->json(['message' => 'Request not found', 'status' => 404], 404);
        }

        $request->delete();

        return response()->json([
            'message' => 'Request deleted successfully',
            'status' => 200
        ]);
    }
}
