<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('books')->insert([
            [
                'title' => 'Bumi',
                'description' => 'Novel petualangan fantasi.',
                'price' => 95000,
                'stock' => 10,
                'cover_photo' => 'bumi.jpg',
                'author_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Laskar Pelangi',
                'description' => 'Kisah perjuangan anak Belitung.',
                'price' => 85000,
                'stock' => 12,
                'cover_photo' => 'laskar-pelangi.jpg',
                'author_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Aroma Karsa',
                'description' => 'Novel misteri dan petualangan.',
                'price' => 120000,
                'stock' => 8,
                'cover_photo' => 'aroma-karsa.jpg',
                'author_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Lelaki Harimau',
                'description' => 'Novel sastra Indonesia.',
                'price' => 90000,
                'stock' => 7,
                'cover_photo' => 'lelaki-harimau.jpg',
                'author_id' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Bumi Manusia',
                'description' => 'Novel sejarah Indonesia.',
                'price' => 110000,
                'stock' => 9,
                'cover_photo' => 'bumi-manusia.jpg',
                'author_id' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
