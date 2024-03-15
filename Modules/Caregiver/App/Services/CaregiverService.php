<?php

namespace Modules\Caregiver\App\Services;

use App\Models\Caregiver;
use Modules\Caregiver\App\Interfaces\CaregiverRepositoryInterface;

class CaregiverService
{
    protected $caregiverRepository;

    public function __construct(CaregiverRepositoryInterface $caregiverRepositoryInterface){
        $this->caregiverRepository = $caregiverRepositoryInterface;
    }

    public function storeCaregiver($validatedData){
        return $this->caregiverRepository->storeCaregiver($validatedData);
    }

    public function updateCaregiver($request, $id){
        return $this->caregiverRepository->updateCaregiver($request, $id);
    }
    public function deleteCaregiver($id){
        return $this->caregiverRepository->deleteCaregiver($id);
    }
}
