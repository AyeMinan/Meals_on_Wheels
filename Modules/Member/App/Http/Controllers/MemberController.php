<?php

namespace Modules\Member\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Modules\Member\App\Http\Requests\MemberRequest;
use Modules\Member\App\Services\MemberService;

class MemberController extends Controller
{
    protected $memberService;

    public function __construct(MemberService $memberService){
        $this->memberService = $memberService;
    }
    public function index()
    {
        [$member, $memberProfile] = $this->memberService->getAllMembers();
        return response()->json([
            "CSRF Token" => csrf_token(),
            "member" => $member,
            "Profile" => $memberProfile
        ],200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('member::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, MemberRequest $memberRequest)
    {
        $startTime = microtime(true);

        try{
            $validatedData = $memberRequest->validated();

            $this->memberService->storeMember($request, $validatedData);

            return response()->json([
                "message" => "Member has been successfully created"
            ],201 );
        }catch(Exception $e){
            Log::channel('sora_error_log')->error("Member Create Error" . $e->getMessage());
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }finally{
            $executionTime = microtime(true) - $startTime;
            Log::info("Execution Time" . $executionTime. "seconds");
        }



    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('member::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $startTime = microtime(true);
        try{

            $this->memberService->updateMember($request, $id);

            return response()->json([
                "message" => "Data updated"
            ],200);
        }catch(Exception $e){
            Log::channel('sora_error_log')->error("Member Update Error" . $e->getMessage());
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }finally{
            $executionTime = microtime(true) - $startTime;
            Log::info("Execution Time" . $executionTime. "seconds");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $startTime = microtime(true);

        try{
        $member = $this->memberService->deleteMember($id);

        if($member == null){
            return response()->json([
                'message' => "Member not fount",
            ],404);
        }
        return response()->json([
            'message' => "Member has been successfully deleted"
        ],200);
        }catch(Exception $e){
            Log::channel('sora_error_log')->error("Member Delete Error" . $e->getMessage());
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }finally{
            $executionTime = microtime(true) - $startTime;
            Log::info("Execution Time" . $executionTime. "seconds");
        }

    }
}
