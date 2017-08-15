<?php
namespace App\Repositories\Tag;
use Illuminate\Http\Request;
use App\Models\Tag;
use App\Repositories\Tag\ITagRepository;

class TagRepository implements ITagRepository
{
    public function findByName($name)
    {
        return Tag::where('name', $name)->first();
    }
    
    public function create($name)
    {
        $tag = new Tag();
        $tag->name = $name;
        $tag->save();
        return $tag;
    }
    
    public function findByTags($tags = array())
    {
        
    }
}