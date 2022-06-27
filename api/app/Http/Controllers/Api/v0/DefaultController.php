<?php

namespace App\Http\Controllers\Api\v0;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\v0\ImageResource;
use App\Http\Resources\Api\v0\PictureResource;
use App\Models\Picture;
use Illuminate\Http\Request;
use App\Models\District;
use App\Models\Image;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;

class DefaultController extends Controller
{

    public function getAllDistrict(){
        return District::get();
    }

    public function images(){
         $images = Picture::orderBy('id', 'DESC')->get();
        return PictureResource::collection($images);
    }

    public function storeImage(Request $request){
        $image = new Picture();

        $image->fill($request->all());

        if($request->hasFile('image')){
            $image->url =  Storage::put('images', $request->file('image'));
        }
        $image->save();

        $images = Picture::orderBy('id', 'DESC')->get();
        return ImageResource::collection($images);
    }
    public function deleteImage($id){
        $image = Picture::findOrFail($id);
        // $file = asset('storage/').$image->url;

        // if ($image->delete()){
        //     Storage::delete($file);
        // }
        $image->delete();

        $images = Picture::orderBy('id', 'DESC')->get();
        return PictureResource::collection($images);
    }
}
