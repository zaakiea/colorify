<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trash extends Model
{
    use HasFactory;

    public $table = 'trash';
    protected $fillable = [
        'collection_id',
        'collection_name',
        'collection_slug',
        'user_id',
        'palettes',
    ];

    protected $casts = [
        'palettes' => 'array',
    ];

}
