<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomClass extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'beds',
        'bathroom',
        'price',
        'hotel_parking',
        'pet_friendly',
        'facilities_for_disabled',
        'in_room_dining',
        'quantity'
    ];
}
