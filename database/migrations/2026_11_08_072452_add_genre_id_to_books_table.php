<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $genreId = DB::table('genres')
            ->where('name', 'Uncategorized')
            ->value('id');

        if ($genreId === null) {
            $genreId = DB::table('genres')->insertGetId([
                'name' => 'Uncategorized',
                'description' => 'Books without an assigned genre.',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        Schema::table('books', function (Blueprint $table) {
            $table->foreignId('genre_id')
                ->nullable()
                ->after('author_id')
                ->constrained()
                ->restrictOnDelete();
        });

        DB::table('books')
            ->whereNull('genre_id')
            ->update(['genre_id' => $genreId]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropConstrainedForeignId('genre_id');
        });
    }
};
