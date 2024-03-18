<?php

namespace Modules\Volunteer\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Volunteer\App\Http\Requests\VolunteerRequest;
use Modules\Volunteer\App\Repository\VolunteerClass;

class VolunteerController extends Controller
{
    protected $volunteerRepository;
   public function __construct(VolunteerClass $volunteerRepository){
        $this->volunteerRepository=$volunteerRepository;
   }
    public function index()
    {
        $volunteers=$this->volunteerRepository->allVolunteer();
        return response()->json([
            'success'=>true,
            'volunteer'=>$volunteers,
        ]);

    }


    public function store(VolunteerRequest $request)
    {
        $validatedData=$request->validated();
        $volunteer=$this->volunteerRepository->createVolunteer($validatedData);
        return response()->json([
            'success'=>true,
            'volunteer'=>$volunteer,
        ]);

    }


    public function show($id)
    {
        $volunteer=$this->volunteerRepository->getVolunteerById($id);
        return response()->json([
            'success'=>true,
            'volunteer'=>$volunteer,
        ]);
    }


    public function update(VolunteerRequest $request, $id)
    {
        $validatedData=$request->validated();
        $volunteer=$this->volunteerRepository->updateVolunteer($validatedData,$id);
        return response()->json([
            'success'=>true,
            'volunteer'=>$volunteer,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->volunteerRepository->deleteVolunteer($id);
        return response()->json([
            'success'=>true,
            
        ]);
    }
}
