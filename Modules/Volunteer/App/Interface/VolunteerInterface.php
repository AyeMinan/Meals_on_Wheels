<?php
namespace Modules\Volunteer\App\Interface;

interface VolunteerInterface
{
    function all();
    function getById($id);
    function create($attributes);
    function update($id,$attributes);
    function delete($id);


}
