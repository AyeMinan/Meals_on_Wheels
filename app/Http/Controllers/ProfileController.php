<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Profile;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
class ProfileController extends Controller
{
    public function showAuthenticatedUserProfile()
    {
        $userId = Auth::id();

        $profile = Profile::where('user_id', $userId)->first();

        if (!$profile) {
            return response()->json([
                'message' => 'Profile not found for the authenticated user.',
            ], 404);
        }

          // Load additional details based on user type
    switch ($profile->user->type) {
        case 'member':
            $profile->load('user.member');
            break;
        case 'caregiver':
            $profile->load('user.caregiver');
            break;
        case 'partner':
            $profile->load('user.partner');
            break;
        case 'volunteer':
            $profile->load('user.volunteer');
            break;
        case 'donor':
            $profile->load('user.donor');
            break;

    }

    return response()->json($profile);


    }

    public function index()
    {
        $profiles = Profile::all();
        return response()->json($profiles);
    }

    public function show($id)
    {
        $profile = Profile::find($id);

        if (!$profile) {
            return response()->json("There is no profile", 404);
        }

        return response()->json($profile);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_name' => "required|string",
            'image' => "image|nullable",
            'phone_number' => "required|string",
            'address' => "required|string",
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
            ]);
        }

        $userId = Auth::id();

        $profile = new Profile();
        $profile->user_name = $request->user_name;
        $profile->phone_number = $request->phone_number;
        $profile->address = $request->address;
        $profile->user_id = $userId;

        // Image
        $path = 'uploads/profile';
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $ext = $file->getClientOriginalExtension();
            $filename = time() . '.' . $ext;
            $file->move($path, $filename);
            $profile->image = $filename;
        }

        $profile->save();

        return response()->json([
            'message' => 'Profile created successfully',
            'status' => 201,
        ]);
    }


    public function update(Request $request)
    {

        $userId = Auth::id();

        $profile = Profile::where('user_id', $userId)->first();

        // Check if the authenticated user owns the profile
        if (Auth::id() !== $profile->user_id) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 403);
        }


$profile->update([
    'user_name' => $request->input('user_name', $profile->user_name),
    'phone_number' => $request->input('phone_number', $profile->phone_number),
    'address' => $request->input('address', $profile->address),
    'image' => $request->input('image', $profile->image )

]);



$profile->user->update([
    'user_name' => $request->input('user.user_name', $profile->user->user_name),
    'email' => $request->input('user.email', $profile->user->email),
    'password' => bcrypt($request->input('user.password', $profile->user->password)), // Update password if needed
    'type' => $request->input('user.type', $profile->user->type),

]);

// Load additional details based on user type
switch ($profile->user->type) {
    case 'member':
        if ($profile->user->member) {
            $profile->user->member->update([
                'first_name' => $request->filled('first_name') ? $request->input('first_name') : $profile->user->member->first_name,
                'last_name' => $request->input('last_name', $profile->user->member->last_name),
                'gender' => $request->input('gender', $profile->user->member->gender),
                'age' => $request->input('age', $profile->user->member->age),
                'emergency_contact_number' => $request->input('emergency_contact_number', $profile->user->member->emergency_contact_number),
                'date_of_birth' => $request->input('date_of_birth', $profile->user->member->date_of_birth),
                'dietary_restriction' => $request->input('dietary_restriction', $profile->user->member->dietary_restriction),
                'user_id' => $userId
            ]);

        }
        break;


            case 'caregiver':
                if ($profile->user->caregiver) {
                    $profile->user->caregiver->update([
                        'first_name' => $request->input('first_name',$profile->user->caregiver->first_name),
                        'last_name' => $request->input('last_name',$profile->user->caregiver->last_name),
                        'date_of_birth' => $request->input('date_of_birth',$profile->user->caregiver->date_of_birth),
                        'relationship_with_member' => $request->input('relationship_with_member',$profile->user->caregiver->relationship_with_member),
                        'gender' => $request->input('gender',$profile->user->caregiver->gender),
                        'user_id' => $userId

                    ]);

                }
                break;

            case 'partner':
                if ($profile->user->partner) {
                    $profile->user->partner->update([
                        'first_name' => $request->input('first_name',$profile->user->partner->first_name),
                        'last_name' => $request->input('last_name',$profile->user->partner->last_name),
                        'shop_name' => $request->input('shop_name',$profile->user->partner->shop_name),
                        'shop_address' => $request->input('shop_address',$profile->user->partner->shop_address),
                        'user_id' => $userId

                    ]);
                }
                break;

            case 'volunteer':
                if ($profile->user->volunteer) {
                    $profile->user->volunteer->update([
                'first_name' => $request->input('first_name', $profile->user->volunteer->first_name),
                'last_name' => $request->input('last_name', $profile->user->volunteer->last_name),
                'gender' => $request->input('gender', $profile->user->volunteer->gender),
                'date_of_birth' => $request->input('date_of_birth', $profile->user->volunteer->date_of_birth),
                'user_id' => $userId
                    ]);

                }
                break;

            case 'donor':
                if ($profile->user->donor) {
                    $profile->user->donor->update([
                        'first_name' => $request->input('first_name', $profile->user->donor->first_name),
                'last_name' => $request->input('last_name', $profile->user->donor->last_name),
                'gender' => $request->input('gender', $profile->user->donor->gender),
                'date_of_birth' => $request->input('date_of_birth', $profile->user->donor->date_of_birth),
                'user_id' => $userId

                    ]);
                
                }
                break;
        }

        $profile->save();

        return response()->json([
            'message' => 'Profile updated successfully',
            'status' => 200,
        ]);
    }


    public function destroy($id)
    {
        $profile = Profile::find($id);

        if (!$profile) {
            return response()->json([
                'message' => 'Profile not found.',
            ], 404);
        }

        $profile->delete();

        return response()->json([
            'message' => 'Profile deleted successfully.',
        ], 200);
    }
    public function upload(Request $request){
        $validator = Validator::make($request->all(),[
            'image' => 'required|image|mimes:jpeg,png,jpg,gif',
        ]);
        $validatorMessage = collect($validator->errors())->flatMap(function ($e, $field){
            return [$field => $e[0]];
        });
        if($validator->fails()){
            return response()->json([
                'status' => '422',
                'error'  => $validatorMessage
            ],422);
        }
            // Handle image upload
            $path = 'uploads/profile';
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $ext = $file->getClientOriginalExtension();
                $filename = time() . '.' . $ext;
                $file->move($path, $filename);
                $imagePath = $path . '/' . $filename;
            }
            return response()->json([
                    "message" => "Upload Successful",
                    "imagePath" => $imagePath
                ],200);
    }
}
