<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;

class UserController extends Controller
{
    public function homepage(){
       if(auth()->check()){
        return view("home-feed");
       }

       else{
        return view("homepage");
       }
    }

    public function register(Request $request){

        $data = $request->validate(
            [
                'username' => 'required',
                'email' => ['required','min:3',"max:30",Rule::unique('users','email')],
                'password' => ['required','confirmed']

            ]
        );

       $user =  User::create($data);

       auth()->login($user);

        return  redirect('/')->with('success','Registered Successfully');

    }

    public function login(Request $request){

        $data = $request->validate(
            [
                'loginusername' => 'required',
                'loginpassword' => 'required'
            ]
        );

        if(auth()->attempt(['username' => $data['loginusername'],'password'=> $data['loginpassword']])){

            $request->session()->regenerate();
            return  redirect('/')->with('success','You Successfully logged in.');
        }

        else{
            return  redirect('/')->with('wrong','Invalid Login');
        }


    }

    public function logout(){
        auth()->logout();
        return redirect('/')->with('success','Logout Successfully');;
    }

    public function profile(User $username){
        return view('profile-posts',['avatar'=>$username->avatar,'username'=>$username->username, 'posts' => $username->posts()->latest()->get(),'postCount'=>$username->posts()->count()]);
    }

    public function manageAvatar(){
        return view('manage-avatar');
    }

    public function updateAvatar(Request $request){

        $request->validate([
            'avatar' => 'required|image|max:3000'
        ]);

        $user = auth()->user();

        $filename = $user->id."-". uniqid() .'.jpg';

        $manager = new ImageManager(new Driver());

        $image =  $manager->read($request->file('avatar'));

        $imgData = $image->cover(120,120)->toJpeg();
        
        Storage::put("public/avatar/".$filename,$imgData);

        $oldphoto = $user->avatar;

        $user->avatar = $filename;

        $user->save();

    //    unlink(public_path($oldphoto));

        Storage::delete("public/".$oldphoto);

    }


}
