<?php

namespace Database\Seeders;

use App\Models\RoomClass;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoomSEeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        RoomClass::create([
            'name' => 'Premier Suite',
            'description' => 'Luxurious suite with premium amenities',
            'beds' => '1',
            'bathroom' => 'Attached',
            'price' => '500$',
            'quantity'=>10,
        ]);

        RoomClass::create([
            'name' => 'Premier Suite',
            'description' => 'Luxurious suite with premium amenities',
            'beds' => '2',
            'bathroom' => 'Attached',
            'price' => '800$',
            'quantity'=>10

        ]);


        RoomClass::create([
            'name' => 'Double Bed Room ',
            'description' => 'Comfortable room with double bed',
            'beds' => 'Double bed',
            'bathroom' => 'Private bathroom',
            'price' => '200$',
            'quantity'=>20

        ]);
        RoomClass::create([
            'name' => 'Single Bed Room ',
            'description' => 'Cozy room with single bed',
            'beds' => 'Single bed',
            'bathroom' => 'Not Attached',
            'price' => '50$',
            'quantity'=>25
        ]);
    }
}
