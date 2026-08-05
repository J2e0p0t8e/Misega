<?php

namespace App\Http\Controllers;

use App\Models\Manga;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MangaController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Manga::with(['volumes']);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'ilike', "%{$search}%")
                  ->orWhere('author', 'ilike', "%{$search}%")
                  ->orWhere('publisher', 'ilike', "%{$search}%");
            });
        }

        // Genre filter
        if ($request->filled('genre')) {
            $query->where('genre', $request->input('genre'));
        }

        // Author filter
        if ($request->filled('author')) {
            $query->where('author', $request->input('author'));
        }

        $mangas = $query->orderBy('title', 'asc')->get();
        $genres = Manga::distinct()->pluck('genre')->filter()->values();
        $authors = Manga::distinct()->pluck('author')->filter()->values();

        $featuredMangas = Manga::with(['volumes'])->where('is_featured', true)->get();

        return Inertia::render('Catalog', [
            'mangas' => $mangas,
            'genres' => $genres,
            'authors' => $authors,
            'featuredMangas' => $featuredMangas,
            'filters' => [
                'search' => $request->input('search', ''),
                'genre' => $request->input('genre', ''),
                'author' => $request->input('author', ''),
            ]
        ]);
    }

    public function show(int $id): Response
    {
        $manga = Manga::with(['volumes'])->findOrFail($id);
        
        $relatedMangas = Manga::where('genre', $manga->genre)
            ->where('id', '!=', $manga->id)
            ->take(4)
            ->get();

        return Inertia::render('MangaDetail', [
            'manga' => $manga,
            'relatedMangas' => $relatedMangas,
        ]);
    }
}
