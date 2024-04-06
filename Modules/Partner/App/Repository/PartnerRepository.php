<?php
namespace Modules\Partner\App\Repository;

use App\Models\Partner;
use App\Models\Profile;
use App\Models\User;
use Exception;
use Modules\Partner\App\Interface\PartnerInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PartnerRepository implements PartnerInterface
{

    public function all()
    {
        $partner = Partner::with('user')->get();
            $partnerUsers = User::where('type', 'partner')->get();
            foreach($partnerUsers as $partnerUser){
                $profile = Profile::where('user_id', $partnerUser->id)->get();
                if ($profile) {
                    $partnerProfile[] = $profile;
                }
            }
      return [$partner, $partnerProfile];
    }

    public function getById($id)
    {
        return Partner::find($id);
    }

    public function create( $validatedData)
    {
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
            'township' => $validatedData['address'],
            'phone_number' => $validatedData['phone_number'],

        ]);

        // // Associate the profile with the user
        $user->profile()->save($profile);

        if ($validatedData['type'] === 'partner') {
                $member = new  Partner([
                    'first_name' =>  $validatedData['first_name'],
                    'last_name' =>  $validatedData['last_name'],
                    'shop_name' =>   $validatedData['shop_name'],
                    'shop_address'=>  $validatedData['shop_address'],
                ]);

           $user->member()->save($member);

         }
        DB::commit();
    }catch (\Throwable $th){
        DB::rollBack();
        throw new Exception($th->getmessage());
    }

}

public function update($request, $id){

    $partner = Partner::where('id', $id)->first();
        if(!$partner){
            return null;
        }

        $partnerUser = User::where('id', $partner->user_id)->first();

            $partner->first_name = $request->input('first_name', $partner->first_name);
            $partner->last_name = $request->input('last_name', $partner->last_name);
            $partner->shop_name = $request->input('shop_name', $partner->shop_name);
            $partner->shop_address = $request->input('shop_address', $partner->shop_address);
            $partner->save();

            $partnerUser->user_name = $request->input('user_name', $partnerUser->user_name );
            $partnerUser->email = $request->input('email', $partnerUser->email);
            $partnerUser->password = $request->input('password', $partnerUser->password);
            $partnerUser->confirm_password = $request->input('confirm_password', $partnerUser->confirm_password);

            $partnerUser->save();

            $partnerProfile = Profile::where('user_id', $partnerUser->id)->first();
            $partnerProfile->user_name = $request->input('user_name', $partnerProfile->user_name );
            $partnerProfile->image = $request->input('image', $partnerProfile->image );
            $partnerProfile->address =  $request->input('address', $partnerProfile->address );
            $partnerProfile->township =  $request->input('township', $partnerProfile->township );
            $partnerProfile->phone_number =  $request->input('phone_number', $partnerProfile->phone_number );


        $partnerProfile->save();


    }

    public function delete($id)
    {
        $partner = Partner::find($id);
        $partnerUser = User::where('id', $partner->user_id)->first();

        if ($partner) {

            $partner->delete();
            $partner->user()->delete();
            $partnerUser->profile()->delete();

        }
        return null;
    }
}
