<?php

namespace App\Models;

class Genre
{
    public static function all(): array
    {
        return [
            [
                'id' => 1,
                'name' => 'Fantasy',
                'description' => 'Cerita dengan unsur sihir dan dunia fantasi'
            ],
            [
                'id' => 2,
                'name' => 'Romance',
                'description' => 'Cerita yang berfokus pada hubungan cinta'
            ],
            [
                'id' => 3,
                'name' => 'Horror',
                'description' => 'Cerita yang menegangkan dan menyeramkan'
            ],
            [
                'id' => 4,
                'name' => 'Mystery',
                'description' => 'Cerita yang berisi teka-teki'
            ],
            [
                'id' => 5,
                'name' => 'Science Fiction',
                'description' => 'Cerita tentang ilmu pengetahuan dan teknologi'
            ],
        ];
    }
}
