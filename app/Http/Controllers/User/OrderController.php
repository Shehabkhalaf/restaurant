<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Product;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use ApiResponse;
    public function make_order(OrderRequest $orderRequest)
    {
        $order = Order::create([
           'name' => $orderRequest->name,
           'phone' => $orderRequest->phone,
           'address_1' => $orderRequest->address_1,
           'address_2' => $orderRequest->address_2,
           'before_discount' => 0,
           'after_discount' => 0,
           'status' => 'pending'
        ]);
        if($order){
            return $this->set_order_details($orderRequest->products,$order);
        }else{
            return $this->JsonResponse(500,'Error has been occurred');
        }
    }
    private function set_order_details($products, $order)
    {
        $products = json_decode($products,true);
        $finalPrice = 0;
        $initialPrice = 0;
        foreach ($products as $product){
            $productData = Product::findOrFail($product['id']);
            OrderDetails::create([
                'order_id' => $order->id,
                'product_name' => $productData->title,
                'amount' => $product['amount'],
                'product_price' => $productData->final_price,
                'amount_price' => $productData->final_price * $product['amount']
            ]);
            $finalPrice += $productData->final_price * $product['amount'];
            $initialPrice += $productData->initial_price * $product['amount'];
        }
        $order->update([
           'before_discount' => $initialPrice,
           'after_discount' => $finalPrice
        ]);
        $order = Order::with('order_details')->where('id',$order->id)->first();
        return $this->JsonResponse(201,'Order_created',$order);
    }
}
