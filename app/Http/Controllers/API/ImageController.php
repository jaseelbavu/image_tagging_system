<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Image;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function upload(Request $request) { 
        $data = $request->validate([ 
            'image' => 'required|mimes:jpg,png|max:2048',
            'visibility' => 'required|numeric',
            'category' => 'string'
        ]); 

        $category_id = NULL;
        if(isset($data['category'])) {
            $category = $data['category'];
            $category_id = Category::whereName($category)->value('id');
            if(!$category_id) {
                $category_id = DB::table('categories')->insertGetId([
                    'name' => $category,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }

        if ($image = $request->file('image')) {
            $user_id = Auth::user()->id;
            $path = $image->store('public/images/'. $user_id);
            $name = $image->getClientOriginalName();

            $uploadImage = new Image();
            $uploadImage->user_id = $user_id;
            $uploadImage->category_id = $category_id;
            $uploadImage->name = $name;
            $uploadImage->path= $path;
            $uploadImage->save();

            [$width, $height] = getimagesize($image);
               
            return response()->json([
                'message' => 'Image uploaded successfully.',
                'url' => Storage::url($path),
                'size' => [
                    'width' => $width,
                    'height' => $height,
                ]
            ]);
   
        }
    }

    public function imageTag(Request $request, $image_id) {
        if(Image::whereId($image_id)->exists()) {
            $data = $request->validate([
                'coords' => 'required|string',
                'label' => 'required|string|max:50',
                'description' => 'string'
            ]);
    
            $data['created_at'] = Carbon::now();
            $data['updated_at'] = Carbon::now();
    
            DB::table('image_tags')
            ->updateOrInsert(
                ['image_id' => $image_id, 'coords' => $data['coords']],
                $data
            );
    
            return response()->json([
                'message' => 'Tags added to the image.',
                'coords' => $data['coords']
            ], 200);
        } else {
            return response()->json([
                'message' => 'No image found.',
            ], 404);
        }
    }
}
