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

            $caregivers = caregiver::with('user.profile')->get();

      return $caregivers;
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
        if(!$caregiver){
            return null;
        }
        $caregiverUser = User::where('id', $caregiver->user_id)->first();
        if ($request->hasAny(['first_name', 'last_name', 'gender', 'date_of_birth', 'relationship_with_member'])) {
            $caregiver->first_name = $request->input('first_name');
            $caregiver->last_name = $request->input('last_name');
            $caregiver->gender = $request->input('gender');
            $caregiver->date_of_birth = $request->input('date_of_birth');
            $caregiver->relationship_with_member = $request->input('relationship_with_member');
            $caregiver->save();
        }
        if($request->hasAny( 'email', 'password', 'confirm_password')) {


        $caregiverUser->user_name = $request['user_name'];
        $caregiverUser->email = $request['email'];
        $caregiverUser->password = $request['password'];
        $caregiverUser->confirm_password = $request['confirm_password'];

        $caregiverUser->save();
        }

        if($request->hasAny( 'image', 'address','phone_number')) {

        $caregiverProfile = Profile::where('user_id', $caregiverUser->id)->first();
        $caregiverProfile->user_name = $request['user_name'];
        $caregiverProfile->image = $request['image'];
        $caregiverProfile->address = $request['address'];
        $caregiverProfile->phone_number = $request['phone_number'];


        $caregiverProfile->save();

        }
    }

    public function deleteCaregiver($id)
    {
        $caregiver = Caregiver::where('id', $id)->first();

        if(!$caregiver){
            return null;
        }
        $caregiver->delete();
        $caregiver->user()->delete();
        $caregiver->user()->profile()->delete();
    }
}
