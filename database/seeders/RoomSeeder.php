<?php

namespace Database\Seeders;

use App\Models\Hotel;
use App\Models\Room;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hotels = Hotel::all();

        // array of images for each room type
        $imagePools = [
            'single' => [
                'images/single1.jpg',
                'images/single2.jpg',
                'images/single3.jpg',
                // 'images/single4.jpg',
            ],
            'double' => [
                'images/double1.jpg',
                'images/double2.jpg',
                'images/double3.webp',
                // 'images/double4.jpg',
            ],
            'queen' => [
                'images/queen1.jpg',
                'images/queen2.jpg',
                'images/queen3.jpg',
                // 'images/queen4.jpg',
            ],
            'king' => [
                'images/king1.jpg',
                'images/king2.jpg',
                'images/king3.jpg',
                // 'images/king4.jpg',
            ],
        ];

        $stateMultipliers = [
            'Kuala Lumpur' => 1.8,
            'Penang' => 1.6,
            'Johor' => 1.4,
            'Selangor' => 1.4,
            'Sabah' => 1.3,
            'Sarawak' => 1.2,
            'Melaka' => 1.3,
            'Pahang' => 1.5,
            'Perak' => 1.1,
            'Kedah' => 1.2,
            'Terengganu' => 1.0,
            'Negeri Sembilan' => 1.0,
            'Perlis' => 0.8,
            'Kelantan' => 0.8,
        ];

        foreach ($hotels as $index => $hotel) {
            $baseMultiplier = $stateMultipliers[$hotel->state] ?? 1.0;

            $randomFluctuation = rand(-10, 20) / 100; 
            $finalMultiplier = $baseMultiplier + $randomFluctuation;

            $singleIndices = array_rand($imagePools['single'], 1);
            $doubleIndices = array_rand($imagePools['double'], 1);
            $queensIndices = array_rand($imagePools['queen'], 1);
            $kingIndices = array_rand($imagePools['king'], 1);
            
            $hotelImages = [
                'single' => $imagePools['single'][$singleIndices],
                'double' => $imagePools['double'][$doubleIndices],
                'queen'  => $imagePools['queen'][$queensIndices],
                'king'   => $imagePools['king'][$kingIndices],
            ];

            $roomTypes = [
                [
                    'name' => 'Single Room',
                    'type' => 'single',
                    'capacity' => 1,
                    'price' => 120.00 * $finalMultiplier,
                    'total_rooms' => rand(3, 8),
                    'image' => $hotelImages['single'],
                ],
                [
                    'name' => 'Double Room',
                    'type' => 'double',
                    'capacity' => 2,
                    'price' => 180.00 * $finalMultiplier,
                    'total_rooms' => rand(5, 12),
                    'image' => $hotelImages['double'],
                ],
                [
                    'name' => 'Queen Room',
                    'type' => 'queen',
                    'capacity' => 2,
                    'price' => 250.00 * $finalMultiplier,
                    'total_rooms' => rand(4, 10),
                    'image' => $hotelImages['queen'],
                ],
                [
                    'name' => 'King Suite',
                    'type' => 'king',
                    'capacity' => 4,
                    'price' => 450.00 * $finalMultiplier,
                    'total_rooms' => rand(2, 5),
                    'image' => $hotelImages['king'],
                ],
            ];

            foreach ($roomTypes as $roomData) {
                $formattedPrice = ceil($roomData['price'] / 5) * 5;

                Room::create([
                    'hotel_id' => $hotel->id,
                    'name' => $roomData['name'],
                    'type' => $roomData['type'],
                    'capacity' => $roomData['capacity'],
                    'price' => $formattedPrice,
                    'total_rooms' => $roomData['total_rooms'],
                    'image' => $roomData['image'],
                ]);
            }
        }
    }
}