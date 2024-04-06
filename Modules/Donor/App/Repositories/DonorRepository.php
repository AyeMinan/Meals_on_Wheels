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
            'township' =>$validatedData['township'],
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
        $donorUser = User::where('id', $donor->user_id)->first();
        try{
            $donor->delete();
            $donor->user()->delete();
            $donorUser->profile()->delete();

        } catch (Exception $e) {
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

            $donor->first_name = $request->input('first_name', $donor->first_name);
            $donor->last_name = $request->input('last_name', $donor->last_name);
            $donor->gender = $request->input('gender', $donor->gender);
            $donor->date_of_birth = $request->input('date_of_birth',  $donor->date_of_birth);

        $donor->save();

            $donorUser->user_name = $request->input('user_name', $donorUser->user_name );
            $donorUser->email = $request->input('email', $donorUser->email);
            $donorUser->password = $request->input('password', $donorUser->password);
            $donorUser->confirm_password = $request->input('confirm_password', $donorUser->confirm_password);

        $donorUser->save();

            $donorProfile = Profile::where('user_id', $donorUser->id)->first();

            $donorProfile->user_name = $request->input('user_name', $donorProfile->user_name );
            $donorProfile->image = $request->input('image', $donorProfile->image );
            $donorProfile->address =  $request->input('address', $donorProfile->address );
            $donorProfile->township =  $request->input('township', $donorProfile->township );
            $donorProfile->phone_number =  $request->input('phone_number', $donorProfile->phone_number );


        $donorProfile->save();


            } catch(Exception $e) {
                return response()->json(['message' => $e->getMessage()], 500);
        }
    }

}

