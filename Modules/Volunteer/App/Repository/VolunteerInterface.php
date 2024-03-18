<?php
namespace Modules\Volunteer\App\Repository;
interface VolunteerInterface
{
    function allVolunteer();
    function getVolunteerById($id);
    function createVolunteer($attributes);
    function updateVolunteer($id,$attributes);
    function deleteVolunteer($id);


}
