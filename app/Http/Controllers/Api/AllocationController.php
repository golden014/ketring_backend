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

}
