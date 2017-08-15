<?php
namespace App\Repositories\Order;
use Illuminate\Http\Request;

interface IOrderRepository
{
    public function findById($id);
    public function create(Request $request);
    public function delete($id);
}
