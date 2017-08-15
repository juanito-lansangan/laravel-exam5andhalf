<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PetTag extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'tag_id',
    ];
    
    public function tag()
    {
        return $this->belongsTo('App\Models\Tag', 'tag_id');
    }
}
