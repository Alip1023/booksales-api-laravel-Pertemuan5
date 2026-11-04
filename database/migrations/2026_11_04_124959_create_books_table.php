<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('books', function (Blueprint $table) {
        $table->id();
        $table->foreignId('author_id')->constrained('authors')->cascadeOnDelete();
        $table->string('title');
        $table->text('description')->nullable();
        $table->decimal('price', 12, 2);
        $table->integer('stock')->default(0);
        $table->string('cover_photo')->nullable();
        $table->timestamps();
    });
}
    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropForeign(['author_id']);
            $table->dropColumn('author_id');
        });
    }
};