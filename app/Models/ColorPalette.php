<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ColorPalette extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'colors',
        'collection_id',
        'saved_on'
    ];

    protected $casts = [
        'colors' => 'array',
        'saved_on' => 'datetime'
    ];

    public function collection()
    {
        return $this->belongsTo(Collection::class);
    }
    public function palettes()
{
    return $this->hasMany(\App\Models\ColorPalette::class, 'collection_id');
}
}