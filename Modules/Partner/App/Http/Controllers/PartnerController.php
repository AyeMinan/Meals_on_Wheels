<?php

namespace Modules\Partner\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repository\PartnerClass;
use Modules\Partner\App\Http\Requests\PartnerRequest;

class PartnerController extends Controller
{
    protected $partnerRepository;
    public function __construct(\Modules\Partner\App\Repository\PartnerClass $partnerRepository){
        $this->partnerRepository=$partnerRepository;
    }
    public function index()
    {
        $partners=$this->partnerRepository->allPartner();
        return response()->json([
            'success'=>true,
            'partner'=>$partners,
        ]);
    }



    public function store(PartnerRequest $request)
    {
        $validatedData=$request->validated();
        $partner=$this->partnerRepository->createPartner($validatedData);
        return response()->json([
            'success'=>true,
            'partner'=>$partner,
        ]);
    }


    public function show($id)
    {
        $partner=$this->partnerRepository->getPartnerById($id);
        return response()->json([
            'success'=>true,
            'partner'=>$partner,
        ]);
    }

    public function update(PartnerRequest $request, $id)
    {
        $validatedData=$request->validated();
        $partner=$this->partnerRepository->updatePartner($id,$validatedData);
        return response()->json([
            'success'=>true,
            'partner'=>$partner,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $partner=$this->partnerRepository->deletePartner($id);
        return response()->json(['success'=>true]);
    }
}
