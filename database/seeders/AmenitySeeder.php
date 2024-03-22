<?php

namespace Database\Seeders;

use App\Models\RoomAmenitiy;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AmenitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $amenities = [
            'Free Wi-Fi',
            'Air Conditioning',
            'Mini-Bar',
            'Television',
            'Room Service',
            'Hair Dryer',
            'Ironing Facilities',
            'Safe',
            'Coffee Maker',
            'Work Desk',
            'Telephone',
            'Daily Housekeeping',
            'Ensuite Bathroom',
            'Complimentary Toiletries',
            'Closet',
            'hotel_parking',
            'pet_friendly',
            'facilities_for_disabled',
            'in_room_dining',
            // Add more amenities if needed
        ];

        // Seed amenities
        foreach ($amenities as $amenity) {
            RoomAmenitiy::create(['name' => $amenity,'added_by'=>1]);
        }
    }
}
