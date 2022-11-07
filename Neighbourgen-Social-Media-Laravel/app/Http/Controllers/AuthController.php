<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Http\Request;
use App\Models\Neighbourhood;

class AuthController extends Controller
{
    public function login(){
        return view('pages.login.login')->with('error', '1');
    }
    public function registration(){
        $neighbourhoods = Neighbourhood::all();
        return view('pages.login.registration')->with('neighbourhoods', $neighbourhoods);
    }
    public function loginSubmit(Request $request){
        $validate = $request->validate([
            'userName' => 'required',
            'password' => 'required'
        ],
        [
            'userName.required'=>'Enter Your Name Please!',
            'password.required'=>'Enter Your Password Please!',
        ]
    );
        $userName = $request->input('userName');
        $password = $request->input('password');

        $user = User::where('name', $userName)
            ->where('password', $password)
            ->first();

        if($user){
            $request->session()->put('user', $user->id);
            if($request->remember){
                setcookie('userName', $userName, time()+36000);
                setcookie('password', $password, time()+36000);
            }
            else{
                setcookie('userName', "");
                setcookie('password', "");
            }
            return redirect()->route('home');
        }
        else{
            return redirect()->route('login')->with('error', 'Invalid Credentials');
        }
    }
    public function registrationSubmit(Request $request){
        $validate = $request->validate([
            'name' => 'required',
            'password' => 'required',
            'email' => 'email',
        ],
        [
            'name.required'=>'Enter Your Name Please!',
            'password.required'=>'Enter Your Password Please!',
            'email.email'=>'Enter Your Email Please!',
        ]
    );
        $user = new User();
        $user->name = $request->name;
        $user->password = $request->password;
        $user->email = $request->email;
        $user->neighbourhood_id = $request->neighbourhood;
        $user->save();
        $request->session()->put('user', $user->id);
        return redirect()->route('home');
    }

    public function logout(){
        session()->forget('user');
        return redirect()->route('login');
    }

}
