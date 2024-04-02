<?php

namespace Modules\Member\Repositories;

use App\Models\Member;
use App\Models\Profile;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Modules\Member\App\Interfaces\MemberRepositoryInterface;

class MemberRepository implements MemberRepositoryInterface
{

    public function getAllMembers(){

            $members = Member::with('user.profile')->get();

      return $members;
    }
    public function storeMember(Request $request, $validatedData){
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
            // // Associate the profile with the user
            $user->profile()->save($profile);

            if ($validatedData['type'] === 'member') {
                    $member = new  Member([
                        'first_name' =>  $validatedData['first_name'],
                        'last_name' =>  $validatedData['last_name'],
                        'gender' =>   $validatedData['gender'],
                        'date_of_birth'=>  $validatedData['date_of_birth'],
                        'age' =>  $validatedData['age'],
                        'emergency_contact_number'=> $validatedData['emergency_contact_number'],
                        'dietary_restriction' =>  $validatedData['dietary_restriction'],
                    ]);




               $user->member()->save($member);

             }
            DB::commit();
        }catch (\Throwable $th){
            DB::rollBack();
            throw new Exception($th->getmessage());
        }

    }

    public function updateMember($request, $id){

        $member = Member::where('id', $id)->first();
        if(!$member){
            return null;
        }
        $memberUser = User::where('id', $member->user_id)->first();

        if ($request->hasAny(['first_name', 'last_name', 'gender', 'date_of_birth', 'age', 'emergency_contact_number', 'dietary_restriction'])) {
            $member->first_name = $request->input('first_name');
            $member->last_name = $request->input('last_name');
            $member->gender = $request->input('gender');
            $member->date_of_birth = $request->input('date_of_birth');
            $member->age = $request->input('age');
            $member->emergency_contact_number = $request->input('emergency_contact_number');
            $member->dietary_restriction = $request->input('dietary_restriction');
            $member->save();
        }
        if($request->hasAny('email', 'password', 'confirm_password')) {


        $memberUser->user_name = $request['user_name'];
        $memberUser->email = $request['email'];
        $memberUser->password = $request['password'];
        $memberUser->confirm_password = $request['confirm_password'];

        $memberUser->save();
        }

        if($request->hasAny('image', 'address','phone_number')) {

        $memberProfile = Profile::where('user_id', $memberUser->id)->first();
        $memberProfile->user_name = $request['user_name'];
        $memberProfile->image = $request['image'];
        $memberProfile->address = $request['address'];
        $memberProfile->phone_number = $request['phone_number'];


        $memberProfile->save();

        }
    }

    public function deleteMember($id){
        $member = Member::where('id', $id)->first();

        if(!$member){
            return null;
        }

        $member->delete();
        $member->user()->delete();
        $member->user()->profile()->delete();

    }
}
