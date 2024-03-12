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
            'name' => "required|string",
            'image' => "required|image",
            'phone' => "required|string",
            'address' => "required|string",
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
            ]);
        }

        $userId = Auth::id();

        $profile = new Profile();
        $profile->name = $request->name;
        $profile->phone = $request->phone;
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

    public function update(Request $request, $id)
    {


        $validator = Validator::make($request->all(), [
            'name' => "string",
            'image' => "string|nullable",
            'phone' => "string|nullable",
            'address' => "string|nullable",
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
            ]);
        }

        $profile = Profile::find($id);

        $userId = Auth::id();

        if ($profile->user_id != $userId) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 403);
        }

        $profile->name = $request->name;
        $profile->phone = $request->phone;
        $profile->address = $request->address;

        // info('Authenticated User ID: ' . auth()->id());
        // info('Profile User ID: ' . $profile->user_id);
        // Image
        $path = 'uploads/profile';
        if ($request->hasFile('image')) {
            // Delete old image
            if ($profile->image && File::exists($path . '/' . $profile->image)) {
                File::delete($path . '/' . $profile->image);
            }

            $file = $request->file('image');
            $ext = $file->getClientOriginalExtension();
            $filename = time() . '.' . $ext;
            $file->move($path, $filename);
            $profile->image = $filename;
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
}
