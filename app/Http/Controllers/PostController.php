<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Image;
use Auth;
use Exception;

class PostController extends Controller
{
    public function AddPost(Request $request){
     
    try {
        $validator =  Validator::make($request->all(),[
            'title' => 'required|unique:posts,title',
            'desc' => 'required',
            'image' => 'mimes:png,jpg,jpeg|max:1024',
        
         ]);

         if($validator->fails()){
             return response()->json(
              ['errors' => $validator->errors()]
             );
         }
        
         $image = $request->file('image');
         $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
          $destinationPath = 'image/blog_img';
          if(!File::isDirectory($destinationPath)){
             File::makeDirectory($destinationPath, 007, true, true);
          }

          Image::make($image)->resize(300,300, function($constrain){
           $constrain->aspectRatio();
       })->save($destinationPath.'/'.$name_gen);
         $img_path = $destinationPath.'/'.$name_gen;

         $post = new Post();
         $post->title = $request->title;
         $post->desc= $request->desc;
         $post->image = $img_path;
         $post->save();
         return response([
           'message' => 'Post created successfully',
           'post' => $post,
         ],200);

    }catch (Exception $ex) {
        return response([
            'messsage' => $ex->getMessage()
          ],401);
     }
    }
}
