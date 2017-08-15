<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Repositories\Pet\IPetRepository;

class PetController extends Controller
{
    protected $repository;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(IPetRepository $repository)
    {
        $this->repository = $repository;
    }
    
    public function findById($id)
    {
        $pet = $this->repository->findById($id);
        if(!$pet) {
            return 'Pet not found';
        }
        return $pet;
    }
    
    public function findByTags($tags)
    {
        return $this->repository->findByTags($tags);
    }
    
    public function create(Request $request)
    {
        return $this->repository->create($request);
    }
    
    public function update(Request $request)
    {
        return $this->repository->update($request);
    }
    
    public function updateById(Request $request, $id)
    {
        return $this->repository->updateById($request, $id);
    }
    
    public function delete($id)
    {
        return $this->repository->delete($id);
    }
    
    public function uploadImage(Request $request, $id)
    {
        return $this->repository->uploadImage($request, $id);
    }
}