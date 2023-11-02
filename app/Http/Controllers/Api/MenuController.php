<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    //insert menu
    public function insertMenu(Request $request) {
        
        //validasiin request harus ada semua data yg diperlu
        $this->validate($request, [
            'menu_name' => 'required',
            'menu_price' => 'required',
            'modal_price' => 'required',
            'menu_picture' => 'required',
            'menu_detail' => 'required'
        ]);

        //ambil picture dari menu nya
        $menu_picture = $request->file('menu_picture');
        //nama dari picture = nama menu.extensionnya (cth nama menu: nasi goreng, ext file yg dikasi .png -> namanya jadi nasi goreng.png)
        $picture_name = $request->menu_name.'.'.$menu_picture->extension();
        
        //save file nya ke storage kita di directory menu_photos
        Storage::putFileAs('menu_photos', $menu_picture, $picture_name);
        
        //directory file nya disimpan
        $menu_picture_location = 'menu_photos/' . $picture_name;

        //buat menu nya
        Menu::create([
            'menu_name' => $request->menu_name,
            'menu_price' => $request->menu_price,
            'modal_price' => $request->modal_price,
            'menu_picture' => $menu_picture_location,
            'menu_detail' => $request->menu_detail
        ]);

        return response(['message' => 'Create menu success !'], 200);
    }
}
