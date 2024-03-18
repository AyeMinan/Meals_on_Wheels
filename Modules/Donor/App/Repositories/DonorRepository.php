<?php
namespace Modules\Donor\App\Repositories;
use App\Models\Donor;
use App\Models\Profile;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\Donor\App\Interfaces\DonorRepositoryInterface;
use App\Models\User;
class DonorRepository implements DonorRepositoryInterface{
    public function storeDonor($validatedData){
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
            // Update associated User (if necessary)
            $user = $donor->user;
            if ($user) {
                $user->update([
                ' email'=> $request->email,
                 'user_name'=> $request->user_name,
                 'password'=> $request->password,
                 'confirm_password'=> $request->confirm_password,
                 'type'=> $request->type,
                ]);
            }
                // Update associated Profile (if necessary)
               // dd($donorProfile);
                if ($donorProfile) {
                    $donorProfile->update([ 
                        'image'=> $request->image,
                        'user_name'=> $request->user_name,
                        'address'=> $request->address,
                        'phone_number'=> $request->phone_number,
                    ]);
                }
                return response()->json(['message' => 'Donor updated successfully'], 200);
            } catch(\Exception $e) {
                return response()->json(['message' => 'Failed to update donor'], 500);
        }
    }

}

