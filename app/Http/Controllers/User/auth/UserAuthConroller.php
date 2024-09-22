<?php

namespace App\Http\Controllers\User\auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegistrationRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserAuthConroller extends Controller
{
    public function register(RegistrationRequest $request)
    {
        $user=new User();
        $user->name=$request->get('name');
        $user->email=$request->get('email');
        $user->password=Hash::make($request->password);
        $user->save();
        $accessToken=$user->createToken('authtoken')->plainTextToken;
        return response()->json([
            'sucess'=>true,
            'message'=>'User registered succesfully',
            'user'=>$user,
            'token'=>$accessToken
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'=>'email|required',
            'password'=>'required|string|min:6'
        ]);


        $credentials=$request->only(['email','password']);
        $user=User::where('email',$credentials['email'])->first();
        if($user && hash::check($credentials['password'],$user->password))
        {
            $accessToken=$user->createToken('authtoken')->plainTextToken;
            return response()->json([
                'status'=>true,
                'message'=>'Successfully logged in',
                'user'=>$user,
                'token'=>$accessToken
            ],200);
        }
        else
        {
            return response()->json([
                'status'=>false,
                'message'=>'Incorrect email or password',
            ],401);
        }
    }





}