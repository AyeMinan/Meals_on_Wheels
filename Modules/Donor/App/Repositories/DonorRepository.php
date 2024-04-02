<?php
namespace Modules\Donor\App\Repositories;
use App\Models\Donor;
use App\Models\Profile;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\Donor\App\Interfaces\DonorRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\File;

class DonorRepository implements DonorRepositoryInterface{


    public function allDonor()
    {
        $donor = Donor::with('user')->get();
            $donorUsers = User::where('type', 'donor')->get();
            foreach($donorUsers as $donorUser){
                $profile = Profile::where('user_id', $donorUser->id)->get();
                if ($profile) {
                    $donorProfile[] = $profile;
                }
            }
      return [$donor, $donorProfile];
    }

    public function storeDonor($request, $validatedData){

        try{
            DB::beginTransaction();
        $user = User::create([
            'user_name' =>$validatedData['user_name'],
            'email' =>$validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'confirm_password' => Hash::make($validatedData['confirm_password']),
            'type' => $validatedData['type'] ,
        ]);

        if($validatedData['password'] !== $validatedData['confirm_password']){
                return response()->json("Password doesn't match");
        }


        // Create a profile instance
        $profile = new Profile([
            'user_name' =>$validatedData['user_name'],
            'image' =>$validatedData['image'] ,
            'phone_number' => $validatedData['phone_number'],
            'address' =>$validatedData['address'],
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
        if ($validatedData['type'] === 'donor') {
            $donor = new Donor([
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'gender' =>$validatedData['gender'],
            'date_of_birth' =>$validatedData['date_of_birth'],
                // Add other donor attributes here
            ]);
           //dd($donor);
           $user->donor()->save($donor);
         }
         DB::commit();
        }catch (\Throwable $th){
            DB::rollBack();
            throw new Exception($th->getmessage());
        }
    }
    public function deleteDonor($id)
    {   $donor = Donor::find($id);

        try{
            $donor->delete();
            if($donor->user()){
            $donor->user()->delete();
            }
            if($donor->user()->profile()){
            $donor->user()->profile()->delete();
            }
        } catch (\Exception $e) {
            // Handle exceptions if needed
            return false; // Deletion failed
        }
    }

    public function updateDonor($request, $id)
    {
      try{
        $donor = donor::where('id', $id)->first();
        if(!$donor){
            return null;
        }


        $donorUser = User::where('id', $donor->user_id)->first();
        if ($request->hasAny(['first_name', 'last_name', 'gender', 'date_of_birth'])) {
            $donor->first_name = $request->input('first_name');
            $donor->last_name = $request->input('last_name');
            $donor->gender = $request->input('gender');
            $donor->date_of_birth = $request->input('date_of_birth');
            $donor->save();
        }
        if($request->hasAny('email', 'password', 'confirm_password')) {

        $donorUser->user_name = $request['user_name'];
        $donorUser->email = $request['email'];
        $donorUser->password = $request['password'];
        $donorUser->confirm_password = $request['confirm_password'];

        $donorUser->save();
        }

        if($request->hasAny('image', 'address','phone_number')) {

            $donorProfile = Profile::where('user_id', $donorUser->id)->first();
            $donorProfile->user_name = $request['user_name'];
        $donorProfile->image = $request['image'];
        $donorProfile->address = $request['address'];
        $donorProfile->phone_number = $request['phone_number'];


        $donorProfile->save();

        }
            } catch(Exception $e) {
                return response()->json(['message' => $e->getMessage()], 500);
        }
    }

}

