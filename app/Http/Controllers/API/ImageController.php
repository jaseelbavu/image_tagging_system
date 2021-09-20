<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ImageCollection;
use App\Http\Resources\ImageResource;
use App\Http\Resources\ImageTagCollection;
use App\Models\Category;
use App\Models\Image;
use App\Models\ImageTag;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    // To retrieve all public images
    public function AllImages() {
        $images = Image::whereVisibility(1)->get();
        $data = ImageCollection::collection($images);

        return response()->json([
            'images' => $data
        ], 200);
    }

    // To view image details
    public function viewImage($image_id) {
        $image = Image::find($image_id);

        if($image) {
            $data = new ImageResource($image);
            return response()->json([
                'image' => $data
            ], 200);
        } else {
            return response()->json([
                'message' => 'No image found'
            ], 404);
        }
    }

    // To view image tags
    public function viewImageTags($image_id) {
        $image_tags = ImageTag::whereImageId($image_id)->get();

        if($image_tags) {
            $data = ImageTagCollection::collection($image_tags);
            return response()->json([
                'image' => $data
            ], 200);
        } else {
            return response()->json([
                'message' => 'No image found'
            ], 404);
        }
    }

    // Upload image with category
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
                'url' => url(Storage::url($path)),
                'size' => [
                    'width' => $width,
                    'height' => $height,
                ]
            ]);

        }
    }

    // Add tag to images
    public function addImageTag(Request $request, $image_id) {
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
