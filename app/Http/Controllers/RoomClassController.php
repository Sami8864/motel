<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\RoomClass;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\RoomRelationAmenity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class RoomClassController extends Controller
{
    public function getRoomClasses()
    {
        return response()->json([
            'message' => 'Room Classes Fetched',
            'code' => 200,
            'data' => RoomClass::all()
        ], 200);
    }

    public function getPromoRooms()
    {
        return response()->json([
            'message' => 'Promo Rooms Fetched',
            'code' => 200,
            'data' =>RoomClass::take(3)->get()
        ], 200);
    }


    public function addRoom(Request $request)
    {
        $data = $request->All();
        $validator = Validator::make($data, [
            'name' => 'required|string',
            'description' => 'nullable|string',
            'beds' => 'nullable|integer|min:1',
            'bathroom' => 'nullable|string',
            'price' => 'nullable|string',
            'quantity' => 'nullable|integer|min:0',
            'images' => 'required|min:1|max:204',
            'amenities' => 'nullable|array'
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Create a new RoomClass instance
        $roomClass =RoomClass::create([
            'name' => $data['name'],
            'description' =>  $data['description'],
            'beds' =>  $data['beds'],
            'bathroom' =>  $data['bathroom'],
            'price' => $data['price'],
            'quantity' => $data['quantity'],
        ]);

        $images = $request->file('images');
        foreach ($images as $image) {
            $imagePath = $image->store('public/roomimages');
            //$hash = Str::afterLast($imagePath, '/');
            $imageUrl = url(Storage::url($imagePath));
            Image::create([
                'added_by' => auth()->user()->id,
                'type_id' => $roomClass->id,
                'type' => 1,
                'url' => $imageUrl,
            ]);
        }
        if (isset($data['amenities'])) {
            $amenities = $data['amenities'];
            foreach ($amenities as $amenity) {
                RoomRelationAmenity::create([
                    'room' => $roomClass->id,
                    'added_by' => auth()->user()->id,
                    'aminity' => json_decode($amenity)
                ]);
            }
        }

        return response()->json([
            'message' => 'Room class created successfully.',
            'room_class' => $roomClass
        ], 201);
    }
}
