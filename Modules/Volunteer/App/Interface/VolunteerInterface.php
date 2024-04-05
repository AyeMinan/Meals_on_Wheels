<?php
namespace Modules\Volunteer\App\Interface;

interface VolunteerInterface
{
    function all();
    function getById($id);
    function create($attributes);
    function update($request, $id);
    function delete($id);


}
