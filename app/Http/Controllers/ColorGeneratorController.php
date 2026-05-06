<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ColorGeneratorController extends Controller
{
    public function index()
    {
        return view('color-generator');
    }

    public function generatePalette(Request $request)
    {
        $color = $request->color;

        if (!preg_match('/^#[a-fA-F0-9]{6}$/', $color)) {
            return response()->json([
                'error' => 'Invalid color format'
            ], 400);
        }

        try {
            // COLOR NAME
            $colorNameResponse = Http::timeout(5)->get(
                'https://palettespro.com/api/v1/color-name',
                ['color' => $color]
            );

            if (!$colorNameResponse->ok()) {
                return response()->json([
                    'error' => 'Color API failed',
                    'debug' => $colorNameResponse->body()
                ], 500);
            }

            $colorData = $colorNameResponse->json();
            $colorLabel = data_get($colorData, 'closest_color.label', 'Unknown Color');

            // PALETTE
            $paletteResponse = Http::timeout(5)->get(
                'https://palettespro.com/api/v1/gradient-stops',
                [
                    'color' => $color,
                    'count' => 11
                ]
            );

            if (!$paletteResponse->ok()) {
                return response()->json([
                    'error' => 'Palette API failed',
                    'debug' => $paletteResponse->body()
                ], 500);
            }

            $paletteData = $paletteResponse->json();

            $palette = $paletteData['to_dark'] ?? null;

            if (!$palette) {
                return response()->json([
                    'error' => 'Palette data empty',
                    'debug' => $paletteData
                ], 500);
            }

            return response()->json([
                'palette' => $palette,
                'colorName' => $colorLabel
            ]);
        } catch (\Throwable $e) {
            \Log::error('Palette error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Server error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
