<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    use ApiResponse;

    public function all_orders()
    {
        $orders = Order::with('order_details')->get();
        return $this->JsonResponse(200,'Here is all orders',$orders);
    }
    public function get_order($id)
    {
        $order = Order::with('order_details')->where('id',$id)->first();
        return $this->JsonResponse(200,'Here is the order',$order);
    }
    public function update_status($id,Request $request)
    {
        $validator = Validator::make($request->all(),['status' => 'required']);
        if($validator->fails()){
            return $this->JsonResponse(422,'Error has been occurred',$validator->messages());
        }
        $order = Order::findOrFail($id);
        $order->update(['status' => $request->status]);
        return $this->JsonResponse(200,'Order status has been updated',$order);
    }
    public function delete($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();
        return $this->JsonResponse(200,'Order has been deleted successfully');
    }
}
