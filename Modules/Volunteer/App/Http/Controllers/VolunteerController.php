<?php

namespace Modules\Volunteer\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Volunteer\App\Http\Requests\VolunteerRequest;
use Modules\Volunteer\App\Repository\VolunteerClass;
use Modules\Volunteer\App\Service\VolunteerService;

class VolunteerController extends Controller
{
    protected $volunteerService;
   public function __construct(VolunteerService $volunteerService){
        $this->volunteerService=$volunteerService;
   }
    public function index()
    {
        [$volunteer, $volunteerProfile] = $this->volunteerService->allVolunteer();
        return response()->json([
            "CSRF Token" => csrf_token(),
            "volunteer" => $volunteer,
            "Profile" => $volunteerProfile
        ],200);

    }


    public function store(VolunteerRequest $request)
    {

        $validatedData=$request->validated();
         $this->volunteerService->storeVolunteer($validatedData);

        return response()->json([
            'message'=> "Volunteer created successful"

        ],201);

    }


    public function show($id)
    {
        $volunteer=$this->volunteerService->getVolunteerById($id);
        return response()->json([
            'success'=>true,
            'volunteer'=>$volunteer,
        ],200);
    }


    public function update(Request $request, $id)
    {
        $this->volunteerService->updateVolunteer($request, $id);
        return response()->json([
            'message'=> "Volunteer Data Updated",
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->volunteerService->deleteVolunteer($id);
        return response()->json([
            'message'=> "Volunteer Data Deleted Successful",

        ],200);
    }
}
