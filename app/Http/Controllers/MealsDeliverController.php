<?php

namespace App\Http\Controllers;

use App\Models\MealDeliver;
use App\Models\Partner;
use App\Models\Volunteer;
use App\Notifications\MealDeliveryScheduledPartnerNotification;
use App\Notifications\MealDeliveryScheduledVolunteerNotification;
use Illuminate\Http\Request;

class MealsDeliverController extends Controller
{
    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'partner_id' => 'required|exists:partners,id',
            'rider_id' => 'required|exists:riders,id',
            'delivery_date' => 'required|date',

        ]);


        $mealDelivery = new MealDeliver();
        $mealDelivery->partner_id = $request->partner_id;
        $mealDelivery->volunteer_id = $request->volunteer_id;
        $mealDelivery->delivery_date = $request->delivery_date;

        $mealDelivery->save();

        return response()->json(['message' => 'Meal delivery scheduled successfully'], 201);
    }

     // Method to retrieve scheduled meal deliveries for a partner
     public function partnerMealDeliveries($partnerId)
     {
         $partner = Partner::findOrFail($partnerId);
         $mealDeliveries = MealDeliver::where('partner_id', $partnerId)->get();

         return response()->json(['meal_deliveries' => $mealDeliveries]);
     }

     // Method to retrieve scheduled meal deliveries for a volunteer
     public function volunteerMealDeliveries($volunteerId)
     {
         $volunteer = Volunteer::findOrFail($volunteerId);
         $mealDeliveries = MealDeliver::where('volunteer_id', $volunteerId)->get();

         return response()->json(['meal_deliveries' => $mealDeliveries]);
     }
}
