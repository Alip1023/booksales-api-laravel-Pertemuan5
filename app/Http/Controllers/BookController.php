<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use RuntimeException;

class BookController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'data' => Book::with(['author', 'genre'])->get(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'author_id' => ['required', 'integer', 'exists:authors,id'],
            'genre_id' => ['required', 'integer', 'exists:genres,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['sometimes', 'integer', 'min:0'],
            'cover_photo' => [
                'nullable',
                Rule::when(
                    $request->hasFile('cover_photo'),
                    ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
                    ['string', 'max:255'],
                ),
            ],
        ]);

        if ($request->hasFile('cover_photo')) {
            $validated['cover_photo'] = $this->storeCover($request);
        }

        try {
            $book = Book::create($validated);
        } catch (QueryException $exception) {
            $this->deleteStoredCover($validated['cover_photo'] ?? null);

            throw $exception;
        }

        $book->load(['author', 'genre']);

        return response()->json(['data' => $book], 201);
    }

    public function show(Book $book): JsonResponse
    {
        return response()->json([
            'data' => $book->load(['author', 'genre']),
        ]);
    }

    public function update(Request $request, Book $book): JsonResponse
    {
        $validated = $request->validate([
            'author_id' => ['sometimes', 'integer', 'exists:authors,id'],
            'genre_id' => ['sometimes', 'required', 'integer', 'exists:genres,id'],
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'price' => ['sometimes', 'required', 'numeric', 'min:0'],
            'stock' => ['sometimes', 'integer', 'min:0'],
            'cover_photo' => [
                'sometimes',
                'nullable',
                Rule::when(
                    $request->hasFile('cover_photo'),
                    ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
                    ['string', 'max:255'],
                ),
            ],
        ]);

        $previousCover = $book->cover_photo;

        $newCover = null;

        if ($request->hasFile('cover_photo')) {
            $newCover = $validated['cover_photo'] = $this->storeCover($request);
        }

        try {
            $book->update($validated);
        } catch (QueryException $exception) {
            $this->deleteStoredCover($newCover);

            throw $exception;
        }

        if (isset($validated['cover_photo']) && $validated['cover_photo'] !== $previousCover) {
            $this->deleteStoredCover($previousCover);
        }

        return response()->json([
            'data' => $book->load(['author', 'genre']),
        ]);
    }

    public function destroy(Book $book): JsonResponse
    {
        $coverPath = $book->cover_photo;
        $book->delete();
        $this->deleteStoredCover($coverPath);

        return response()->json(null, 204);
    }

    private function storeCover(Request $request): string
    {
        $coverPath = $request->file('cover_photo')->store('book-covers', 'public');

        if ($coverPath === false) {
            throw new RuntimeException('Unable to store the uploaded book cover.');
        }

        return $coverPath;
    }

    private function deleteStoredCover(?string $coverPath): void
    {
        if ($coverPath === null || ! str_starts_with($coverPath, 'book-covers/')) {
            return;
        }

        $disk = Storage::disk('public');

        if (! $disk->exists($coverPath)) {
            return;
        }

        if (! $disk->delete($coverPath)) {
            throw new RuntimeException("Unable to delete the stored book cover: {$coverPath}");
        }
    }
}
