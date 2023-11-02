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
        // dd($user_id);

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
}
