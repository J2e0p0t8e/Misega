<?php

namespace App\Http\Controllers;

use App\Models\Manga;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MangaController extends Controller
{
    /**
     * Homepage (GET /)
     */
    public function home(): Response
    {
        $featuredMangas = Manga::with(['volumes'])->where('is_featured', true)->get();
        $latestMangas = Manga::with(['volumes'])->orderBy('created_at', 'desc')->take(8)->get();
        $genres = Manga::distinct()->pluck('genre')->filter()->values();

        return Inertia::render('Home', [
            'featuredMangas' => $featuredMangas,
            'latestMangas' => $latestMangas,
            'genres' => $genres,
        ]);
    }

    /**
     * Dedicated Catalog page (GET /catalogue & GET /catalog)
     */
    public function catalog(Request $request): Response
    {
        $query = Manga::with(['volumes']);

        // Search filter (cross-database case insensitive search)
        if ($request->filled('search')) {
            $search = strtolower($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(title) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(author) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(publisher) LIKE ?', ["%{$search}%"]);
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

        return Inertia::render('Catalogue', [
            'mangas' => $mangas,
            'genres' => $genres,
            'authors' => $authors,
            'filters' => [
                'search' => $request->input('search', ''),
                'genre' => $request->input('genre', ''),
                'author' => $request->input('author', ''),
            ]
        ]);
    }

    /**
     * Manga detail page (GET /manga/{id})
     */
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
