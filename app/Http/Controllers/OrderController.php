<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use App\Models\Order;
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
            $orders = Order::where('orderer_id', $user->id)
        ->where('created_at', '>=', $aDayAgo)
        ->get();
        }else if($user->type === "caregiver"){
            $orders = Order::where('orderer_id', $user->id)
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
        ]);

        // dd($validatedData);
        if($user && ($user->type === 'member' || $user->type === 'caregiver')){
            $validatedData['orderer_id'] = $user->id;
    }else{
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

