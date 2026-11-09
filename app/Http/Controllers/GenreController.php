<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GenreController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'data' => Genre::with('books')->get(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:genres,name'],
            'description' => ['nullable', 'string'],
        ]);

        return response()->json([
            'data' => Genre::create($validated),
        ], 201);
    }

    public function show(Genre $genre): JsonResponse
    {
        return response()->json([
            'data' => $genre->load('books'),
        ]);
    }

    public function update(Request $request, Genre $genre): JsonResponse
    {
        $validated = $request->validate([
            'name' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('genres', 'name')->ignore($genre->id),
            ],
            'description' => ['sometimes', 'nullable', 'string'],
        ]);

        $genre->update($validated);

        return response()->json([
            'data' => $genre->load('books'),
        ]);
    }

    public function destroy(Genre $genre): JsonResponse
    {
        if ($genre->books()->exists()) {
            return response()->json([
                'message' => 'Genre cannot be deleted while books are assigned to it.',
            ], 409);
        }

        $genre->delete();

        return response()->json(null, 204);
    }
}
