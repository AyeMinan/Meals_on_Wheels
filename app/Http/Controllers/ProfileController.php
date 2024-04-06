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
    'name' => $request->input('user.name', $profile->user->name),
    'email' => $request->input('user.email', $profile->user->email),
    'password' => bcrypt($request->input('user.password', $profile->user->password)), // Update password if needed
    'type' => $request->input('user.type', $profile->user->type),

]);

// Load additional details based on user type
switch ($profile->user->type) {
    case 'member':
        if ($profile->user->member) {
            $profile->user->member->update([
                'age' => $request->filled('age') ? $request->input('age') : $profile->user->member->age,
                'health_condition' => $request->input('health_condition', $profile->user->member->health_condition),
                'dietary_requirements' => $request->input('dietary_requirements', $profile->user->member->dietary_requirements),

            ]);

        }
        break;


            case 'caregiver':
                if ($profile->user->caregiver) {
                    $profile->user->caregiver->update([
                        'contact_information' => $request->input('contact_information',$profile->user->caregiver->contact_information),
                        'relationship_to_member' => $request->input('relationship_to_member',$profile->user->caregiver->relationship_to_member)

                    ]);

                }
                break;

            case 'partner':
                if ($profile->user->partner) {
                    $profile->user->partner->update([
                        'partner_type' => $request->input('partner_type',$profile->user->partner->partner_type),
                        'location' => $request->input('location',$profile->user->partner->location),

                    ]);
                }
                break;

            case 'volunteer':
                if ($profile->user->volunteer) {
                    $profile->user->volunteer->update([
                        'volunteer_type' => $request->input('volunteer_type', $profile->user->volunteer->volunteer_type),
                'availability' => $request->input('availability', $profile->user->volunteer->availability),
                    ]);

                }
                break;

            case 'donor':
                if ($profile->user->donor) {
                    $profile->user->donor->update([
                        'donation_amount' => $request->input('donation_amount', $profile->user->donor->donation_amount),
                'donation_date' => $request->input('donation_date', $profile->user->donor->donation_date),

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
