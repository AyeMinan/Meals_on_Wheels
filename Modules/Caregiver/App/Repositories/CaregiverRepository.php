<?php

namespace Modules\Caregiver\App\Repositories;

use App\Models\Caregiver;
use App\Models\Member;
use App\Models\Profile;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\Caregiver\App\Http\Requests\CaregiverRequest;
use Modules\Caregiver\App\Interfaces\CaregiverRepositoryInterface;

class CaregiverRepository implements CaregiverRepositoryInterface
{

    public function getAllCaregivers(){
        
            $caregiver = caregiver::with('user')->get();
            $caregiverUsers = User::where('type', 'caregiver')->get();
            foreach($caregiverUsers as $caregiverUser){
                $profile = Profile::where('user_id', $caregiverUser->id)->get();
                if ($profile) {
                    $caregiverProfile[] = $profile;
                }

        }
      return [$caregiver, $caregiverProfile];
    }
    public function storeCaregiver(Request $request, $validatedData){

        // dd($validatedData);

            try{
            DB::beginTransaction();
            $user = User::create([
                'user_name' => $validatedData['user_name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'confirm_password' => Hash::make($validatedData['confirm_password']),
                'type' => $validatedData['type'],
            ]);


            if($validatedData['password'] !== $validatedData['confirm_password']){
                return response()->json("Password doesn't match");
            }

            // Create a profile instance
            $profile = new Profile([
                'user_name' => $validatedData['user_name'],
                'image'=> $validatedData['image'],
                'address' => $validatedData['address'],
                'phone_number' => $validatedData['phone_number'],
            ]);

            $path = 'uploads/profile';
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $ext = $file->getClientOriginalExtension();
                $filename = time() . '.' . $ext;
                $file->move($path, $filename);
                $profile->image = $filename;
            }

            // Associate the profile with the user
            $user->profile()->save($profile);

            if ($validatedData['type'] === 'caregiver') {
                $caregiver = new Caregiver([
                    'first_name' =>  $validatedData['first_name'],
                    'last_name' =>  $validatedData['last_name'],
                    'gender' =>   $validatedData['gender'],
                    'date_of_birth'=>  $validatedData['date_of_birth'],
                    'relationship_with_member' => $validatedData['relationship_with_member'],
                ]);

                // Save the caregiver instance

                $user->caregiver()->save($caregiver);
            }
            DB::commit();
        }catch (\Throwable $th){
            DB::rollBack();
            throw new Exception($th->getmessage());
        }

    }

    public function updateCaregiver(Request $request, $id){
        $caregiver = Caregiver::where('id', $id)->first();
        $caregiverUser = User::where('id', $caregiver->user_id)->first();
        $caregiverProfile = Profile::where('user_id', $caregiverUser->id)->first();


        if(!$caregiver && !$caregiverUser && !$caregiverProfile){
            return null;
        }
        $caregiverData = $request->validate([
            'first_name' => ['required_if:type,caregiver|string'],
            'last_name' => ['required_if:type,caregiver|string'],
            'gender' => ['required_if:type,caregiver|string'],
            'date_of_birth' => ['required_if:type,caregiver|date', 'date'],
            'relationship_with_member' => 'required_if:type,caregiver|string',
        ]);

        dd($caregiverData);
        // $caregiver->first_name = $request['first_name'];
        // $caregiver->last_name = $request['last_name'];
        // $caregiver->gender = $request['gender'];
        // $caregiver->date_of_birth = $request['date_of_birth'];
        // $caregiver->relationship_with_member = $request['relationship_with_member'];

        $caregiver->update($caregiverData);

        $caregiverProfileData = $request->validate([
            'user_name' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'address' => ['required'],
            'phone_number' => ['required'],
        ]);

        // $caregiverProfile->user_name = $request['user_name'];
        // $caregiverProfile->image = $request['image'];
        // $caregiverProfile->address = $request['address'];
        // $caregiverProfile->phone_number = $request['phone_number'];

        $path = 'uploads/profile';
        if ($request->hasFile('image')) {
            // Delete old image
            if ($caregiverProfile->image && File::exists($path . '/' . $caregiverProfile->image)) {
                File::delete($path . '/' . $caregiverProfile->image);
            }

            $file = $request->file('image');
            $ext = $file->getClientOriginalExtension();
            $filename = time() . '.' . $ext;
            $file->move($path, $filename);
            $caregiverProfile->image = $filename;
        }

        $caregiverProfile->update($caregiverProfileData);

        $caregiverUserData = $request->validate([
            'user_name' => 'required',
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8'],
            'confirm_password' => ['required', 'min:8'],
        ]);
        // $caregiverUser->user_name = $request['user_name'];
        // $caregiverUser->email = $request['email'];
        // $caregiverUser->password = $request['password'];
        // $caregiverUser->confirm_password = $request['confirm_password'];

        $caregiverUser->update($caregiverUserData);
    }

    public function deleteCaregiver($id)
    {
        $caregiver = Caregiver::where('id', $id)->first();

        if(!$caregiver){
            return null;
        }
        $caregiver->delete();
        $caregiver->profile()->delete();
        $caregiver->user()->delete();
    }
}
