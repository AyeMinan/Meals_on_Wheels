<?php

namespace App\Http\Controllers;

use App\Models\Caregiver;
use App\Models\Meal;
use App\Models\Member;
use App\Models\Order;
use App\Models\Partner;
use App\Models\Profile;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{

    public function index(){
        $user = auth()->user();

        $currentTime = Carbon::now();

        $aDayAgo = $currentTime->subHours(24);

        if($user->type === "partner"){
        $orders = Order::where('partner_id', $user->id)
        ->where('created_at', '>=', $aDayAgo)
        ->get();
        }else if($user->type === "member"){
            $orders = Order::where('member_id', $user->id)
        ->where('created_at', '>=', $aDayAgo)
        ->get();
        }else if($user->type === "caregiver"){
            $orders = Order::where('caregiver_id', $user->id)
        ->where('created_at', '>=', $aDayAgo)
        ->get();
        }else{
            return response()->json("Invalid User Type");
        }

        if($orders->isEmpty()){
            return response()->json([
                "message" => "No Valid Orders"
            ],404);
        }else{
        return response()->json([
            "Orders" => $orders
        ],200);
    }
    }
    public function store(Request $request){

        $user = auth()->user();

        $validatedData = $request->validate([
            'name' => 'required',
            'ingredients' => 'required',
            'allergy_information' => 'nullable',
            'nutritional_information' => 'nullable',
            'dietary_restrictions' => 'nullable',
            'price' => 'required|numeric',
            'is_frozen' => 'required',
            'delivery_status' => 'required',
            'is_preparing' => 'required',
            'is_finished' => 'required',
            'is_pickup' => 'required',
            'is_delivered' => 'required',
            'image' => 'required',
            'temperature' => 'required',
            'partner_id' => 'required',
            'member_id' => 'nullable',
            'caregiver_id' => 'nullable',
            'volunteer_id' => 'nullable',
        ]);

        // dd($validatedData);
        if($user && $user->type === 'member' ){
            $validatedData['member_id'] = $user->id;
    }elseif($user && $user->type === 'caregiver'){
        $validatedData['caregiver_id'] = $user->id;
    } else{
        return response()->json([
            'message' => 'Invalid User Type'
        ],500);
    }
    $path = 'uploads/orders';
    if ($request->hasFile('image')) {
        $file = $request->file('image');
        $ext = $file->getClientOriginalExtension();
        $filename = time() . '.' . $ext;
        $file->move($path, $filename);
        $validatedData['image'] = $filename;
    }



    $orderedMeal = Order::create($validatedData);

    return response()->json(['ordered meal' => $orderedMeal], 201);
}

public function show($id){
    $currentTime = Carbon::now();

    $aDayAgo = $currentTime->subHours(24);

    $order = Order::where('id',$id)->where('created_at', '>=' ,$aDayAgo)->first();

    if ($order == null) {
        return response()->json(['error' => 'order not found'], 404);
    }

    return response()->json(['order' => $order], 200);
}

public function update(Request $request, $id){
    $order = Order::find($id);
    $user = auth()->user();
    if(!$order){
        return response()->json([
            'message' => "order not found"
        ],404);
    }

    $order->update([
        $order->name = $request->input('name', $order->name),
        $order->ingredients  = $request->input('ingredients', $order->ingredients),
        $order->allergy_information = $request->input('allergy_information', $order->allergy_information),
        $order->nutritional_information = $request->input('nutritional_information',$order->allergy_information),
        $order->dietary_restrictions = $request->input('dietary_restrictions',$order->dietary_restrictions),
        $order->price = $request->input('price',$order->price),
        $order->is_frozen = $request->input('is_frozen',$order->is_frozen),
        $order->delivery_status = $request->input('delivery_status',$order->delivery_status),
        $order->temperature = $request->input('temperature',$order->temperature),
        $order->is_preparing = $request->input('is_preparing',$order->is_preparing),
        $order->is_finished = $request->input('is_finished',$order->is_finished),
        $order->is_pickup = $request->input('is_pickup',$order->is_pickup),
        $order->is_delivered = $request->input('is_delivered',$order->is_delivered),
        $order->image = $request->input('image',$order->image),

    ]);

    return response()->json(['Updated Ordered Meal' => $order], 201);
}
public function destory($id){
    $order = Order::find($id);
    if(!$order){
        return response()->json([
            "message" => "Order not found"
        ],404);

    }
    $order->delete();

    return response()->json(["messsage" => "Order Deleted Successful"]);
}

public function showOrdersForRider()
{
    $user = auth()->user();
    $currentTime = Carbon::now();
    $aDayAgo = $currentTime->subHours(24);
    $partners = User::where('type', 'partner')->get();
    $orderDetails = []; // Initialize the array outside of the loop

    if ($user->type === 'volunteer') {
        foreach ($partners as $partner) {
            $partnerProfiles = Profile::where('user_id', $partner->id)->get();
            $partnerTownship = [];

            foreach ($partnerProfiles as $partnerProfile) {
                if ($partnerProfile->township) {
                    $partnerTownship[] = $partnerProfile->township;
                }
            }

            $volunteerTownship = $user->profile->township;

            // Retrieve orders only if partner's township matches volunteer's township
            if (in_array($volunteerTownship, $partnerTownship)) {
                $orders = Order::where('created_at', '>=', $aDayAgo)
                    ->where('partner_id', $partner->id)
                    ->get();

                foreach ($orders as $order) {
                    $user = User::where('id', $order->partner_id)->first();
                    $partnerShopAddress = $user->partner->shop_address;
                    $memberAddress = null;
                    $caregiverAddress = null;

                    $member = User::where('id', $order->member_id)->first();
                    if ($member && $member->type === 'member') {
                        $memberAddress = $member->profile->address;
                    }

                    $caregiver = User::where('id', $order->caregiver_id)->first();
                    if ($caregiver && $caregiver->type === 'caregiver') {
                        $caregiverAddress = $caregiver->profile->address;
                    }

                    $orderDetails[] = [
                        'order' => $order,
                        'partner_shop_address' => $partnerShopAddress,
                        'member_address' => $memberAddress,
                        'caregiver_address' => $caregiverAddress,
                    ];
                }
            }
        }

        return response()->json([
            "order_details" => $orderDetails
        ], 200);
    } else {
        return response()->json([
            "message" => "Invalid User Type"
        ], 500);
    }
}




public function upload(Request $request){
    $validator = Validator::make($request->all(),[
        'image' => 'required|image|mimes:jpeg,png,jpg,gif',
    ]);
    $validatorMessage = collect($validator->errors())->flatMap(function ($e, $field){
        return [$field => $e[0]];
    });
    if($validator->fails()){
        return response()->json([
            'status' => '422',
            'error'  => $validatorMessage
        ],422);
    }
        // Handle image upload
        $path = 'uploads/orders';
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $ext = $file->getClientOriginalExtension();
            $filename = time() . '.' . $ext;
            $file->move($path, $filename);
            $imagePath = $path . '/' . $filename;
        }
        return response()->json([
                "message" => "Upload Successful",
                "imagePath" => $imagePath
            ],200);
}
}

