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
        'policies',
        'cover_image',
        'images',
        'click_count'
    ];

    protected $casts = [
        'images' => 'array',
        'click_count' => 'integer',
    ];

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }
}
