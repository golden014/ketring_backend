<?php

namespace App\Http\Controllers\File;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    //buat folder utk simpan file2 (menu & carousel)
    public function initFolders(Request $request) {
        $this->validate($request, [
            'password' => 'required'
        ]);

        if ($request->password == 'vasang123') {
            Storage::makeDirectory('menu_photos');
            Storage::makeDirectory('carousel_photos');
            return response(['message' => 'success'], 200);
        }
    }

}
