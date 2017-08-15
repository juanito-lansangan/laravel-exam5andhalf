<?php
namespace App\Repositories\Order;
use Illuminate\Http\Request;
use App\Repositories\Order\IOrderRepository;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\OrderDetail;

class OrderRepository implements IOrderRepository
{
    public function findById($id)
    {
        return Order::find($id);
    }
    
    public function create(Request $request)
    {
        $order = new Order();
    }
    
    public function delete($id)
    {
        $order = $this->findById($id);
        if(Carbon::now()->gt($order->shipping_date)) {
            return 'Failed to delete, order can be delete before shipping date!';
        }
        $order->delete();
    }
}