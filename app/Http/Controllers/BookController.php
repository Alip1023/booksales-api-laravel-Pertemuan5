<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'data' => Book::with('author')->get(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'author_id' => ['required', 'integer', 'exists:authors,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['sometimes', 'integer', 'min:0'],
            'cover_photo' => ['nullable', 'string', 'max:255'],
        ]);

        $book = Book::create($validated)->load('author');

        return response()->json(['data' => $book], 201);
    }

    public function show(Book $book): JsonResponse
    {
        return response()->json([
            'data' => $book->load('author'),
        ]);
    }

    public function update(Request $request, Book $book): JsonResponse
    {
        $validated = $request->validate([
            'author_id' => ['sometimes', 'integer', 'exists:authors,id'],
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'price' => ['sometimes', 'required', 'numeric', 'min:0'],
            'stock' => ['sometimes', 'integer', 'min:0'],
            'cover_photo' => ['sometimes', 'nullable', 'string', 'max:255'],
        ]);

        $book->update($validated);

        return response()->json([
            'data' => $book->load('author'),
        ]);
    }

    public function destroy(Book $book): JsonResponse
    {
        $book->delete();

        return response()->json(null, 204);
    }
}
