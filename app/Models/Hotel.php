<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    protected $table = 'hotels';
    protected $fillable = [
        'name',
        'description',
        'address',
        'state',
        'category',
        'image',
        'policies',
        'cover_image',
        'images'
    ];

    protected $casts = [
        'images' => 'array',
    ];

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }
}
