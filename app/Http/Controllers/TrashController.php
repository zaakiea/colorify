<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\ColorPalette;
use App\Models\Trash;

class TrashController extends Controller
{
    public function index()
    {
        $collections = Collection::where('user_id', auth()->id())->get();
        $trashedIds = Trash::where('user_id', auth()->id())->pluck('collection_id')->toArray();
        $trashedCollections = Trash::where('user_id', auth()->id())->get();

        return view('trash', compact('collections', 'trashedIds', 'trashedCollections'));
    }

    public function move($id)
    {
        $collection = Collection::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        $palettes = ColorPalette::where('collection_id', $collection->id)->get();

        Trash::create([
            'collection_id' => $collection->id,
            'collection_name' => $collection->name,
            'collection_slug' => $collection->slug,
            'user_id' => $collection->user_id,
            'palettes' => $palettes->toArray(),
        ]);

        // Delete palettes and collection
        ColorPalette::where('collection_id', $collection->id)->delete();
        $collection->delete();

        return redirect()->route('trash.index')->with('success', 'Collection moved to trash.');
    }



    public function restore($id)
    {
        $trash = Trash::where('collection_id', $id)->where('user_id', auth()->id())->firstOrFail();

        // Restore collection only if it doesn't already exist
        $existing = Collection::find($trash->collection_id);
        if (!$existing) {
            Collection::insert([
                'id' => $trash->collection_id,
                'name' => $trash->collection_name,
                'slug' => $trash->collection_slug,
                'user_id' => $trash->user_id,
            ]);
        }

        // Restore palettes
        foreach ($trash->palettes as $palette) {
            if (!ColorPalette::find($palette['id'])) {
                ColorPalette::insert([
                    'id' => $palette['id'],
                    'collection_id' => $palette['collection_id'],
                    'name' => $palette['name'] ?? 'Untitled',
                    'colors' => is_array($palette['colors']) ? json_encode($palette['colors']) : $palette['colors'],
                    'saved_on' => now(),
                ]);
            }
        }


        // Remove from trash
        $trash->delete();

        return redirect()->route('trash.index')->with('success', 'Collection restored.');
    }

    public function delete($id)
    {
        $trash = Trash::where('collection_id', $id)->where('user_id', auth()->id())->firstOrFail();
        $trash->delete();

        return redirect()->route('trash.index')->with('success', 'Collection permanently deleted.');
    }
}
