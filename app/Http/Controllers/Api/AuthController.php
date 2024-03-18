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
 
class AuthController extends Controller
{

    public function register(Request $request)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required',
        'email' => ['required', 'email'],
        'password' => ['required', 'min:8'],
        'type' => 'required|string|in:member,caregiver,partner,volunteer,donor',
        'age' => 'required_if:type,member|integer',
        'health_condition' => 'required_if:type,member|string',
        'dietary_requirements' => 'required_if:type,member|string',
        'contact_information' => 'required_if:type,caregiver,partner,volunteer|string',
        'relationship_to_member' => 'required_if:type,caregiver|string',
        'partner_type' => 'required_if:type,partner|string',
        'location' => 'required_if:type,partner|string',
        'volunteer_type' => 'required_if:type,volunteer|string',
        'availability' => 'required_if:type,volunteer|string',
        'donation_amount' => 'required_if:type,donor|numeric',
        'donation_date' => 'required_if:type,donor|date',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => '422',
            'error'  => $validator->errors()->first(),
        ]);
    }

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'type' => $request->type,
    ]);

    // Create a profile instance
    $profile = new Profile([
        'image' => $request->image,
        'name' => $request->name,
        'address' => $request->address,
        'phone' => $request->phone,
    ]);

    // Associate the profile with the user
    $user->profile()->save($profile);

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