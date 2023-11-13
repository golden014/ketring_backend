<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    //create Order
    public function createOrder(Request $request) {
        $this->validate($request, [
            'order_date',
            'quantity',
            'detail',
            'status',
            'menu_id'
        ]);

        $user_id = Auth::user()->id;
        
        //error
        Order::create([
            'order_date' => $request->order_date,
            'quantity' => $request->quantity,
            'detail' => $request->detail,
            'status' => $request->status,
            'menu_id' => $request->menu_id,
            'user_id' => $user_id,
        ]);

        return response(['message' => 'Create order success']);
    }

    //update order status
    public function updateOrderStatus(Request $request) {
        $this->validate($request, [
            'order_id',
            'new_status'
        ]);

        //isi status2 yg bisa diberikan
        $all_status = array('Pending', 'Cancelled', 'On Progress', 'Done');

        //kalau status yg di send gaada dalam array status, return error
        if (!in_array($request->new_status, $all_status)) {
            return response(['error' => "Invalid status"]);
        }

        //check order dgn matched id
        $order = Order::where('id', $request->order_id)->first();
        //update status
        $order->status = $request->new_status;
        //save
        $order->save();

        return response(['message' => 'Update order status success']);
    }
}
