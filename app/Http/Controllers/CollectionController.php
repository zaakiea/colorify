<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CollectionController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255'
            ]);

            $collection = Collection::create([
                'name' => $validated['name'],
                'user_id' => auth()->id() ?? 1
            ]);

            return response()->json($collection, 201);
        } catch (\Exception $e) {
            \Log::error('Collection creation failed: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to create collection',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function index()
    {
        try {
            $collections = Collection::where('user_id', auth()->id())->orderBy('created_at', 'desc')->get();
            return response()->json($collections);
        } catch (\Exception $e) {
            \Log::error('Error loading collections: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load collections'], 500);
        }
    }

    public function show($slug)
    {
        $collection = Collection::where('slug', $slug)->where('user_id', auth()->id())->firstOrFail();

        $collections = Collection::where('user_id', auth()->id())->orderBy('created_at', 'desc')->get();

        $palettes = $collection->colorPalettes()->orderBy('saved_on', 'desc')->get();

        return view('collection.show', compact('collection', 'collections', 'palettes'));
    }
}
