<?php
namespace Modules\Partner\App\Repository;

interface PartnerInterface{
    function allPartner();
    function getPartnerById($id);
    function createPartner($attributes);
    function updatePartner($id, $attributes);
    function deletePartner($id);

}
