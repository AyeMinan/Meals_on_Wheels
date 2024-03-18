<?php
namespace Modules\Partner\App\Repository;

use App\Models\User;
use \App\Models\Partner;

class PartnerClass implements PartnerInterface{
    function allPartner(){
        return User::with('partner')->get();
    }
    function getPartnerById($id){
        $partner=Partner::findOrFail($id);
        return $partner;

    }
    function createPartner($attributes){
       return Partner::create($attributes);

    }
    function updatePartner($id, $attributes){
        $partner=Partner::findOrFail($id);
        $partner->update($attributes);
        return $partner;

    }
    function deletePartner($id){
        $partner=Partner::findOrFail($id);
        $partner->delete();
        return $partner;
    }
}
