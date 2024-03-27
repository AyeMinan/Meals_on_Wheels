<?php

namespace Modules\Partner\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repository\PartnerClass;
use Modules\Partner\App\Http\Requests\PartnerRequest;
use Modules\Partner\App\Service\PartnerService;

class PartnerController extends Controller
{
    protected $partnerRepository;
    public function __construct(PartnerService $partnerRepository){
        $this->partnerRepository=$partnerRepository;
    }
    public function index()
    {
        $partners=$this->partnerRepository->allPartner();
        return response()->json([
            'success'=>true,
            'partner'=>$partners,
            'token'=>csrf_token(),
        ]);
    }



    public function store(PartnerRequest $request)
    {
        $validatedData=$request->validated();
        $partner=$this->partnerRepository->storePartner($validatedData);
        return response()->json([
            'success'=>true,
            'partner'=>$partner,
        ]);
    }


    public function show($id)
    {
        $partner=$this->partnerRepository->showPartner($id);
        return response()->json([
            'success'=>true,
            'partner'=>$partner,
            'token'=>csrf_token(),
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
