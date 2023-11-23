<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Carousel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CarouselController extends Controller
{
    public function addNewCarousel(Request $request) {
        $this->validate($request, [
            'carousel_name' => 'required',
            'carousel_image' => 'required|image|mimes:jpg,png,jpeg'
        ]);

        //retrieve file image nya dari request
        $carousel_image = $request->file('carousel_image');

        //nama file carousel nya
        $carousel_file_name =$request->carousel_name.'.'.$carousel_image->extension();

        //save file ke storage di directory carousel_photos
        Storage::putFileAs('images/carousel_photos', $carousel_image, $carousel_file_name);
        
        //directory file nya yang akan disimpan di db
        $image_path = 'images/carousel_photos/'.$carousel_file_name;

        //buat carousel nya
        Carousel::create([
            'name' => $request->carousel_name,
            'image_path' => $image_path
        ]);

        return response(['message' => 'Add new carousel success !'], 200);
    }

    public function getAllCarousel() {
        $carousels = Carousel::all();

        //utk ngembaliin path nya dalam bentuk link, pakai asset()
        $response = $carousels->map(function ($carousel) {
            return [
                'carousel_id' => $carousel->id,
                'carousel_name' => $carousel->name,
                'image_path' => asset($carousel->image_path)
            ];

        });

        return response()->json($response, 200);
    }

    public function deleteCarousel(Request $request) {
        $this->validate($request, [
            'carousel_id' => 'required'
        ]);

        //cek apakah carousel dengan id tsb ada atau engga
        $carousel = Carousel::where('id', $request->carousel_id)->first();

        //kalau gaada, return error
        if (!$carousel) {
            return response(['error' => 'error when deleting carousel!'], 500);
        }
 
        //delete dari storage nya 
        Storage::delete($carousel->image_path);

        //delete dari db
        $carousel->delete();

        return response(['message' => 'Delete carousel success !'], 200);
    }

    public function deleteAllCarousel() {
        //ambil semua carousel nya dulu
        $carousels = Carousel::all();
        
        //delete dari storage utk semua foto carousel nya
        foreach($carousels as $carousel) {
            Storage::delete($carousel->image_path);
        }

        //delete all di db dan reset id nya dari awal lagi
        Carousel::truncate();

        return response(['message' => 'Delete all carousels success !'], 200);
    }
}


