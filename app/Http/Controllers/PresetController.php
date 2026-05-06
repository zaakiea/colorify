<?php

namespace App\Http\Controllers;

use App\Models\Preset;

class PresetController extends Controller
{
    public function index()
    {
        $presets = Preset::with('templates')->get()->groupBy('category');
        return view('preset', compact('presets'));
    }
}
