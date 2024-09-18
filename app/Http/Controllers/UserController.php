<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
        return view('profile-posts',['username'=>$username->username, 'posts' => $username->posts()->latest()->get(),'postCount'=>$username->posts()->count()]);
    }

    public function manageAvatar(){
        return view('manage-avatar');
    }

    public function updateAvatar(Request $request){

        $request->file('avatar')->store('public/avatars');
        return "Stored File";
    }


}
