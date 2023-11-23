<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Allocation;
use Illuminate\Http\Request;

class AllocationController extends Controller
{
     //allocate menu
     public function allocateMenu(Request $request) {
        
        //validate
        $this->validate($request, [
            'allocation_date' => 'required|unique:allocations',
            'end_order_date' => 'required',
            'end_order_time' => 'required',
            'menu_id' => 'required'
        ]);

        //create allocation
        $temp = Allocation::create([
            'allocation_date' => $request->allocation_date,
            'end_order_date' => $request->end_order_date,
            'end_order_time' => $request->end_order_time,
            'menu_id' => $request->menu_id
        ]);

        

        return response(['message' => 'Allocate menu success ! - '.$temp->menu->menu_name], 200);
    }

    //upcoming menu
    public function getUpcomingMenu() {
        
        $curr = now();
        $currDate = $curr->toDateString();
        $currTime = $curr->toTimeString();

        //ambil yang end order date nya > dari date sekarang atau yang end order date nya adalah date sekarang tapi end order time nya <= end order time
        $upcomingAllocations = Allocation::where('end_order_date', '>', $currDate)->orWhere(function($query) use ($currDate, $currTime) {
            $query->where('end_order_date', '=', $currDate)->where('end_order_time', '<=', $currTime);
        })->get();

        //return barengan dengan detail dari menu nya
        $response = $upcomingAllocations->map(function ($allocation) {
            return [
                'allocation_id' => $allocation->id,
                'allocation_date' => $allocation->allocation_date,
                'end_order_date' => $allocation->end_order_date,
                'end_order_time' => $allocation->end_order_time,
                'menu_id' => $allocation->menu_id,
                'menu_name' => $allocation->menu->menu_name,
                'menu_price' => $allocation->menu->menu_price,
                'menu_detail' => $allocation->menu->menu_detail,
                'menu_picture' => asset($allocation->menu->menu_picture)
            ];
        });

        return response($response, 200);

    }

}
