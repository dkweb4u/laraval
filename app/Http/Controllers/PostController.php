<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function showCreatePost(){
        return view('create-post');
    }
    public function storeNewPost(Request $request){

        echo auth()->id();
        
        $data = $request->validate(
            [
                'title'=> 'required',
                 'body'=>'required'
            ]
        );
        $data['title']= strip_tags($data['title']);
        $data['body']= strip_tags($data['body']);
        $data['user_id'] = auth()->id();

      $post =   Post::Create($data);

      print_r($post);

        return redirect("/post/{$post->id}")->with("success","Post Created Successfully");
    }

    public function viewSinglePost(Post $post){
        $post['body'] = strip_tags(Str::markdown($post->body),'<p>,<ul>,<ol>,<li><strong><em><h3><h4><br>');       
         return view('single-post',['post'=>$post]);
    }
}
