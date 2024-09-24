<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Follow;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function createFollow(User $user){

        // check self follow
        if($user->id == auth()->user()->id){
            return back()->with('wrong',"You Can't follow yourself");
        }

        // check already followed of not

        $existCheck = Follow::where('user_id',auth()->user()->id)->where('followeduser',$user->id)->count();

        if($existCheck){
          return back()->with('wrong',"You Already Follewed");
        }

     
             // follow new user  
 $newFollow = new Follow;
 $newFollow->user_id = auth()->user()->id;
 $newFollow->followeduser = $user->id;
 $newFollow->save();
 return back()->with('success',"Followed Succesfully");
      
       

    }
    public function removeFollow(User $user){
        Follow::where('user_id',auth()->user()->id)->where('followeduser',$user->id)->delete();
        return back()->with('success',"Unfollowed Succesfully");
    }
}
