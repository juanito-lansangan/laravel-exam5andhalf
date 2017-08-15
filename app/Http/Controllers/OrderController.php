<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Repositories\Order\IOrderRepository;

class OrderController extends Controller
{
    protected $repository;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(IOrderRepository $repository)
    {
        $this->repository = $repository;
    }
    
    public function create(Request $request)
    {
        return $this->repository->create($request);
    }
    
    public function findById($id)
    {
        $order = $this->repository->findById($id);
        if(!$order) {
            return 'Order no found';
        }
        return $order;
    }
    
    public function delete($id)
    {
        $order = $this->repository->delete($id);
    }
}