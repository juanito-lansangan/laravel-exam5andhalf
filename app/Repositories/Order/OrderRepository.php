<?php
namespace App\Repositories\Order;
use Illuminate\Http\Request;
use App\Repositories\Order\IOrderRepository;
use App\Repositories\Pet\IPetRepository;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\Pet;
use App\Models\OrderDetail;

class OrderRepository implements IOrderRepository
{
    private $petRepo;

    public function __construct(IPetRepository $repo)
    {
        $this->petRepo = $repo;
    }

    public function findById($id)
    {
        $order = Order::with('details')->find($id);
        $shifmentDate = Carbon::parse($order->shipping_date);
        if(Carbon::now()->gt($shifmentDate)) {
            $order->status = 'delivered';
            $order->save();
            $details = $order->details;
            $pets = array_map(function($pet) { return $pet['pet_id']; }, $details->toArray());
            $this->updatePetStatus($pets, 'sold');
        }
        return $order;
    }

    public function create(Request $request)
    {
        if(!$this->checkPetsAvailability($request->petId)) {
          return 'Invalid Input, Pet is not available';
        }
        
        $order = new Order();
        $order->shipping_date = $request->shipping_date;
        $order->complete = $request->complete;
        $details = $this->detail($request->petId);
        $order->save();
        $order->details()->saveMany($details);
        $pets = array_map(function($pet) { return $pet->pet_id; }, $details);
        $this->updatePetStatus($pets, 'pending');
        return $order;
    }

    private function checkPetsAvailability($pets)
    {
      // check availability
      $pets = $this->petRepo->findByIdWhereIn($pets);
      foreach ($pets as $pet) {
        if($pet->status != 'available') {
          return false;
        }
      }
      return true;
    }

    private function updatePetStatus($pets, $status)
    {

      $pets = $this->petRepo->findByIdWhereIn($pets);
      foreach ($pets as $pet) {
        $pet->status = $status;
        $pet->save();
      }
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
        $details = $order->details;
        $pets = array_map(function($pet) { return $pet['pet_id']; }, $details->toArray());
        $this->updatePetStatus($pets, 'available');
        $order->delete();
        return 'Order successfully deleted';
    }
}
