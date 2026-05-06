<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresetTemplate extends Model
{
    use HasFactory;

    protected $table = 'preset_templates';

    protected $fillable = [
        'preset_id',
        'name',
        'colors',
        'order',
    ];

    protected $casts = [
        'colors' => 'array',
    ];

    public function preset()
    {
        return $this->belongsTo(Preset::class);
    }
}
