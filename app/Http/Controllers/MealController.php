<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use App\Models\Partner;
use App\Models\Profile;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class MealController extends Controller
{
    public function index()
    {
        $meals = Meal::all();

        return response()->json(['data' => $meals], 200);
    }

    public function showMealByTownship(){
        $user = auth()->user();
        $partners = User::where('type', 'partner')->get();
        $mealsByTownship = [];

        if($user->type === 'member' || $user->type === 'caregiver'){
        foreach($partners as $partner){
            $partnerProfiles = Profile::where('user_id', $partner->id)->get();
            $partnerTownship = [];
            foreach($partnerProfiles as $partnerProfile){
            if ($partnerProfile && $partnerProfile->township) {
                $partnerTownship[] = $partnerProfile->township;


            }
            $userTownship = $user->profile->township;
                $meals = Meal::where('partner_id', $partner->id)->get();

                foreach($meals as $meal){
                    if (in_array($userTownship, $partnerTownship)) {
                        $mealsByTownship[] = [
                            'meal' => $meal
                        ];
                    }
                }
            }
        }

        if (empty($mealsByTownship)) {
            return response()->json(["message" => "No Valid Meal"],500);
        }
    }else{
        return response()->json([
            "message" => "Invalid User Type"
        ],500);
    }
        return response()->json(["meals by township" => $mealsByTownship],200);
    }

    public function store(Request $request)
    {

        $user = auth()->user();
        $validatedData = $request->validate([
            'name' => 'required',
            'ingredients' => 'required',
            'allergy_information' => 'nullable',
            'nutritional_information' => 'nullable',
            'dietary_restrictions' => 'nullable',
            'price' => 'required|numeric',
            'is_frozen' => 'required|string',
            'delivery_status' => 'required|string',
            'is_preparing' => 'required|string',
            'is_finished' => 'required|string',
            'is_pickup' => 'required|string',
            'is_delivered' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'temperature' => 'required',

        ]);

        if($user && $user->type === 'partner'){
        $validatedData['partner_id'] = $user->id;
    }

        // Handle image upload
        $path = 'uploads/meals';
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $ext = $file->getClientOriginalExtension();
            $filename = time() . '.' . $ext;
            $file->move($path, $filename);
            $validatedData['image'] = $filename;
        }



        $meal = Meal::create($validatedData);

        return response()->json(['meal' => $meal], 201);
    }

    public function update(Request $request, $id)
    {
        try{

        $meal = Meal::find($id);
        $user = auth()->user();
        if (!$meal) {
            return response()->json(['error' => 'Meal not found'], 404);
        }

        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'ingredients' => 'required',
            'allergy_information' => 'nullable',
            'nutritional_information' => 'nullable',
            'dietary_restrictions' => 'nullable',
            'price' => 'required|numeric',
            'is_frozen' => 'required|string',
            'delivery_status' => 'required|string',
            'is_preparing' => 'required|string',
            'is_finished' => 'required|string',
            'is_pickup' => 'required|string',
            'is_delivered' => 'required|string',
            'image' => 'required',
            'temperature' => 'required'
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
        if($user && $user->type === 'partner'){
            $partner_id = $user->id;
        }

        $meal->update([
            'name' => $request->name,
            'ingredients' => $request->ingredients,
            'allergy_information' => $request->allergy_information,
            'nutritional_information' => $request->nutritional_information,
            'dietary_restrictions' => $request->dietary_restrictions,
            'price' => $request->price,
            'is_frozen' => $request->is_frozen,
            'delivery_status' => $request->delivery_status,
            'temperature' => $request->temperature,
            'is_preparing' => $request->is_preparing,
            'is_finished' => $request->is_finished,
            'is_pickup' => $request->is_pickup,
            'is_delivered' => $request->is_delivered,
            'image' => $request->image,
            'partner_id' => $partner_id

        ]);

        return response()->json([
            "message" => "Updated Successful",
            'meal' => $meal
        ], 200);
        }catch(Exception $e){
            return response()->json(['error message' => $e->getMessage()],500);
        }
    }

    public function destroy($id)
    {
        $meal = Meal::find($id);

        if (!$meal) {
            return response()->json(['error' => 'Meal not found'], 404);
        }

        $meal->delete();

        return response()->json(['message' => 'Meal deleted successfully'], 200);
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
            $path = 'uploads/meals';
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
