<?php

namespace Modules\Caregiver\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Modules\Caregiver\App\Http\Requests\CaregiverRequest;
use  Modules\Caregiver\App\Services\CaregiverService;
class CaregiverController extends Controller
{

    protected $caregiverService;

    public function __construct(CaregiverService $caregiverService){

        $this->caregiverService = $caregiverService;
    }
    public function index()
    {
        return csrf_token();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('caregiver::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, CaregiverRequest $caregiverRequest)
    {
        $validatedData = $caregiverRequest->validated();

        $this->caregiverService->storeCaregiver($request, $validatedData);

        return response()->json("Caregiver has been successfully created");
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('caregiver::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('caregiver::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $startTime = microtime(true);

        try{
        $this->caregiverService->updateCaregiver($request, $id);

        return response()->json([
            "message" => "Date updated"
        ],200);
    } catch (Exception $e) {
        Log::channel('sora_error_log')->error("Caregiver Update Error" . $e->getMessage());
        return response()->json([
            'message' => $e->getMessage(),
        ], 500);
    }finally {
        // Calculate execution time
        $executionTime = microtime(true) - $startTime;
        Log::info("Execution time: " . $executionTime . " seconds");
    }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $startTime = microtime(true);

        try {
            $caregiver = $this->caregiverService->deleteCaregiver($id);

            if ($caregiver == null) {
                return response()->json([
                    'message' => 'Data not found',
                ], 404);
            }

            return response()->json([
                'message' => 'Caregiver Delete Successfully.',
                'data' => $caregiver,
            ], 200);
        } catch (Exception $e) {
            Log::channel('sora_error_log')->error("Caregiver Delete Error" . $e->getMessage());
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }finally {
            // Calculate execution time
            $executionTime = microtime(true) - $startTime;
            Log::info("Execution time: " . $executionTime . " seconds");
        }
    }

}
