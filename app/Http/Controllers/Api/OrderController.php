<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    //create Order
    public function createOrder(Request $request) {
        
        $this->validate($request, [
            'order_date' => 'required',
            'quantity' => 'required',
            'detail' => 'required',
            'status' => 'required',
            'menu_id' => 'required',
            'allocation_id' => 'required',
            'payment_proof' => 'required|image|mimes:jpg,png,jpeg,gif,svg'
        ]);

        //get current user
        $user_id = Auth::user()->id;

        //ambil file dari request
        $payment_proof_picture = $request->file('payment_proof');

        //format nama file: [date]_[user_id]_[allocation_id]_proof.ext
        $file_name = $request->order_date.'_'.$user_id.'_'.$request->allocation_id.'_proof.'.$payment_proof_picture->extension();

        //save file ke directory payment_proof
        Storage::putFileAs('payment_proof', $payment_proof_picture, $file_name);

        //directory file nya disimpan
        $payment_proof_path = 'images/payment_proof/'.$file_name;


        Order::create([
            'order_date' => $request->order_date,
            'quantity' => intval($request->quantity),
            'detail' => $request->detail,
            'status' => $request->status,
            'menu_id' => intval($request->menu_id),
            'user_id' => $user_id,
            'allocation_id' => intval($request->allocation_id),
            'payment_proof' => $payment_proof_path
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
        $all_status = array('Pending', 'Cancelled', 'On Going', 'Done');

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

    //ambil ongoing order
    public function getOngoingOrders() {
        $ongoing_orders = Order::where('status', 'On Going')->get();
        
        //map utk jadiin response, buat ambil name user dan menu
        $response = $ongoing_orders->map(function ($order) {
            return [
                'order_id' => $order->id,
                'order_date' => $order->order_date,
                'quantity' => $order->quantity,
                'detail' => $order->detail,
                'status' => $order->status,
                'user_name' => $order->user->name,
                'menu_name' => $order->menu->menu_name
            ];
        });

        return response()->json($response, 200);
        
    }
}
