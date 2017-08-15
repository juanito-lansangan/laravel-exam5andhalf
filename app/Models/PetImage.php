<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PetImage extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'pet_id',
        'image_url',
    ];
    
}
