<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    public function register(Request $request){
 
        try{
        $validator = Validator::make($request->all(), [
            "name" => "required",
            "email"=> ["required","email"],
            "password"=> ["required","min:8"] ]);
            if($validator->fails()){
                return response()->json([
                    "status" => "422",
                    "error"=> $validator->errors()->first() ]);
            }else{
                $user = User::create([
                    "name"=> $request->name,
                    "email"=> $request->email,
                    "password"=> Hash::make($request->password),
                ]);
 
                $token = $user->createToken("internProjectToken")->plainTextToken;
 
                $response = [
                    "token" => $token,
                    "user" => $user ];
 
                    return response($response, 200);
 
    }}catch(Exception $e){
        Log::channel('sora_error_log')->error('Login Error: ' . $e->getMessage());
        return response()->error('Internal Server Error', 500);
    }
 
    }
    

    public function logout(){
        auth()->user()->tokens()->delete();
        return response(["message" => "Logged Out Successfully"]);

    }
    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            "email"=> ["required","email"],
            "password"=> ["required","min:8"] ]);
            if($validator->fails()){
                return response()->json([
                    "status"=> "422",
                    "error"=> $validator->errors()->first() ]);

                }else{
                    $user = User::where("email", $request->email)->first();

                    if(!$user || !Hash::check($request->password, $user["password"])){
                        return response(['message' => 'Invalid Credentials'], 401);
                }else{
                    $token = $user->createToken("internProjectToken")->plainTextToken;
                    $response = [
                        "token"=> $token,
                        "user"=> $user ];
                }

                return response($response, 200);
}
}

    public function show(){
        if(!Auth::check()){

            return response(["message"=> "Please Login with registered email and password"]);

    }
}
}
