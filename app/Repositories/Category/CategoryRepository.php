<?php
namespace App\Repositories\Category;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Repositories\Category\ICategoryRepository;

class CategoryRepository implements ICategoryRepository
{
    public function findByName($name)
    {
        return Category::where('name', $name)->first();
    }
    
    public function create($name)
    {
        $category = new Category();
        $category->name = $name;
        $category->save();
        return $category;
    }
}