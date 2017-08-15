<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PetCategory extends Model
{
    public $timestamps = false;
    
    public function category()
    {
        return $this->belongsTo('App\Models\Category', 'category_id');
    }
    
    public function pet()
    {
        return $this->belongsTo('App\Models\Pet', 'pet_id');
    }
}
