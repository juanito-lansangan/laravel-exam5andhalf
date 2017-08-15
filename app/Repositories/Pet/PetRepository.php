<?php
namespace App\Repositories\Pet;
use Illuminate\Http\Request;
use App\Models\Pet;
use App\Models\PetTag;
use App\Models\PetCategory;
use App\Repositories\Category\ICategoryRepository;
use App\Repositories\Tag\ITagRepository;

class PetRepository implements IPetRepository
{
    private $categoryRepo;
    private $tagRepo;
    
    public function __construct(ICategoryRepository $repo, ITagRepository $tagRepo)
    {
        $this->categoryRepo = $repo;
        $this->tagRepo = $tagRepo;
    }
    
    public function findById($id)
    {
        return Pet::where('id', $id)
        ->with('petCategory.category')
        ->with('petTags.tag')
        ->first();
    }
    
    public function findByTags($tags = array())
    {
        
    }
    
    public function create(Request $request)
    {
        $pet = new Pet();
        $pet->name = $request->name;
        $petCategory = new PetCategory();
        return $this->save($request, $pet, $petCategory);
    }
    
    private function attachCategory($name)
    {
        $category = $this->categoryRepo->findByName($name);
        if($category) {
            return $category->id;
        }
        $category = $this->categoryRepo->create($name);
        return $category->id;
    }
    
    /**
     * I assume the $tags param is delimited by ","
     * e.g tag1,tag2,tag3....
     */
    private function attachTags($tags)
    {
        $tags = explode(",", $tags);
        $tagsToAttach = [];
        
        if(count($tags) == 0) {
            return $tagsToAttach;
        }
        
        foreach($tags as $tag) {
            $tagsToAttach[] = new PetTag([
                'tag_id' => $this->addTag($tag)
            ]);
        }
        
        return $tagsToAttach;
    }
    
    private function addTag($name)
    {
        $tag = $this->tagRepo->findByName($name);
        if($tag) {
            return $tag->id;
        }
        $tag = $this->tagRepo->create($name);
        return $tag->id;
    }
    
    public function update(Request $request)
    {
        $pet = $this->findById($request->id);
        $pet->name = $request->name;
        $petCategory = $pet->petCategory;
        return $this->save($request, $pet, $petCategory);
    }
    
    public function updateById(Request $request, $id)
    {
        $pet = $this->findById($id);
        $pet->name = $request->name;
        $petCategory = $pet->petCategory;
        return $this->save($request, $pet, $petCategory);
    }
    
    private function save(Request $request, $pet, $petCategory)
    {
        $pet->save();
        $category = $this->attachCategory($request->category);
        $petTags = $this->attachTags($request->tags);
        $petCategory = $this->petCategoryInstance($category, $petCategory);
        $pet->petCategory()->save($petCategory);
        $pet->petTags()->saveMany($petTags);
        return $petTags;
        return [
            'status' => 'ok',
            'message' => 'pet successfully saved!',
        ];
    }
    
    private function petCategoryInstance($category, $petCategory)
    {
        $petCategory->category_id = $category;
        return $petCategory;
    }
    
    public function delete($id)
    {
        $pet = $this->findById($id);
        if(!$pet->delete()) {
            return [
                'status' => 'error',
                'message' => 'error in saving pet'
            ];
        }
        
        return [
            'status' => 'ok',
            'message' => 'pet successfully deleted!'
        ];
    }
    
    public function uploadImage(Request $request, $id)
    {
        
    }
}