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
    {
        $donor = Donor::where('id', $id)->first();
        $donorUser= User::where('id', $donor->user_id)->first();
        $donorProfile= Profile::where('user_id', $donor->user_id)->first();

        try{
            $donor->delete();
            $donorUser->delete();
            $donorProfile->delete();

        } catch (\Exception $e) {
            // Handle exceptions if needed
            return false; // Deletion failed
        }
    }

    public function updateDonor($request, $id)
    {
        $donor = Donor::where('id', $id)->first();
        $donorUser= User::where('id', $donor->user_id)->first();
        $donorProfile= Profile::where('user_id', $donor->user_id)->first();


        if (!$donor) {
            return ['success' => false, 'message' => 'Donor not found'];
        }

        try {
            $donor->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'gender' => $request->gender,
                'date_of_birth'=> $request->date_of_birth,
            ]);

            // dd($donorUser);
            if ($donorUser) {
                $donorUser->email =  $request->email;
                $donorUser->user_name =  $request->user_name;
                $donorUser->password =  $request->password;
                $donorUser->confirm_password =  $request->confirm_password;
                $donorUser->type =  $request->type;

            }
            $donorUser->save();

                if ($donorProfile) {
                        $donorProfile->image = $request->image;
                        $donorProfile->user_name= $request->user_name;
                        $donorProfile->address = $request->address;
                        $donorProfile->phone_number = $request->phone_number;

                    $path = 'uploads/profile';
                    if ($request->hasFile('image')) {
                        // Delete old image
                        if ($donorProfile->image && File::exists($path . '/' . $donorProfile->image)) {
                            File::delete($path . '/' . $donorProfile->image);
                        }

                        $file = $request->file('image');
                        $ext = $file->getClientOriginalExtension();
                        $filename = time() . '.' . $ext;
                        $file->move($path, $filename);
                        $donorProfile->image = $filename;
                    }
                }
                $donorProfile->save();
                return response()->json(['message' => 'Donor updated successfully'], 200);
            } catch(\Exception $e) {
                return response()->json(['message' => 'Failed to update donor'], 500);
        }
    }

}

