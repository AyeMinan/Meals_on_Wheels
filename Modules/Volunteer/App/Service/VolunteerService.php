<?php
namespace Modules\Volunteer\App\Service;
use Modules\Volunteer\App\Interface\VolunteerInterface;

class VolunteerService{
    protected $volunteerRepository;

    public function __construct(VolunteerInterface $volunteerRepository)
    {
        $this->volunteerRepository=$volunteerRepository;

    }

    public function allVolunteer()
    {
        return $this->volunteerRepository->all();

    }

    public function getVolunteerById($id)
    {
        return $this->volunteerRepository->getById($id);

    }

    public function storeVolunteer($attributes)
    {
        return $this->volunteerRepository->create($attributes);

    }

    public function updateVolunteer($request,$id)
    {
        return $this->volunteerRepository->update($request,$id);

    }

    public function deleteVolunteer($id)
    {
        return  $this->volunteerRepository->delete($id);

    }
}
