<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            GenresTableSeeder::class,
            CitiesTableSeeder::class,
            VenuesTableSeeder::class,
            ConcertsTableSeeder::class,
            ConcertGenresTableSeeder::class,
            TicketsTableSeeder::class
        ]);
    }
}

class GenresTableSeeder extends Seeder
{
    public function run(): void
    {
        $genres = ['Today', 'This Week', 'Trending', 'Pop', 'Rock', 'Emo', 'Jazz', 'Metal', 'Indie', 'Alternative', 'Hip-Hop', 'R&B', 'Reggae'];
        foreach ($genres as $genre) {
            DB::table('genres')->insert([
                'name' => $genre
            ]);
        }
    }
}

class CitiesTableSeeder extends Seeder
{
    public function run(): void
    {
        $cities = ['Bekasi', 'Bandung', 'Bali', 'Jakarta', 'Surabaya', 'Yogyakarta', 'Semarang', 'Medan', 'Makassar'];
        foreach ($cities as $city) {
            DB::table('cities')->insert([
                'name' => $city
            ]);
        }
    }
}

class VenuesTableSeeder extends Seeder
{
    public function run(): void
    {
        $cities = DB::table('cities')->pluck('id', 'name');
        $data = [
            ['name' => 'SMAN 1 Tambun Selatan', 'city' => 'Bekasi'],
            ['name' => 'IFI Bandung', 'city' => 'Bandung'],
            ['name' => 'Pantai Mertasari', 'city' => 'Bali'],
            ['name' => 'The Pallas', 'city' => 'Jakarta'],
            ['name' => 'GBK Senayan', 'city' => 'Jakarta'],
            ['name' => 'Universitas Gadjah Mada', 'city' => 'Yogyakarta'],
            ['name' => 'Wonderia', 'city' => 'Semarang'],
            ['name' => 'Medan Magnet', 'city' => 'Medan'],
            ['name' => 'Payakumbuah', 'city' => 'Makassar'],
            ['name' => 'Pakuwon Trade Center', 'city' => 'Surabaya'],
        ];

        foreach ($data as $item) {
            DB::table('venues')->insert([
                'name' => $item['name'],
                'city_id' => $cities[$item['city']],
                'latitude' => -6.2,
                'longitude' => 106.8,
            ]);
        }
    }
}

class ConcertsTableSeeder extends Seeder
{
    public function run(): void
    {
        $venues = DB::table('venues')->pluck('id', 'name');
        $concerts = [
            ['name' => 'Synchronize Fest 2024', 
            'genre' => 'Indie', 'venue' => 'GBK Senayan', 
            'start' => '2025-05-25 16:00:00', 
            'end' => '2025-05-25 23:00:00', 
            'poster' => 'https://garcnrembfsdumtgemxo.supabase.co/storage/v1/object/concert-assets/posters/1945.jpg', 
            'venue_link' => 'https://garcnrembfsdumtgemxo.supabase.co/storage/v1/object/concert-assets/venues/101-1010251_concert-special-event-seating-map-concert-seat-map.jpg'],
            ['name' => 'We The Fest', 
            'genre' => 'Pop', 'venue' => 
            'The Pallas', 
            'start' => '2025-05-25 15:00:00', 
            'end' => '2025-05-25 23:00:00', 
            'poster' => 'https://garcnrembfsdumtgemxo.supabase.co/storage/v1/object/concert-assets/posters/cincin.jpg', 
            'venue_link' => 'https://garcnrembfsdumtgemxo.supabase.co/storage/v1/object/concert-assets/venues/2024+VENUE+MAP.png'],
            ['name' => 'Jazz Gunung Bromo', 
            'genre' => 'Jazz', 
            'venue' => 'Pakuwon Trade Center', 
            'start' => '2025-05-25 17:00:00', 
            'end' => '2025-05-25 21:00:00', 
            'poster' => 'https://garcnrembfsdumtgemxo.supabase.co/storage/v1/object/concert-assets/posters/jumbo.jpg', 
            'venue_link' => 'https://garcnrembfsdumtgemxo.supabase.co/storage/v1/object/concert-assets/venues/25F1409-SOEC_Sesame-8.5x11-SeatingMap_-Digital.png'],
            ['name' => 'DCDC Rock Adventure', 
            'genre' => 'Rock', 
            'venue' => 'IFI Bandung', 
            'start' => '2025-05-25 18:00:00', 
            'end' => '2025-05-25 22:00:00', 
            'poster' => 'https://garcnrembfsdumtgemxo.supabase.co/storage/v1/object/concert-assets/posters/ruangindonesia.fest-photo-DJBSbKkpPUm-20250429_131729_494553418.jpg', 
            'venue_link' => 'https://garcnrembfsdumtgemxo.supabase.co/storage/v1/object/concert-assets/venues/Balcony_seat-map.png'],
            ['name' => 'Emo Night Jakarta', 
            'genre' => 'Emo', 
            'venue' => 'GBK Senayan', 
            'start' => '2025-05-27 19:00:00', 
            'end' => '2025-05-27 23:00:00', 
            'poster' => 'https://garcnrembfsdumtgemxo.supabase.co/storage/v1/object/concert-assets/posters/waktunyaberpestaria-photo-DIXv4M1J9Fz-20250414_121802_491459637.jpg', 
            'venue_link' => 'https://garcnrembfsdumtgemxo.supabase.co/storage/v1/object/concert-assets/venues/bass-concert-hall-bass-performance-hall-aircraft-seat-map-theatre-seating-plan-png-favpng-asnU1iu4gJW63q49cY8FyGRQi.jpg'],
            ['name' => 'Soundrenaline 2024', 
            'genre' => 'Alternative', 
            'venue' => 'Wonderia', 
            'start' => '2025-05-27 17:00:00', 
            'end' => '2025-05-27 23:30:00', 
            'poster' => 'https://garcnrembfsdumtgemxo.supabase.co/storage/v1/object/concert-assets/posters/tautaufestival-photo-DJG_yVMShwQ-20250521_061703_491903837.jpg', 
            'venue_link' => 'https://garcnrembfsdumtgemxo.supabase.co/storage/v1/object/concert-assets/venues/beatles-at-the-hollywood-bowl-cure-bowl-los-angeles-philharmonic-walt-disney-concert-hall-hollywood-bowl-concert-hall-seating-assignment-aircraft-seat-map-seating-plan-los-angeles.png'],
            ['name' => 'LaLaLa Festival', 
            'genre' => 'Pop', 
            'venue' => 'Universitas Gadjah Mada', 
            'start' => '2025-05-27 15:00:00', 
            'end' => '2025-05-27 23:00:00', 
            'poster' => 'https://garcnrembfsdumtgemxo.supabase.co/storage/v1/object/concert-assets/posters/infokonser-photo-DITANbPTzpk-20250416_234934_490067989.webp', 
            'venue_link' => 'https://garcnrembfsdumtgemxo.supabase.co/storage/v1/object/concert-assets/venues/bUeTN2bKUQP_77FnNLCJ5M4bvWqUr0Urx6L8ZK1Aee1tBcXhWYs0u4ubRJXVYfFKWqTzhkJDbXvJunZH-0Z0qaSCxKBSuXQwVfVBBbMzf59XOw.png'],
            ['name' => 'Reggae Rise Up', 
            'genre' => 'Reggae', 
            'venue' => 'Pantai Mertasari', 
            'start' => '2025-05-27 14:00:00', 
            'end' => '2025-05-27 22:00:00', 
            'poster' => 'https://garcnrembfsdumtgemxo.supabase.co/storage/v1/object/concert-assets/posters/bigbangfest.id-photo-DJDqhx9SpCa-20250501_014118_491894932.jpg', 
            'venue_link' => 'https://garcnrembfsdumtgemxo.supabase.co/storage/v1/object/concert-assets/venues/city-of-perth-red-paw-seating-plan-gumtree-western-australia-perth-arena-ticket-concert-australia.png'],
            ['name' => 'Java Hip-Hop Day', 
            'genre' => 'Hip-Hop', 
            'venue' => 'SMAN 1 Tambun Selatan', 
            'start' => '2026-08-22 16:00:00', 
            'end' => '2026-08-22 21:30:00', 
            'poster' => 'https://garcnrembfsdumtgemxo.supabase.co/storage/v1/object/concert-assets/posters/bigbangfest.id-photo-DIx5F5OSn9t-20250423_134730_491469743.jpg', 
            'venue_link' => 'https://garcnrembfsdumtgemxo.supabase.co/storage/v1/object/concert-assets/venues/Concert-End-Stage-Seating-Map.png'],
            ['name' => 'Indieground Malang', 
            'genre' => 'Indie', 
            'venue' => 'Pakuwon Trade Center', 
            'start' => '2026-06-30 17:00:00', 
            'end' => '2026-06-30 22:00:00', 
            'poster' => 'https://garcnrembfsdumtgemxo.supabase.co/storage/v1/object/concert-assets/posters/riangriuhfest-thumbnail-DJOwH4xS4VC-20250504_190118_491422765.jpg', 
            'venue_link' => 'https://garcnrembfsdumtgemxo.supabase.co/storage/v1/object/concert-assets/venues/concert-seating-map_assuredpartners-6636151e95.png'],
            ['name' => 'Rock in Semarang', 
            'genre' => 'Rock', 
            'venue' => 'Wonderia', 
            'start' => '2026-12-02 18:00:00', 
            'end' => '2026-12-02 23:00:00', 
            'poster' => 'https://garcnrembfsdumtgemxo.supabase.co/storage/v1/object/concert-assets/posters/m&m.jpg', 
            'venue_link' => 'https://garcnrembfsdumtgemxo.supabase.co/storage/v1/object/concert-assets/venues/concert-seating-plan-graphic-zhr19enzmtm0keat-zhr19enzmtm0keat.png'],
            ['name' => 'Metal Storm', 
            'genre' => 'Metal', 
            'venue' => 'Medan Magnet', 
            'start' => '2026-09-17 19:00:00', 
            'end' => '2026-09-17 23:00:00', 
            'poster' => 'https://garcnrembfsdumtgemxo.supabase.co/storage/v1/object/concert-assets/posters/jumbo.jpg', 
            'venue_link' => 'https://garcnrembfsdumtgemxo.supabase.co/storage/v1/object/concert-assets/venues/concert-venue-layout-map-ht9wbmg6mc1t6z4q-ht9wbmg6mc1t6z4q.png'],
            ['name' => 'Emo Revival Tour', 
            'genre' => 'Emo', 
            'venue' => 'The Pallas', 
            'start' => '2025-05-26 20:00:00', 
            'end' => '2025-05-26 23:59:00', 
            'poster' => 'https://garcnrembfsdumtgemxo.supabase.co/storage/v1/object/concert-assets/posters/shaollinmusic-photo-DJJoYrXzY7Y-20250502_201807_494384395.webp', 
            'venue_link' => 'https://garcnrembfsdumtgemxo.supabase.co/storage/v1/object/concert-assets/venues/forest-national-sportpaleis-antwerp-map-plan-concert-png-favpng-8S4sedZxprBiMPckgtmCQ9CpJ.jpg'],
            ['name' => 'RnB Session', 
            'genre' => 'R&B', 
            'venue' => 'Universitas Gadjah Mada', 
            'start' => '2024-08-19 17:00:00', 
            'end' => '2024-08-19 22:00:00', 
            'poster' => 'https://garcnrembfsdumtgemxo.supabase.co/storage/v1/object/concert-assets/posters/infokonser-photo-DJ0_8neTffU-20250519_213158_499544471.webp', 
            'venue_link' => 'https://garcnrembfsdumtgemxo.supabase.co/storage/v1/object/concert-assets/venues/Frontwave-Seat-Map-Concert-380x350-e9dccb8722.png'],
            ['name' => 'Makassar Jazz Fest', 
            'genre' => 'Jazz', 
            'venue' => 'Payakumbuah', 
            'start' => '2024-07-12 17:00:00', 
            'end' => '2024-07-12 23:00:00', 
            'poster' => 'https://garcnrembfsdumtgemxo.supabase.co/storage/v1/object/concert-assets/posters/tautaufestival-photo-DJG_yVMShwQ-20250521_061703_491903837.jpg', 
            'venue_link' => 'https://garcnrembfsdumtgemxo.supabase.co/storage/v1/object/concert-assets/venues/png-transparent-amway-center-beautiful-trauma-world-tour-el-dorado-world-tour-concert-seating-assignment-others-miscellaneous-stage-structure.png'],
        ];

        foreach ($concerts as $concert) {
            DB::table('concerts')->insert([
                'name' => $concert['name'],
                'description' => $concert['name'] . ' official description.',
                'concert_start' => $concert['start'],
                'concert_end' => $concert['end'],
                'venue_id' => $venues[$concert['venue']],
                'link_poster' => $concert['poster'],
                'link_venue' => $concert['venue_link'],
            ]);
        }
    }
}

class ConcertGenresTableSeeder extends Seeder
{
    public function run(): void
    {
        $concerts = DB::table('concerts')->pluck('id', 'name');
        $genres = DB::table('genres')->pluck('id', 'name');

        $data = [
            'Synchronize Fest 2024' => ['Indie', 'Alternative'],
            'We The Fest' => ['Pop', 'Hip-Hop', 'R&B'],
            'Jazz Gunung Bromo' => ['Jazz'],
            'DCDC Rock Adventure' => ['Rock', 'Metal'],
            'Emo Night Jakarta' => ['Emo', 'Alternative'],
            'Soundrenaline 2024' => ['Alternative', 'Rock'],
            'LaLaLa Festival' => ['Pop', 'Indie'],
            'Reggae Rise Up' => ['Reggae'],
            'Java Hip-Hop Day' => ['Hip-Hop'],
            'Indieground Malang' => ['Indie'],
            'Rock in Semarang' => ['Rock', 'Metal'],
            'Metal Storm' => ['Metal'],
            'Emo Revival Tour' => ['Emo', 'Alternative'],
            'RnB Session' => ['R&B'],
            'Makassar Jazz Fest' => ['Jazz'],
        ];

        foreach ($data as $concert => $genreList) {
            foreach ($genreList as $genre) {
                DB::table('concert_genres')->insert([
                    'concert_id' => $concerts[$concert],
                    'genre_id' => $genres[$genre],
                ]);
            }
        }
    }
}

class TicketsTableSeeder extends Seeder
{
    public function run(): void
    {
        $concerts = DB::table('concerts')->get()->keyBy('name');

        $tickets = [
            'Synchronize Fest 2024' => [
                ['name' => 'General', 'price' => 150000, 'quota' => 500],
                ['name' => 'VIP', 'price' => 300000, 'quota' => 200],
                ['name' => 'VVIP', 'price' => 500000, 'quota' => 100],
            ],
            'We The Fest' => [
                ['name' => 'Early Bird', 'price' => 200000, 'quota' => 300],
                ['name' => 'VIP', 'price' => 750000, 'quota' => 150],
            ],
            'Jazz Gunung Bromo' => [
                ['name' => 'Regular', 'price' => 250000, 'quota' => 100],
                ['name' => 'Premium', 'price' => 400000, 'quota' => 50],
                ['name' => 'VVIP', 'price' => 800000, 'quota' => 20],
            ],
            'DCDC Rock Adventure' => [
                ['name' => 'Rockzone', 'price' => 250000, 'quota' => 400],
                ['name' => 'Backstage Pass', 'price' => 500000, 'quota' => 50],
                ['name' => 'VIP Lounge', 'price' => 1000000, 'quota' => 20],
                ['name' => 'Moshpit', 'price' => 300000, 'quota' => 200],
            ],
            'Emo Night Jakarta' => [
                ['name' => 'Early Bird', 'price' => 100000, 'quota' => 100],
                ['name' => 'General Admission', 'price' => 200000, 'quota' => 200],
            ],
            'Soundrenaline 2024' => [
                ['name' => 'Festival Pass', 'price' => 180000, 'quota' => 300],
                ['name' => 'Super VIP', 'price' => 500000, 'quota' => 100],
            ],
            'LaLaLa Festival' => [
                ['name' => 'Regular', 'price' => 220000, 'quota' => 300],
                ['name' => 'VIP', 'price' => 650000, 'quota' => 150],
            ],
            'Reggae Rise Up' => [
                ['name' => 'Sunset Entry', 'price' => 120000, 'quota' => 200],
                ['name' => 'Full Day', 'price' => 200000, 'quota' => 100],
                ['name' => 'VIP', 'price' => 400000, 'quota' => 50],
            ],
            'Java Hip-Hop Day' => [
                ['name' => 'Standard', 'price' => 100000, 'quota' => 250],
                ['name' => 'Meet & Greet', 'price' => 300000, 'quota' => 50],
            ],
            'Indieground Malang' => [
                ['name' => 'Indie Pass', 'price' => 150000, 'quota' => 300],
                ['name' => 'VIP Lounge', 'price' => 350000, 'quota' => 80],
            ],
            'Rock in Semarang' => [
                ['name' => 'Moshpit', 'price' => 220000, 'quota' => 350],
                ['name' => 'Balcony View', 'price' => 400000, 'quota' => 60],
            ],
            'Metal Storm' => [
                ['name' => 'Hellzone', 'price' => 275000, 'quota' => 200],
                ['name' => 'Meet the Band', 'price' => 500000, 'quota' => 30],
            ],
            'Emo Revival Tour' => [
                ['name' => 'Nostalgia', 'price' => 140000, 'quota' => 180],
                ['name' => 'Premium', 'price' => 280000, 'quota' => 90],
            ],
            'RnB Session' => [
                ['name' => 'Sweet Seat', 'price' => 160000, 'quota' => 120],
                ['name' => 'VIP Sofa', 'price' => 350000, 'quota' => 60],
                ['name' => 'VVIP Table', 'price' => 600000, 'quota' => 30],
            ],
            'Makassar Jazz Fest' => [
                ['name' => 'Lounge', 'price' => 300000, 'quota' => 100],
                ['name' => 'Regular', 'price' => 180000, 'quota' => 200],
            ],
        ];

        foreach ($tickets as $concertName => $ticketOptions) {
            $concert = $concerts[$concertName] ?? null;

            if ($concert) {
                foreach ($ticketOptions as $ticket) {
                    DB::table('tickets')->insert([
                        'concert_id' => $concert->id,
                        'name' => $ticket['name'],
                        'price' => $ticket['price'],
                        'quota' => $ticket['quota'],
                        'sales_start' => Carbon::parse($concert->concert_start)->subDays(14),
                        'sales_end' => Carbon::parse($concert->concert_start)->subDay(),
                    ]);
                }
            }
        }
    }
}
