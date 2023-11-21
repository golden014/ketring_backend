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
        //temp, for debugging
        // $temp = Carousel::first();

        // return response()->file($temp->image_path, ['Content-Type' => 'image/png']);

        $carousels = Carousel::all();

        $response = $carousels->map(function ($carousel) {
            return [
                'carousel_id' => $carousel->id,
                'carousel_name' => $carousel->name,
                'iamge_path' => asset($carousel->image_path)
            ];

        });

        return response()->json($response, 200);
    }

    public function deleteCarousel(Request $request) {
        $this->validate($request, [
            'carousel_id' => 'required'
        ]);

        $status = Carousel::where('id', $request->carousel_id)->delete();

        if (!$status) {
            return response(['error' => 'error when deleting carousel!'], 500);
        }

        return response(['message' => 'Delete carousel success !'], 200);
    }

    public function deleteAllCarousel() {
        
        Carousel::truncate();

        return response(['message' => 'Delete all carousels success !'], 200);
    }
}


