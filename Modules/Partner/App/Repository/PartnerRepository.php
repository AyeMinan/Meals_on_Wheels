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
        return Partner::all();
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
    $partnerUser = User::where('id', $partner->user_id)->first();
    $partnerProfile = Profile::where('user_id', $partnerUser->id)->first();

    if(!$partner && !$partnerUser && !$partnerProfile){
        return null;
    }

    $partner->first_name = $request['first_name'];
    $partner->last_name = $request['last_name'];
    $partner->gender = $request['shop_name'];
    $partner->date_of_birth = $request['shop_address'];

    $partner->save();

    $partnerProfile->user_name = $request['user_name'];
    $partnerProfile->image = $request['image'];
    $partnerProfile->address = $request['address'];
    $partnerProfile->phone_number = $request['phone_number'];

    $partnerProfile->save();

    $partnerUser->user_name = $request['user_name'];
    $partnerUser->email = $request['email'];
    $partnerUser->password = $request['password'];
    $partnerUser->confirm_password = $request['confirm_password'];

    $partnerUser->save();

    }

    public function delete($id)
    {
        $partner = Partner::find($id);

        if ($partner) {

            $partner->delete();
            return $partner;
        }
        return null;
    }
}
