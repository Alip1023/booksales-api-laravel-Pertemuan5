<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AuthorController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'data' => Author::with('books')->get(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:authors,email'],
            'bio' => ['nullable', 'string'],
        ]);

        return response()->json([
            'data' => Author::create($validated),
        ], 201);
    }

    public function show(Author $author): JsonResponse
    {
        return response()->json([
            'data' => $author->load('books'),
        ]);
    }

    public function update(Request $request, Author $author): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => [
                'sometimes',
                'required',
                'email',
                'max:255',
                Rule::unique('authors', 'email')->ignore($author->id),
            ],
            'bio' => ['sometimes', 'nullable', 'string'],
        ]);

        $author->update($validated);

        return response()->json([
            'data' => $author->load('books'),
        ]);
    }

    public function destroy(Author $author): JsonResponse
    {
        $author->delete();

        return response()->json(null, 204);
    }
}
