<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Preset extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'category',
        'description',
    ];

    public function templates()
    {
        return $this->hasMany(PresetTemplate::class)->orderBy('order');
    }

    public function getColorArraysAttribute()
    {
        return $this->templates->map(function ($template) {
            return is_array($template->colors) ? $template->colors : json_decode($template->colors);
        })->toArray();
    }
}
