<?php
namespace Modules\Partner\App\Service;
use Modules\Partner\App\Repository\PartnerRepository;
class PartnerService
{

    protected $parterRepository;

    public function __construct(PartnerRepository $partnerRepository){
         $this->parterRepository=$partnerRepository;
    }

    public function allPartner()
    {
        return $this->parterRepository->all();
    }
    public function storePartner($attributes){
        return $this->parterRepository->create($attributes);
    }

    public function updatePartner($id,$attributes){
        return $this->parterRepository->update($id,$attributes);
    }

    public function showPartner($id){
        return $this->parterRepository->getById($id);
    }

    public function deletePartner($id){
        return $this->parterRepository->delete($id);
    }
}
