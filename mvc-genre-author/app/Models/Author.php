<?php

namespace App\Models;

class Author
{
    public static function all(): array
    {
        return [
            [
                'id' => 1,
                'name' => 'Tere Liye',
                'email' => 'tereliye@example.com'
            ],
            [
                'id' => 2,
                'name' => 'Andrea Hirata',
                'email' => 'andrea@example.com'
            ],
            [
                'id' => 3,
                'name' => 'Dee Lestari',
                'email' => 'dee@example.com'
            ],
            [
                'id' => 4,
                'name' => 'Pramoedya Ananta Toer',
                'email' => 'pramoedya@example.com'
            ],
            [
                'id' => 5,
                'name' => 'Eka Kurniawan',
                'email' => 'eka@example.com'
            ],
        ];
    }
}
