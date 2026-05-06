<?php

namespace App\Http\Controllers;

use App\Models\Preset;
use App\Models\PresetTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminPresetController extends Controller
{
    public function index()
    {
        $presets = Preset::with(['templates' => function ($q) {
            $q->orderBy('order');
        }])->latest()->paginate(10);

        return view('admin.presets.index', compact('presets'));
    }

    public function create()
    {
        $categories = ['analogic', 'complementary', 'mono', 'triadic', 'tetradic'];
        return view('admin.presets.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateRequest($request);

        $preset = Preset::create([
            'name' => $validated['name'],
            'slug' => $this->generateUniqueSlug($validated['name']),
            'category' => $validated['category'],
            'description' => $validated['description'] ?? null,
        ]);

        $this->syncTemplates($preset, $validated['templates']);

        return redirect()->route('admin.presets.index')
            ->with('success', 'Preset created successfully.');
    }

    public function edit(Preset $preset)
    {
        $preset->load(['templates' => fn($q) => $q->orderBy('order')]);

        $categories = ['analogic', 'complementary', 'mono', 'triadic', 'tetradic'];
        return view('admin.presets.edit', compact('preset', 'categories'));
    }

    public function update(Request $request, Preset $preset)
    {
        $validated = $this->validateRequest($request);

        $preset->update([
            'name' => $validated['name'],
            'slug' => $this->generateUniqueSlug($validated['name'], $preset->id),
            'category' => $validated['category'],
            'description' => $validated['description'] ?? null,
        ]);

        // Replace templates
        $preset->templates()->delete();
        $this->syncTemplates($preset, $validated['templates']);

        return redirect()->route('admin.presets.index')
            ->with('success', 'Preset updated successfully.');
    }

    public function destroy(Preset $preset)
    {
        $preset->templates()->delete(); // ensure cleanup
        $preset->delete();

        return redirect()->route('admin.presets.index')
            ->with('success', 'Preset deleted successfully.');
    }

    public function show(Preset $preset)
    {
        $presets = Preset::with('templates')
            ->orderBy('category')
            ->orderBy('name')
            ->get()
            ->groupBy('category');
        return view('admin.presets.show', compact('preset'));
    }

    // =========================
    // Helper methods
    // =========================

    private function validateRequest(Request $request)
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:analogic,complementary,mono,triadic,tetradic',
            'description' => 'nullable|string',
            'templates' => 'required|array|min:1|max:4',
            'templates.*.name' => 'required|string|max:255',
            'templates.*.colors' => 'required|array|size:5',
            'templates.*.colors.*' => 'required|regex:/^#[0-9A-F]{6}$/i',
        ]);
    }

    private function syncTemplates(Preset $preset, array $templates)
    {
        foreach ($templates as $index => $template) {
            PresetTemplate::create([
                'preset_id' => $preset->id,
                'name' => $template['name'],
                'colors' => $template['colors'], // ensure cast to JSON in model
                'order' => $index,
            ]);
        }
    }

    private function generateUniqueSlug($name, $ignoreId = null)
    {
        $slug = Str::slug($name);
        $original = $slug;
        $count = 1;

        while (
            Preset::where('slug', $slug)
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->exists()
        ) {
            $slug = $original . '-' . $count++;
        }

        return $slug;
    }
}
