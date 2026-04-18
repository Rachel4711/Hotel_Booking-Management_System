<?php

namespace Database\Seeders;

use App\Models\Hotel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HotelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $states = [
            'Kuala Lumpur' => [
                [
                    'name' => 'The Majestic Kuala Lumpur',
                    'description' => 'Luxury city hotel near the heart of Bukit Bintang and KLCC.',
                    'address' => 'No. 1, Jalan Sultan Ismail, 50250 Kuala Lumpur',
                    'policies' => 'No smoking. No pets. Check-in after 3:00 PM. Check-out before 12:00 PM.',
                ],
                [
                    'name' => 'KL Skyline Residence',
                    'description' => 'Bright city-view rooms close to transport, dining, and shopping.',
                    'address' => 'Jalan Ampang, 50450 Kuala Lumpur',
                    'policies' => 'No smoking in rooms. Quiet hours after 10:00 PM.',
                ],
                [
                    'name' => 'Bukit Bintang Urban Suites',
                    'description' => 'Convenient stay for travelers who want to be near city nightlife.',
                    'address' => 'Jalan Bukit Bintang, 55100 Kuala Lumpur',
                    'policies' => 'Breakfast available daily. No pets allowed.',
                ],
            ],
            'Selangor' => [
                [
                    'name' => 'Selangor Garden Hotel',
                    'description' => 'Modern stay close to shopping, business districts, and dining.',
                    'address' => 'Jalan SS 16/1, 47500 Subang Jaya, Selangor',
                    'policies' => 'No smoking in rooms. Quiet hours after 10:00 PM.',
                ],
                [
                    'name' => 'Shah Alam Business Suites',
                    'description' => 'Practical hotel for meetings, events, and family visits.',
                    'address' => 'Persiaran Perbandaran, 40000 Shah Alam, Selangor',
                    'policies' => 'No pets. Check-in after 2:00 PM.',
                ],
                [
                    'name' => 'Petaling Jaya Comfort Hotel',
                    'description' => 'Comfortable stay near malls, offices, and food courts.',
                    'address' => 'Jalan SS 2/61, 47300 Petaling Jaya, Selangor',
                    'policies' => 'No smoking indoors. Laundry service available.',
                ],
            ],
            'Penang' => [
                [
                    'name' => 'Penang Seaview Resort',
                    'description' => 'Beachfront resort with a relaxed island atmosphere.',
                    'address' => 'Batu Ferringhi, 11100 George Town, Penang',
                    'policies' => 'No pets. Pool access from 8:00 AM to 8:00 PM.',
                ],
                [
                    'name' => 'George Town Boutique Suites',
                    'description' => 'Stylish boutique hotel in the heart of Penang heritage streets.',
                    'address' => 'Lebuh Leith, 10200 George Town, Penang',
                    'policies' => 'Quiet hours after 11:00 PM. No pets allowed.',
                ],
                [
                    'name' => 'Bayan Lepas Airport Hotel',
                    'description' => 'Convenient hotel for short business trips and transit stays.',
                    'address' => 'Persiaran Bayan Indah, 11900 Bayan Lepas, Penang',
                    'policies' => 'Early check-out available. No smoking in rooms.',
                ],
            ],
            'Johor' => [
                [
                    'name' => 'Johor Bahru Grand Suites',
                    'description' => 'Comfortable suites for families and business travelers.',
                    'address' => 'Jalan Trus, 80000 Johor Bahru, Johor',
                    'policies' => 'No smoking. Breakfast served from 7:00 AM to 10:00 AM.',
                ],
                [
                    'name' => 'Mersing Coastal Retreat',
                    'description' => 'Simple seaside hotel for island transfers and relaxation.',
                    'address' => 'Jalan Abu Bakar, 86800 Mersing, Johor',
                    'policies' => 'No smoking indoors. Ferry transfer assistance available.',
                ],
                [
                    'name' => 'Batu Pahat Heritage Hotel',
                    'description' => 'Central Johor stay close to local food and city attractions.',
                    'address' => 'Jalan Sultanah, 83000 Batu Pahat, Johor',
                    'policies' => 'No pets. Quiet hours after 10:00 PM.',
                ],
            ],
            'Sabah' => [
                [
                    'name' => 'Sabah Heritage Hotel',
                    'description' => 'Coastal hotel with easy access to city attractions and the waterfront.',
                    'address' => 'Jalan Tun Fuad Stephens, 88000 Kota Kinabalu, Sabah',
                    'policies' => 'No outside food. Late check-in available on request.',
                ],
                [
                    'name' => 'Sandakan Bay Hotel',
                    'description' => 'Seafront stay with access to the bay and nearby markets.',
                    'address' => 'Jalan Pryer, 90000 Sandakan, Sabah',
                    'policies' => 'No outside food. Breakfast starts at 6:30 AM.',
                ],
                [
                    'name' => 'Tawau City View Suites',
                    'description' => 'Convenient hotel for coastal travelers and business stays.',
                    'address' => 'Jalan Dunlop, 91000 Tawau, Sabah',
                    'policies' => 'No smoking indoors. Laundry service available.',
                ],
            ],
            'Sarawak' => [
                [
                    'name' => 'Sarawak Riverside Lodge',
                    'description' => 'Riverfront lodge with easy access to Kuching cultural spots.',
                    'address' => 'Jalan Tunku Abdul Rahman, 93100 Kuching, Sarawak',
                    'policies' => 'No pets. Laundry service available daily.',
                ],
                [
                    'name' => 'Miri Sunset Hotel',
                    'description' => 'Modern hotel with easy access to beaches and city life.',
                    'address' => 'Jalan Kubu, 98000 Miri, Sarawak',
                    'policies' => 'No smoking. Early check-in subject to room availability.',
                ],
                [
                    'name' => 'Sibu Heritage Suites',
                    'description' => 'Comfortable stay near local markets and the riverfront.',
                    'address' => 'Jalan Lau King Howe, 96000 Sibu, Sarawak',
                    'policies' => 'Quiet hours after 10:00 PM. No pets allowed.',
                ],
            ],
            'Kedah' => [
                [
                    'name' => 'Kedah Lakeside Retreat',
                    'description' => 'Peaceful stay surrounded by greenery and lake views.',
                    'address' => 'Jalan Langkawi, 07000 Kuah, Kedah',
                    'policies' => 'Quiet hours after 11:00 PM. Smoking only in designated areas.',
                ],
                [
                    'name' => 'Alor Setar City Hotel',
                    'description' => 'Practical city stay near shopping and government offices.',
                    'address' => 'Jalan Sultanah, 05150 Alor Setar, Kedah',
                    'policies' => 'No smoking in rooms. Breakfast available daily.',
                ],
                [
                    'name' => 'Langkawi Island Breeze Suites',
                    'description' => 'Relaxed island hotel for beach holidays and family trips.',
                    'address' => 'Pantai Cenang, 07000 Langkawi, Kedah',
                    'policies' => 'No pets. Pool access from 8:00 AM to 8:00 PM.',
                ],
            ],
            'Melaka' => [
                [
                    'name' => 'Melaka Heritage Boutique Hotel',
                    'description' => 'Boutique stay near the historic city center and river walk.',
                    'address' => 'Jalan Tun Tan Cheng Lock, 75200 Melaka',
                    'policies' => 'No smoking. Early check-in subject to availability.',
                ],
                [
                    'name' => 'Ayer Keroh Family Suites',
                    'description' => 'Family-friendly stay close to attractions and the highway.',
                    'address' => 'Jalan Ayer Keroh, 75450 Melaka',
                    'policies' => 'No pets. Quiet hours after 10:00 PM.',
                ],
                [
                    'name' => 'Melaka Riverfront Hotel',
                    'description' => 'Scenic hotel with easy access to the river cruise area.',
                    'address' => 'Jalan Bunga Raya, 75100 Melaka',
                    'policies' => 'Breakfast served daily. No smoking indoors.',
                ],
            ],
            'Perak' => [
                [
                    'name' => 'Ipoh Heritage Stay',
                    'description' => 'Comfortable hotel near Ipoh Old Town and local food spots.',
                    'address' => 'Jalan Sultan Iskandar, 30000 Ipoh, Perak',
                    'policies' => 'No smoking in rooms. Late checkout subject to availability.',
                ],
                [
                    'name' => 'Taiping Lakeview Hotel',
                    'description' => 'Relaxed hotel with easy access to parks and heritage streets.',
                    'address' => 'Jalan Taman Tasik, 34000 Taiping, Perak',
                    'policies' => 'No pets. Quiet hours after 10:00 PM.',
                ],
                [
                    'name' => 'Teluk Intan Riverside Suites',
                    'description' => 'Convenient riverfront stay for business and family travel.',
                    'address' => 'Jalan Changkat Jong, 36000 Teluk Intan, Perak',
                    'policies' => 'Smoking only in designated areas. Breakfast available daily.',
                ],
            ],
            'Pahang' => [
                [
                    'name' => 'Genting Highlands View Hotel',
                    'description' => 'Cool highland retreat with mountain views and easy access to attractions.',
                    'address' => 'Genting Highlands, 69000 Pahang',
                    'policies' => 'No pets. Smoking only in designated outdoor areas.',
                ],
                [
                    'name' => 'Kuantan Seaside Suites',
                    'description' => 'Beach-adjacent stay ideal for weekend getaways and family trips.',
                    'address' => 'Jalan Teluk Cempedak, 25050 Kuantan, Pahang',
                    'policies' => 'No smoking indoors. Pool access from 8:00 AM to 8:00 PM.',
                ],
                [
                    'name' => 'Cameron Highlands Garden Retreat',
                    'description' => 'Cool climate hotel surrounded by tea hills and scenic views.',
                    'address' => 'Tanah Rata, 39000 Cameron Highlands, Pahang',
                    'policies' => 'Quiet hours after 10:00 PM. No pets allowed.',
                ],
            ],
            'Terengganu' => [
                [
                    'name' => 'Kuala Terengganu Seaside Hotel',
                    'description' => 'Relaxed stay near the waterfront and city market.',
                    'address' => 'Jalan Sultan Zainal Abidin, 20000 Kuala Terengganu, Terengganu',
                    'policies' => 'Quiet hours after 10:00 PM. Breakfast available daily.',
                ],
                [
                    'name' => 'Dungun Beachfront Resort',
                    'description' => 'Leisure resort with direct access to the coast and sea breeze.',
                    'address' => 'Jalan Pantai, 23000 Dungun, Terengganu',
                    'policies' => 'No pets. Pool access from 8:00 AM to 8:00 PM.',
                ],
                [
                    'name' => 'Kemaman Harbour Suites',
                    'description' => 'Comfortable harbor stay close to shops and local dining.',
                    'address' => 'Jalan Sulaimani, 24000 Kemaman, Terengganu',
                    'policies' => 'No smoking in rooms. Late check-in available.',
                ],
            ],
            'Negeri Sembilan' => [
                [
                    'name' => 'Seremban Comfort Hotel',
                    'description' => 'Practical business stay close to the city center.',
                    'address' => 'Jalan Yam Tuan, 70000 Seremban, Negeri Sembilan',
                    'policies' => 'No smoking. Laundry service available on request.',
                ],
                [
                    'name' => 'Port Dickson Coastal Suites',
                    'description' => 'Relaxing seaside stay for family vacations and beach trips.',
                    'address' => 'Jalan Pantai, 71050 Port Dickson, Negeri Sembilan',
                    'policies' => 'No pets. Quiet hours after 10:00 PM.',
                ],
                [
                    'name' => 'Nilai Gateway Hotel',
                    'description' => 'Convenient stay near the highway, outlets, and airport access.',
                    'address' => 'Persiaran Pusat Bandar, 71800 Nilai, Negeri Sembilan',
                    'policies' => 'No smoking indoors. Breakfast available daily.',
                ],
            ],
            'Perlis' => [
                [
                    'name' => 'Kangar Royal Hotel',
                    'description' => 'A quiet northern stay near shops and local attractions.',
                    'address' => 'Jalan Tuanku Syed Putra, 01000 Kangar, Perlis',
                    'policies' => 'No pets. Check-in after 2:00 PM.',
                ],
                [
                    'name' => 'Arau Heritage Suites',
                    'description' => 'Simple and comfortable stay for university and town visits.',
                    'address' => 'Jalan Arau, 02600 Arau, Perlis',
                    'policies' => 'No smoking in rooms. Quiet hours after 10:00 PM.',
                ],
                [
                    'name' => 'Kuala Perlis Harbor Hotel',
                    'description' => 'Convenient harbor hotel for travelers going to Langkawi.',
                    'address' => 'Jalan Kuala Perlis, 02000 Kuala Perlis, Perlis',
                    'policies' => 'No pets. Ferry transfer assistance available.',
                ],
            ],
            'Kelantan' => [
                [
                    'name' => 'Kota Bharu Cultural Hotel',
                    'description' => 'Central stay close to markets, museums, and dining.',
                    'address' => 'Jalan Hilir Pasar, 15000 Kota Bharu, Kelantan',
                    'policies' => 'No smoking in rooms. Extra towels available on request.',
                ],
                [
                    'name' => 'Wakaf Che Yeh Market Suites',
                    'description' => 'Budget-friendly hotel near the lively night market area.',
                    'address' => 'Jalan Wakaf Che Yeh, 15100 Kota Bharu, Kelantan',
                    'policies' => 'Quiet hours after 11:00 PM. No pets allowed.',
                ],
                [
                    'name' => 'Tanah Merah Riverside Hotel',
                    'description' => 'Relaxed stay for road travelers and local family visits.',
                    'address' => 'Jalan Hospital, 17500 Tanah Merah, Kelantan',
                    'policies' => 'No smoking indoors. Breakfast available daily.',
                ],
            ],
        ];

        $imagePairs = [
            [
                'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=1200&q=80',
                'https://images.unsplash.com/photo-1578683010236-d716f9a3f461?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?auto=format&fit=crop&w=1200&q=80',
                'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'https://images.unsplash.com/photo-1501117716987-c8e1ecb210e9?auto=format&fit=crop&w=1200&q=80',
                'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'https://images.unsplash.com/photo-1512918728675-ed5a9ecdebfd?auto=format&fit=crop&w=1200&q=80',
                'https://images.unsplash.com/photo-1502920917128-1aa500764cbd?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?auto=format&fit=crop&w=1200&q=80',
                'https://images.unsplash.com/photo-1445019980597-93fa8acb246c?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'https://images.unsplash.com/photo-1582719478185-2f0c1b3e6d0f?auto=format&fit=crop&w=1200&q=80',
                'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'https://images.unsplash.com/photo-1509023464722-18d996393ca8?auto=format&fit=crop&w=1200&q=80',
                'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'https://images.unsplash.com/photo-1560067174-8943bdc9b67b?auto=format&fit=crop&w=1200&q=80',
                'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'https://images.unsplash.com/photo-1514104896889-bf5d7f6f8b24?auto=format&fit=crop&w=1200&q=80',
                'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?auto=format&fit=crop&w=1200&q=80',
                'https://images.unsplash.com/photo-1578683010236-d716f9a3f461?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?auto=format&fit=crop&w=1200&q=80',
                'https://images.unsplash.com/photo-1560067174-8943bdc9b67b?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?auto=format&fit=crop&w=1200&q=80',
                'https://images.unsplash.com/photo-1445019980597-93fa8acb246c?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'https://images.unsplash.com/photo-1582719478185-2f0c1b3e6d0f?auto=format&fit=crop&w=1200&q=80',
                'https://images.unsplash.com/photo-1509023464722-18d996393ca8?auto=format&fit=crop&w=1200&q=80',
            ],
        ];

        $imageIndex = 0;

        foreach ($states as $state => $hotels) {
            foreach ($hotels as $hotel) {
                $pair = $imagePairs[$imageIndex % count($imagePairs)];

                Hotel::create([
                    'name' => $hotel['name'],
                    'description' => $hotel['description'],
                    'address' => $hotel['address'],
                    'state' => $state,
                    'click_count' => 0,
                    'policies' => $hotel['policies'],
                    'cover_image' => $pair[0],
                    'images' => $pair,
                ]);

                $imageIndex++;
            }
        }
    }
}
