<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AuthorSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('authors')->insert([
            [
                'name' => 'Tere Liye',
                'email' => 'tere@example.com',
                'bio' => 'Penulis novel Indonesia.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Andrea Hirata',
                'email' => 'andrea@example.com',
                'bio' => 'Penulis Laskar Pelangi.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Dee Lestari',
                'email' => 'dee@example.com',
                'bio' => 'Penulis dan musisi Indonesia.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Eka Kurniawan',
                'email' => 'eka@example.com',
                'bio' => 'Penulis karya sastra Indonesia.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pramoedya Ananta Toer',
                'email' => 'pram@example.com',
                'bio' => 'Sastrawan Indonesia.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
