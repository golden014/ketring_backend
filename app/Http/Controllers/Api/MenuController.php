<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
            'menu_picture' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'menu_detail' => 'required'
        ]);

        //ambil picture dari menu nya
        $menu_picture = $request->file('menu_picture');
        //nama dari picture = nama menu.extensionnya (cth nama menu: nasi goreng, ext file yg dikasi .png -> namanya jadi nasi goreng.png)
        $picture_name = $request->menu_name.'.'.$menu_picture->extension();
        
        //save file nya ke storage kita di directory menu_photos
        Storage::putFileAs('menu_photos', $menu_picture, $picture_name);
        
        //directory file nya disimpan
        $menu_picture_location = 'images/menu_photos/' . $picture_name;

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

    public function getMenuWithPagination(Request $request) {
        //pastiin setiap hit search keyword baru, current_page nya direset ke page pertama
        
        //page = lagi di page berapa, data_per_page = mau tampilin berapa data dalam satu page
        $this->validate($request, [
            'current_page' => 'required',
            'data_per_page' => 'required',
        ]);

        //set offset nya
        $offset = ($request->current_page - 1) * $request->data_per_page;

        //kalau ada dikasih keyword
        if ($request->keyword) {
            //filter berdasarkan keyword, lalu balikin data nya dengan pagination
            $data = Menu::where('menu_name', 'like', '%'.$request->keyword.'%')->skip($offset)->take($request->data_per_page)->get();
        } 
        
        //kalau gaada dikasih keyword, lgsung ambil aja tanpa filter
        else {
            $data = Menu::skip($offset)->take($request->data_per_page)->get();
        }

        //return menu picture nya sebagai link
        if($data) {
            foreach($data as $menu) {
                $menu['menu_picture'] = asset($menu['menu_picture']);
            }
        }

        //return data
        return response($data, 200);
    }

    public function getMenuCount(Request $request) {
        //ambil count dari menu supaya tahu berapa button dibawah nya (utk paginate nya)
        //jumlah total page = menu_count / data_per_page, round up

        if ($request->keyword) {
            $count = Menu::where('menu_name', 'like', '%'.$request->keyword.'%')->count();
        }

        else {
            $count = Menu::all()->count();
        }

        return response(['count' => $count], 200);
    }

    public function deleteMenuById(Request $request) {
        //todo
    }

    public function getAllMenuNames() {
        //get all menu
        $all_menus = Menu::all();

        //get all the names
        // $menu_names = $all_menus->pluck('menu_name');

        return response($all_menus, 200);
    }

}
