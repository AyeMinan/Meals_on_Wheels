<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    public function index(){
        $user = auth()->user();

        $currentTime = Carbon::now();

        $aDayAgo = $currentTime->subHours(24);

        $orders = Order::where('orderer_id', $user->id)->where('created_at', '>=', $aDayAgo)->get();

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
            'is_frozen' => 'required|boolean',
            'delivery_status' => 'required|boolean',
            'is_preparing' => 'required|boolean',
            'is_finished' => 'required|boolean',
            'is_pickup' => 'required|boolean',
            'is_delivered' => 'required|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'temperature' => 'required',

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
        $validatedData = $request->validate([
            'name' => 'required',
            'ingredients' => 'required',
            'allergy_information' => 'nullable',
            'nutritional_information' => 'nullable',
            'dietary_restrictions' => 'nullable',
            'price' => 'required|numeric',
            'is_frozen' => 'required|boolean',
            'delivery_status' => 'required|boolean',
            'is_preparing' => 'required|boolean',
            'is_finished' => 'required|boolean',
            'is_pickup' => 'required|boolean',
            'is_delivered' => 'required|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'temperature' => 'required',

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



    $order->update($validatedData);

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
}

