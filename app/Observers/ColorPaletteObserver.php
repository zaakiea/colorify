<?php

namespace App\Observers;

use App\Models\ColorPalette;

class ColorPaletteObserver
{
    public function created(ColorPalette $colorPalette)
    {
        \Log::info('Palette created: ' . $colorPalette->name);
    }

    public function updated(ColorPalette $colorPalette)
    {
        \Log::info('Palette updated: ' . $colorPalette->name);
    }

    public function deleted(ColorPalette $colorPalette)
    {
        \Log::info('Palette deleted: ' . $colorPalette->name);
    }
}