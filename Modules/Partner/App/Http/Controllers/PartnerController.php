<?php

namespace Modules\Partner\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Repository\PartnerClass;
use Modules\Partner\App\Http\Requests\PartnerRequest;
use Modules\Partner\App\Service\PartnerService;

class PartnerController extends Controller
{
    protected $partnerService;
    public function __construct(PartnerService $partnerService){
        $this->partnerService=$partnerService;
    }
    public function index()
    {
        [$partner, $partnerProfile] = $this->partnerService->allPartner();
        return response()->json([
            'token'=>csrf_token(),
            "partner" => $partner,
            "Profile" => $partnerProfile
        ],200);;
    }



    public function store(PartnerRequest $request)
    {
        $validatedData=$request->validated();
        $partner=$this->partnerService->storePartner($validatedData);
        return response()->json([
            'success'=>true,
            'partner'=>$partner,
        ]);
    }


    public function show($id)
    {
        $partner=$this->partnerService->showPartner($id);
        return response()->json([
            'token'=>csrf_token(),
            'success'=>true,
            'partner'=>$partner,

        ]);
    }

    public function update(PartnerRequest $request, $id)
    {
        $validatedData=$request->validated();
        $partner=$this->partnerService->updatePartner($id,$validatedData);
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
        $partner=$this->partnerService->deletePartner($id);
        return response()->json(['success'=>true, "message" => "Partner deleted Successful"]);
    }
}
