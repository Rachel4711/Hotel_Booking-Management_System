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
        'image'
    ];

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }
}
