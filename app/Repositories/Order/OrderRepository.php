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
        $order = Order::find($id);
        $shifmentDate = Carbon::parse($order->shipping_date);
        if(Carbon::now()->gt($shifmentDate)) {
            $order->status = 'delivered';
            $order->save();
        }
        return $order;
    }
    
    public function create(Request $request)
    {
        $order = new Order();
        $order->shipping_date = $request->shipping_date;
        $order->complete = $request->complete;
        $details = $this->detail($request->petId);
        $order->save();
        $order->details()->saveMany($details);
        return $order;
    }
    
    private function detail($pets)
    {
        $orderedPets = [];
        if(count($pets) == 0) {
            return $orderedPets;
        }
        
        foreach($pets as $pet) {
            $orderDetail = new OrderDetail();
            $orderDetail->pet_id = $pet;
            $orderedPets[] = $orderDetail;
        }
        return $orderedPets;
    }
    
    public function delete($id)
    {
        $order = $this->findById($id);
        $shifmentDate = Carbon::parse($order->shipping_date);
        if(Carbon::now()->gt($shifmentDate)) {
            return 'Failed to delete, order can be delete before shipping date!';
        }
        return $order->delete();
    }
}