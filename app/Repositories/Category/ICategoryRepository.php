<?php
namespace App\Repositories\Category;
use Illuminate\Http\Request;

interface ICategoryRepository
{
    public function findByName($name);
    public function create($name);
}
