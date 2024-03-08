<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\Volunteer;
use App\Models\Caregiver;
use App\Models\Donor;
use App\Models\Member;
use App\Models\Partner;

class AuthController extends Controller
{
    public function register(Request $request){
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
        "message" => "User registered successfully",
        "user" => $user,
    ];

                    return response($response, 200);

    }


    public function logout(){
        try{
        auth()->user()->tokens()->delete();
        return response(["message" => "Logged Out Successfully"]);
    }catch(Exception $e){
        Log::channel('sora_error_log')->error('Logout Error: ' . $e->getMessage());
        return response()->error('Internal Server Error', 500);
    }

    }
    public function login(Request $request){
        try{
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
                        "message"=>"User logged in succesfully",
                        "user"=> $user ];
                }

                return response($response, 200);

}
}catch(Exception $e){
    Log::channel('sora_error_log')->error('Login Error: ' . $e->getMessage());
    return response()->error('Internal Server Error', 500);
}
}

    public function show(){
        if(!Auth::check()){

            return response(["message"=> "Please Login with registered email and password"]);

    }
}
}
