<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomerService;
use Illuminate\Support\Facades\Validator;

class CustomerServiceController extends Controller
{
    public function store(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'sevice_name' => 'required|string',
            'price' => 'required|string',
            'description' => 'required|string',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Create a new customer service instance
        $service = CustomerService::create([
            'sevice_name' => $request->input('sevice_name'),
            'price' => $request->input('price'),
            'description' => $request->input('description'),
            'added_by' => auth()->user()->id, // Assuming authenticated user is adding the service
        ]);

        return response()->json([
            'message' => 'Customer service added successfully.',
            'service' => $service
        ], 201);
    }

    public function load()
    {
        return response()->json([
            'message' => 'Customer service fetched successfully.',
            'services' => CustomerService::take(2)->get()
        ], 200);
    }
}
