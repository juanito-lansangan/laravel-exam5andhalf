<?php
namespace App\Repositories\Tag;
use Illuminate\Http\Request;

interface ITagRepository
{
    public function findByName($name);
    public function findByTags($tags = array());
    public function create($name);
}
