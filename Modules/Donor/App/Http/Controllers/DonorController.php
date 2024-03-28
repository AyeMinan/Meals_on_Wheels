<?php

namespace Modules\Donor\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Donor;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Donor\App\Http\Requests\DonorRequest;
use Modules\Donor\App\Services\DonorService;

class DonorController extends Controller
{
   protected $donorService;

   public function __construct(DonorService $donorService){
    $this->donorService = $donorService;
   }
    public function index()
    {
        $donors=$this->donorService->index();

        return response()->json([
            'donors'=>$donors,
            'csrf'=>csrf_token()
        ]) ;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('donor::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, DonorRequest $donorRequest)

    {
        $validatedData = $donorRequest->validated();

        $this->donorService->storeDonor($request, $validatedData);

        return response()->json("Donor has been created succesfully");

    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('donor::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('donor::edit');
    }

    /**
     * Update the specified resource in storage.
     */  public function update(Request $request, $id)
{
      $result= $this-> donorService->updateDonor($request, $id);
        return response()->json(['message' => "Updated Successful"], 200);

}
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->donorService->deleteDonor($id);
        return response()->json(['message' => "Deleted Successful"], 200);

    }
}
