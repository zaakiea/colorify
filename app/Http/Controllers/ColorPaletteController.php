<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\ColorPalette;
use Illuminate\Http\Request;

class ColorPaletteController extends Controller
{
    // Store a new color palette
    public function store(Request $request, Collection $collection)
    {
        try {
            // Validate the request
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'colors' => 'required|array|min:1'
            ]);

            // Create the palette
            $palette = $collection->colorPalettes()->create([
                'name' => $validated['name'],
                'colors' => $validated['colors'],
                'saved_on' => now()
            ]);

            return response()->json($palette, 201);
            
        } catch (\Exception $e) {
            \Log::error('Palette creation failed: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to create palette'], 500);
        }
    }

    // Update an existing color palette
    public function update(Request $request, Collection $collection, ColorPalette $palette)
    {
        try {
            // Check if the palette belongs to the given collection
            if ($palette->collection_id !== $collection->id) {
                return response()->json(['error' => 'Palette tidak ditemukan'], 404);
            }
    
            // Validate request
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'colors' => 'required|array|min:1'
            ]);
    
            // Update palette
            $palette->update([
                'name' => $validated['name'],
                'colors' => $validated['colors'],
            ]);
    
            return response()->json([
                'message' => 'Palette updated successfully',
                'palette' => $palette
            ], 200);
    
        } catch (\Exception $e) {
            \Log::error('Failed to update palette: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal mengupdate palette'], 500);
        }
    }

public function destroy(Collection $collection, ColorPalette $palette)
{
    try {
        // Verify the palette belongs to the collection
        if ($palette->collection_id !== $collection->id) {
            return response()->json(['error' => 'Palette tidak ditemukan'], 404);
        }

        // Delete the palette
        $palette->delete();

        return response()->json(['message' => 'Palette deleted successfully']);

    } catch (\Exception $e) {
        \Log::error('Failed to delete palette: ' . $e->getMessage());
        return response()->json(['error' => 'Gagal menghapus palette'], 500);
    }
}

}
