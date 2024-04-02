<?php
namespace Modules\Volunteer\App\Repository;

use App\Models\Profile;
use App\Models\User;
use App\Models\Volunteer;
use Modules\Volunteer\App\Interface\VolunteerInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class VolunteerRepository implements VolunteerInterface
{

    public function all(){
        $volunteer = Volunteer::with('user')->get();
        $volunteerUsers = User::where('type', 'volunteer')->get();
        foreach($volunteerUsers as $volunteerUser){
            $profile = Profile::where('user_id', $volunteerUser->id)->get();
            if ($profile) {
                $volunteerProfile[] = $profile;
            }
        }
  return [$volunteer, $volunteerProfile];

    }
    public function getById($id){
        $volunteer=Volunteer::findOrFail($id);
        return $volunteer;

    }
    public function create($validatedData){

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

                // // Associate the profile with the user
                $user->profile()->save($profile);

                if ($validatedData['type'] === 'volunteer') {
                        $voluteer = new  Volunteer([
                            'first_name' =>  $validatedData['first_name'],
                            'last_name' =>  $validatedData['last_name'],
                            'gender' =>   $validatedData['gender'],
                            'date_of_birth'=>  $validatedData['date_of_birth'],
                        ]);

                   $user->volunteer()->save($voluteer);

                 }

            DB::commit();
        }catch(\Exception $e){
            DB::rollBack();
            throw new \Exception ($e->getMessage());
        }
    }
    public function update($request, $id){
        $volunteer = Volunteer::where('id', $id)->first();
        if(!$volunteer){
            return null;
        }


        $volunteerUser = User::where('id', $volunteer->user_id)->first();
        if ($request->hasAny(['first_name', 'last_name', 'gender', 'date_of_birth'])) {
            $volunteer->first_name = $request->input('first_name');
            $volunteer->last_name = $request->input('last_name');
            $volunteer->gender = $request->input('gender');
            $volunteer->date_of_birth = $request->input('date_of_birth');
            $volunteer->save();
        }
        if($request->hasAny('email', 'password', 'confirm_password')) {

        $volunteerUser->user_name = $request['user_name'];
        $volunteerUser->email = $request['email'];
        $volunteerUser->password = $request['password'];
        $volunteerUser->confirm_password = $request['confirm_password'];

        $volunteerUser->save();
        }

        if($request->hasAny('image', 'address','phone_number')) {

            $volunteerProfile = Profile::where('user_id', $volunteerUser->id)->first();
            $volunteerProfile->user_name = $request['user_name'];
        $volunteerProfile->image = $request['image'];
        $volunteerProfile->address = $request['address'];
        $volunteerProfile->phone_number = $request['phone_number'];


        $volunteerProfile->save();

        }

    }
    public function delete($id){
        $volunteer=Volunteer::findOrFail($id);
        $volunteer->delete();
        $volunteer->user()->delete();

        if(!$volunteer->user()->profile()){
            return;
        }
        $volunteer->user()->profile()->delete();
    }

}
