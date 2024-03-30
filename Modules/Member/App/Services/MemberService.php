<?php

namespace Modules\Member\App\Services;

use Modules\Member\App\Interfaces\MemberRepositoryInterface;

class MemberService
{
    protected $memberRepository;

    public function __construct(MemberRepositoryInterface $memberRepositoryInterface){
        $this->memberRepository = $memberRepositoryInterface;
    }

    public function getAllMembers(){
        return $this->memberRepository->getallMembers();
    }
    public function storeMember($request, $validatedData){
        return $this->memberRepository->storeMember($request, $validatedData);
    }

    public function updateMember($request, $id){
        return $this->memberRepository->updateMember($request, $id);
    }

    public function deleteMember($id){
        return $this->memberRepository->deleteMember($id);
    }
}
