<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;
use App\Models\Token;
use DateTime;
use App\Models\Otp;
use App\Mail\MyMail;
use Mail;
use App\Models\Neighbourhood;

function createToken($id){
    $token = new Token();
    $token->token = Str::random(60);
    $token->user_id = $id;
    $token->created_at = new DateTime();
    $token->save();
    return $token;
}

class AuthApiController extends Controller
{
    public function login(Request $request){
        $user = User::where('name', $request->username)->where('password', $request->password)->first();
        if($user){
            return response()->json(createToken($user->id));
        }
        else{
            return response()->json('Invalid Credentials');
        }
    }
    public function registration(Request $request){
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
        return response()->json(createToken($user->id)); 
    }
    public function verify(Request $request){
        $otp = $request->otp;
        $data = Otp::where('otp', $otp)->where('isValid', false)->first();
        if($data){
            User::where('email', $data->email)->update(['verified' => true]);
            Otp::where('otp', $data->otp)->update(['isValid' => true]);
            return $data;
        }
        return "Otp Invalid";
    }
    public function logout(Request $request){
        $header = $request->header('Authorization');
        $token = Token::where('isValid', true)->where('token', $header)->first();
        if($token){
            $token->isValid = false;
            $token->save();
            return response()->json('Logged Out');
        }
        return response()->json('Invalid Token');
    }
    public function neighbourhoods(){
        $neighbourhoods = Neighbourhood::all();
        return response()->json($neighbourhoods);
    }
}
