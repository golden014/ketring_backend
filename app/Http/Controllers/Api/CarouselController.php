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
        Storage::putFileAs('carousel_photos', $carousel_image, $carousel_file_name);
        
        //directory file nya yang akan disimpan di db
        $image_path = 'carousel_photos/'.$carousel_file_name;

        //buat carousel nya
        Carousel::create([
            'name' => $request->carousel_name,
            'image_path' => $image_path
        ]);

        return response(['message' => 'Add new carousel success !'], 200);
    }

    public function getAllCarousel() {
        return response(Carousel::all(), 200);
    }
}


