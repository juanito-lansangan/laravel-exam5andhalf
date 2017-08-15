<?php
namespace App\Repositories\Pet;
use Illuminate\Http\Request;

interface IPetRepository
{
    public function findById($id);
    public function findByTags($tags);
    public function create(Request $request);
    public function update(Request $request);
    public function updateById(Request $request, $id);
    public function delete($id);
    public function uploadImage(Request $request, $id);
}
