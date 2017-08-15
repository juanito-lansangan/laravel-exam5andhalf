<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    public $timestamps = false;
    
    public function petCategory()
    {
        return $this->hasOne('App\Models\PetCategory', 'pet_id');
    }
    
    public function petTags()
    {
        return $this->hasMany('App\Models\PetTag', 'pet_id');
    }
    
    public function images()
    {
        return $this->hasMany('App\Models\PetImage', 'pet_id');
    }
}
