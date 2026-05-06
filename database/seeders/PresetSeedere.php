<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PresetSeeder extends Seeder
{
    public function run(): void
    {
        $categories = ['analogic', 'complementary', 'mono', 'triadic', 'tetradic'];

        $presets = [
            // ─── ANALOGIC ───────────────────────────────────────────────────────────
            [
                'name'        => 'Sunset Warm',
                'category'    => 'analogic',
                'description' => 'Warm hues flowing from red through orange and into golden yellow.',
                'templates'   => [
                    ['name' => 'Ember Glow',   'colors' => ['#FF4500', '#FF6347', '#FF7F50', '#FFA07A', '#FFD700'], 'order' => 1],
                    ['name' => 'Lava Field',   'colors' => ['#B22222', '#CD5C5C', '#E9967A', '#F4A460', '#DAA520'], 'order' => 2],
                    ['name' => 'Desert Dusk',  'colors' => ['#FF6B35', '#FF8C42', '#FFA552', '#FFBF69', '#FFD8A8'], 'order' => 3],
                ],
            ],
            [
                'name'        => 'Ocean Breeze',
                'category'    => 'analogic',
                'description' => 'Cool tones shifting from cyan through blue to violet.',
                'templates'   => [
                    ['name' => 'Deep Sea',     'colors' => ['#00CED1', '#1E90FF', '#4169E1', '#6A5ACD', '#8A2BE2'], 'order' => 1],
                    ['name' => 'Arctic Wave',  'colors' => ['#87CEEB', '#6495ED', '#4682B4', '#5F4B8B', '#3B0F6E'], 'order' => 2],
                    ['name' => 'Tide Pool',    'colors' => ['#00FFFF', '#00BFFF', '#1E90FF', '#0000CD', '#4B0082'], 'order' => 3],
                ],
            ],

            // ─── COMPLEMENTARY ──────────────────────────────────────────────────────
            [
                'name'        => 'Berry & Lime',
                'category'    => 'complementary',
                'description' => 'Bold contrast between purple-berry tones and zesty lime greens.',
                'templates'   => [
                    ['name' => 'Vivid Splash',  'colors' => ['#6A0DAD', '#9B59B6', '#C39BD3', '#A8D5A2', '#27AE60'], 'order' => 1],
                    ['name' => 'Neon Contrast', 'colors' => ['#8E44AD', '#BDC3C7', '#ECF0F1', '#82E0AA', '#1E8449'], 'order' => 2],
                    ['name' => 'Muted Duo',     'colors' => ['#7D3C98', '#A569BD', '#D7BDE2', '#ABEBC6', '#239B56'], 'order' => 3],
                ],
            ],
            [
                'name'        => 'Fire & Ice',
                'category'    => 'complementary',
                'description' => 'The energetic tension between hot orange-reds and cool teal-blues.',
                'templates'   => [
                    ['name' => 'High Voltage',  'colors' => ['#FF4500', '#FF7043', '#FFCCBC', '#B2EBF2', '#00ACC1'], 'order' => 1],
                    ['name' => 'Pastel Clash',  'colors' => ['#FFAB91', '#FF7043', '#E64A19', '#4DD0E1', '#00838F'], 'order' => 2],
                    ['name' => 'Dark Tension',  'colors' => ['#BF360C', '#E64A19', '#FF6E40', '#006064', '#00838F'], 'order' => 3],
                ],
            ],

            // ─── MONO ───────────────────────────────────────────────────────────────
            [
                'name'        => 'Midnight Blues',
                'category'    => 'mono',
                'description' => 'A refined monochromatic journey through the full spectrum of navy to sky.',
                'templates'   => [
                    ['name' => 'Abyss',         'colors' => ['#03045E', '#0077B6', '#00B4D8', '#90E0EF', '#CAF0F8'], 'order' => 1],
                    ['name' => 'Steel Horizon', 'colors' => ['#1A237E', '#283593', '#3949AB', '#7986CB', '#C5CAE9'], 'order' => 2],
                    ['name' => 'Denim Fade',    'colors' => ['#0D47A1', '#1565C0', '#1976D2', '#64B5F6', '#BBDEFB'], 'order' => 3],
                ],
            ],
            [
                'name'        => 'Forest Shades',
                'category'    => 'mono',
                'description' => 'Earthy greens from deep pine shadows to bright spring leaves.',
                'templates'   => [
                    ['name' => 'Pine Forest',   'colors' => ['#1B4332', '#2D6A4F', '#40916C', '#74C69D', '#B7E4C7'], 'order' => 1],
                    ['name' => 'Jade Mist',     'colors' => ['#004B23', '#006400', '#007200', '#38B000', '#9EF01A'], 'order' => 2],
                    ['name' => 'Sage Palette',  'colors' => ['#344E41', '#3A5A40', '#588157', '#A3B18A', '#DAD7CD'], 'order' => 3],
                ],
            ],

            // ─── TRIADIC ────────────────────────────────────────────────────────────
            [
                'name'        => 'Carnival Lights',
                'category'    => 'triadic',
                'description' => 'A festive triad of red, blue, and yellow in playful balance.',
                'templates'   => [
                    ['name' => 'Primary Bold',  'colors' => ['#D32F2F', '#1976D2', '#FBC02D', '#EF9A9A', '#90CAF9'], 'order' => 1],
                    ['name' => 'Neon Festival', 'colors' => ['#FF1744', '#2979FF', '#FFEA00', '#FF80AB', '#82B1FF'], 'order' => 2],
                    ['name' => 'Vintage Fair',  'colors' => ['#C62828', '#1565C0', '#F9A825', '#FFCDD2', '#BBDEFB'], 'order' => 3],
                ],
            ],
            [
                'name'        => 'Tropical Punch',
                'category'    => 'triadic',
                'description' => 'Energetic triadic mix of magenta, teal, and gold for vibrant designs.',
                'templates'   => [
                    ['name' => 'Punch Mix',     'colors' => ['#E91E63', '#009688', '#FFC107', '#F48FB1', '#80CBC4'], 'order' => 1],
                    ['name' => 'Vivid Tropics', 'colors' => ['#AD1457', '#00695C', '#FF8F00', '#EC407A', '#26A69A'], 'order' => 2],
                    ['name' => 'Pastel Tropics','colors' => ['#F8BBD0', '#B2DFDB', '#FFECB3', '#E91E63', '#009688'], 'order' => 3],
                ],
            ],

            // ─── TETRADIC ───────────────────────────────────────────────────────────
            [
                'name'        => 'Aurora Spectrum',
                'category'    => 'tetradic',
                'description' => 'Four-color harmony inspired by the shifting hues of the northern lights.',
                'templates'   => [
                    ['name' => 'Northern Glow', 'colors' => ['#6A0572', '#1A535C', '#4ECDC4', '#FF6B6B', '#FFE66D'], 'order' => 1],
                    ['name' => 'Aurora Soft',   'colors' => ['#A8DADC', '#457B9D', '#E63946', '#F1FAEE', '#1D3557'], 'order' => 2],
                    ['name' => 'Cosmic Dance',  'colors' => ['#7B2D8B', '#2D8B7B', '#8B7B2D', '#8B2D2D', '#E0C1E8'], 'order' => 3],
                ],
            ],
            [
                'name'        => 'Jewel Box',
                'category'    => 'tetradic',
                'description' => 'Rich jewel-tone tetradic palette of sapphire, ruby, emerald, and amber.',
                'templates'   => [
                    ['name' => 'Gemstone',      'colors' => ['#1A237E', '#880E4F', '#1B5E20', '#E65100', '#9FA8DA'], 'order' => 1],
                    ['name' => 'Precious',      'colors' => ['#283593', '#AD1457', '#2E7D32', '#EF6C00', '#C5CAE9'], 'order' => 2],
                    ['name' => 'Royal Court',   'colors' => ['#0D47A1', '#B71C1C', '#33691E', '#F57F17', '#BBDEFB'], 'order' => 3],
                ],
            ],
        ];

        foreach ($presets as $presetData) {
            $templates = $presetData['templates'];
            unset($presetData['templates']);

            $presetData['slug']       = Str::slug($presetData['name']);
            $presetData['created_at'] = now();
            $presetData['updated_at'] = now();

            $presetId = DB::table('presets')->insertGetId($presetData);

            foreach ($templates as $template) {
                DB::table('preset_templates')->insert([
                    'preset_id'  => $presetId,
                    'name'       => $template['name'],
                    'colors'     => json_encode($template['colors']),
                    'order'      => $template['order'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}