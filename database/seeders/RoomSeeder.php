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

        $imagePools = [
            'single' => [
                'https://images.unsplash.com/photo-1631049307261-da0ec440d628?auto=format&fit=crop&w=500&q=60',
                'https://images.unsplash.com/photo-1598928506311-c55dd1b6e7d9?auto=format&fit=crop&w=500&q=60',
                'https://images.unsplash.com/photo-1559841644-08984562005a?auto=format&fit=crop&w=500&q=60',
            ],
            'double' => [
                'https://images.unsplash.com/photo-1631049552240-d8e8ba048cbb?auto=format&fit=crop&w=500&q=60',
                'https://images.unsplash.com/photo-1566665797739-1674de7a421a?auto=format&fit=crop&w=500&q=60',
                'https://images.unsplash.com/photo-1505693314120-0d443867891c?auto=format&fit=crop&w=500&q=60',
            ],
            'queen' => [
                'https://images.unsplash.com/photo-1584622614875-2f938757a188?auto=format&fit=crop&w=500&q=60',
                'https://images.unsplash.com/photo-1595576508898-0ad5c879a061?auto=format&fit=crop&w=500&q=60',
                'https://images.unsplash.com/photo-1618221118493-9cfa1a1c00da?auto=format&fit=crop&w=500&q=60',
            ],
            'king' => [
                'https://images.unsplash.com/photo-1578683010236-d716f9a3f461?auto=format&fit=crop&w=500&q=60',
                'https://images.unsplash.com/photo-1631049035182-249067d7618e?auto=format&fit=crop&w=500&q=60',
                'https://images.unsplash.com/photo-1590490360182-c33d57733427?auto=format&fit=crop&w=500&q=60',
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