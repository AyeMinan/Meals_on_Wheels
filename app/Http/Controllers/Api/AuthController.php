<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Volunteer;
use App\Models\Caregiver;
use App\Models\Donor;
use App\Models\Member;
use App\Models\Partner;
use Exception;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => ['required', 'unique:users,email', 'email'],
            'password' => ['required', 'min:8'],
            'confirm_password' => ['required', 'min:8'],
            'type' => 'required|string|in:member,caregiver,partner,volunteer,donor',
            'age' => 'required_if:type,member|integer',
            'phone_number' => 'required|string',
            'date_of_birth' => 'required_if:type,member,volunteer|date',
            'address' => 'required|string',
            'gender' => 'required_if:type,member,volunteer|in:male,female,other',
            'emergency_contact_number' => 'required_if:type,member|string',
            'dietary_restriction' => 'required_if:type,member|string',
            'relationship_with_member' => 'required_if:type,caregiver|string',
            'shop_name' => 'required_if:type,partner|string',
            'shop_address' => 'required_if:type,partner|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => '422',
                'error'  => $validator->messages(),
            ]);
        }
        try{
            DB::beginTransaction();
        $user = User::create([
            'user_name' => $request->user_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'type' => $request->type,
        ]);

        // Create a profile instance
        $profile = new Profile([
            'name' => $request->name,
            'address' => $request->address,
            'phone' => $request->phone_number,
        ]);

        if ($request->hasFile('image')) {
            $profile->image = $request->file('image')->store('images');
        }

        // Associate the profile with the user
        $user->profile()->save($profile);

        // Handle different types of users
        switch ($request->type) {
            case 'member':
                $member = new Member([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'gender' => $request->gender,
                    'age' => $request->age,
                    'emergency_contact_number' => $request->emergency_contact_number,
                    'date_of_birth' => $request->date_of_birth,
                    'dietary_restriction' => $request->dietary_restriction,
                ]);
                if ($request->hasFile('image')) {
                    $member->image = $request->file('image')->store('images');
                }
                $user->member()->save($member);
                break;

            case 'caregiver':
                $caregiver = new Caregiver([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'gender' => $request->gender,
                    'date_of_birth' => $request->date_of_birth,
                    'relationship_with_member' => $request->relationship_with_member,
                ]);
                if ($request->hasFile('image')) {
                    $caregiver->image = $request->file('image')->store('images');
                }
                $user->caregiver()->save($caregiver);
                break;

            case 'partner':
                $partner = new Partner([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'shop_name' => $request->shop_name,
                    'shop_address' => $request->shop_address,
                ]);
                if ($request->hasFile('image')) {
                    $partner->image = $request->file('image')->store('images');
                }
                $user->partner()->save($partner);
                break;

            case 'volunteer':
                $volunteer = new Volunteer([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'gender' => $request->gender,
                    'date_of_birth' => $request->date_of_birth,
                ]);
                if ($request->hasFile('image')) {
                    $volunteer->image = $request->file('image')->store('images');
                }
                $user->volunteer()->save($volunteer);
                break;

            default:
                break;
        }
        DB::commit();
    }catch(\Throwable $th){
        DB::rollBack();
        throw new Exception($th->getmessage());
    }


        $response = [
            "message" => "User registered successfully",
            "user" => $user,
        ];

        return response($response, 200);
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
                        "message"=>"User logged in succesfully",
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
