<?php

namespace App\Http\Controllers;

use App\Models\Questions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuestionsController extends Controller
{
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'description' => 'required|string',
            'email' => 'required|email',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Create and store the question
        $question = Questions::create([
            'name' => $request->name,
            'description' => $request->description,
            'email' => $request->email,
            'status'=>'Opened',
            'user'=>auth()->user()->id
        ]);

        return response()->json([
            'message' => 'Question stored successfully.',
            'question' => $question,
        ], 201);
    }
}
