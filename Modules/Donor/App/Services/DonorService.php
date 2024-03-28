<?php
namespace Modules\Donor\App\Services;
use App\Models\Donor;
use Modules\Donor\App\Interfaces\DonorRepositoryInterface;

class DonorService{

    protected $donorRepository;

    public function __construct(DonorRepositoryInterface $donorRepository){
        $this->donorRepository = $donorRepository;
    }

    public function index()
    {
        return $this->donorRepository->allDonor();
    }

    public function storeDonor($request, $validatedData){
        return $this->donorRepository->storeDonor($request, $validatedData);
    }

    public function deleteDonor($id)
    {
        return $this-> donorRepository-> deleteDonor($id);
    }

    public function updateDonor($request, $id)
    {
      return $this-> donorRepository-> updateDonor($request, $id);
    }

}
