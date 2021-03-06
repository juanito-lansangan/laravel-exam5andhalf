<?php
namespace App\Repositories\Pet;
use Illuminate\Http\Request;
use App\Models\Pet;
use App\Models\PetTag;
use App\Models\PetCategory;
use App\Models\PetImage;
use App\Repositories\Category\ICategoryRepository;
use App\Repositories\Tag\ITagRepository;
use Illuminate\Support\Facades\DB;
// use Intervention\Image\ImageManagerStatic as Image;
use Validator;

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
        ->with('images')
        ->first();
    }

    public function findByIdWhereIn($ids)
    {
      return Pet::whereIn('id', $ids)->get();
    }

    public function findByTags($tags)
    {
        $tags = explode(",", $tags);
        return Pet::whereHas('petTags.tag', function($query) use ($tags) {
            $query->whereIn('name', $tags);
        })
        ->with('petCategory.category')
        ->with('petTags.tag')
        ->get();
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
     * You can use array of tags[0] = Tag1, tags[1] = Tag2
     * or tags = tag1,tag2,tag3....
     */
    private function attachTags($tags)
    {
        if(!is_array($tags)) {
          $tags = explode(",", $tags);
        }

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
        if(!$pet) {
            return 'Pet not found';
        }
        $pet->name = $request->name;
        $petCategory = $pet->petCategory;
        return $this->save($request, $pet, $petCategory);
    }

    public function updateById(Request $request, $id)
    {
        $pet = $this->findById($id);
        if(!$pet) {
            return 'Pet not found';
        }
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

        // delete previous tags of the selected pet
        // to prevent duplicates
        DB::table('pet_tags')->where('pet_id', $pet->id)->delete();

        $pet->petTags()->saveMany($petTags);
        return $pet;
    }

    private function petCategoryInstance($category, $petCategory)
    {
        if(!$petCategory) {
            $petCategory = new PetCategory();
        }
        $petCategory->category_id = $category;
        return $petCategory;
    }

    public function delete($id)
    {
        $pet = $this->findById($id);
        if(!$pet) {
            return 'Pet not found';
        }
        $pet->delete();
        DB::table('pet_tags')->where('pet_id', $pet->id)->delete();
        DB::table('pet_categories')->where('pet_id', $pet->id)->delete();

        return [
            'status' => 'ok',
            'message' => 'pet successfully deleted!'
        ];
    }

    public function uploadImage(Request $request, $id)
    {

        if(!$request->hasFile('file')) {
          return [
            'status' => 'error',
            'message' => 'No files to upload'
          ];
        }

        $path = public_path() . '/uploads';
        $file = $request->file('file');
        $filename = uniqid(). '.' .$file->getClientOriginalExtension();
        $file->move($path, $filename);

        // save to db;
        $petImage = new PetImage();
        $petImage->pet_id = $id;
        $petImage->image_url = url('/') . '/uploads/' . $filename;
        $petImage->save();

        return $petImage;
        //cant use image intervention
        // GD Library extension not available with this PHP installation.
        // $image = Image::make($file->getRealPath());
        // $image->save($destination);
    }
}
