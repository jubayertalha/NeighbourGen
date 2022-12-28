<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Http\Request;
use App\Models\Neighbourhood;
use App\Mail\MyMail;
use Mail;
use App\Models\Otp;
use DateTime;

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
        $otp = rand(100000, 999999);
        $data = new Otp();
        $data->otp = $otp;
        $data->email = $request->email;
        $data->created_at = new DateTime();
        $data->isValid = false;
        $data->save();
        
        $emailAddress = $request->email;
        $details = [
            'tittle' => 'Email Verification',
            'OTP' => $otp,
        ];
        Mail::to($emailAddress)->send(new MyMail($details));
        $user = new User();
        $user->name = $request->name;
        $user->password = $request->password;
        $user->email = $request->email;
        $user->neighbourhood_id = $request->neighbourhood;
        $user->save();
        $request->session()->put('user', $user->id);
        return view('pages.mail.otp');  
    }

    public function otpCheckSubmit(Request $request){
        $otp = $request->input('otp');
        $currentTime = new DateTime();
        $data = Otp::where('otp', $otp)->where('isValid', false)->first();
        $expired_at = $data->expired_at;

        if($data){
            User::where('email', $data->email)->update(['verified' => true]);
            Otp::where('otp', $data->otp)->update(['isValid' => true]);
            return redirect()->route('home');
        }
        return "Otp Invalid";

    }

    public function logout(){
        session()->forget('user');
        return redirect()->route('login');
    }

}
