<?php

namespace Database\Seeders;

use App\Models\Genre;
use Illuminate\Database\Seeder;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ([
            ['name' => 'Uncategorized', 'description' => 'Books without an assigned genre.'],
            ['name' => 'Fantasy', 'description' => 'Cerita dengan unsur sihir dan dunia fantasi.'],
            ['name' => 'Romance', 'description' => 'Cerita yang berfokus pada hubungan cinta.'],
            ['name' => 'Horror', 'description' => 'Cerita yang menegangkan dan menyeramkan.'],
            ['name' => 'Mystery', 'description' => 'Cerita yang berisi teka-teki.'],
            ['name' => 'Science Fiction', 'description' => 'Cerita tentang ilmu pengetahuan dan teknologi.'],
            ['name' => 'Drama', 'description' => 'Cerita tentang kehidupan dan hubungan antarmanusia.'],
            ['name' => 'Historical', 'description' => 'Cerita yang berlatar peristiwa atau masa sejarah.'],
        ] as $genre) {
            Genre::updateOrCreate(['name' => $genre['name']], ['description' => $genre['description']]);
        }
    }
}
