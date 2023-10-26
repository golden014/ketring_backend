<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    //insert menu
    public function insertMenu(Request $request) {
        $this->validate($request, [
            'menu_name' => 'required',
            'menu_price' => 'required',
            'modal_price' => 'required',
            'menu_picture' => 'required',
            'menu_detail' => 'required'
        ]);
    }
}
