<?php
namespace Modules\Volunteer\App\Repository;
use App\Models\Volunteer;
class VolunteerClass implements VolunteerInterface
{

    public function allVolunteer(){
        $volunteers=Volunteer::all();
        return $volunteers;

    }
    public function getVolunteerById($id){
        $volunteer=Volunteer::findOrFail($id);
        return $volunteer;

    }
    public function createVolunteer($attributes){
        return Volunteer::create($attributes);
    }
    public function updateVolunteer($id,$attributes){
        $volunteer=Volunteer::findOrFail($id);
        $volunteer->update($attributes);
        return $volunteer;

    }
    public function deleteVolunteer($id){
        $volunteer=Volunteer::findOrFail($id);
        $volunteer->delete();
        return $volunteer;
    }

}
