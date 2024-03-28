<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\CaregiverController;
use App\Http\Controllers\DonorController;
use App\Http\Controllers\MealController;
use App\Http\Controllers\MealsDeliverController;
use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\VolunteerController;
use Illuminate\Routing\Router;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post("register", [AuthController::class,"register"]);
Route::post("login", [AuthController::class,"login"]);
Route::get("login", [AuthController::class, "show"])->name('login');



Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('logout', [AuthController::class,'logout']);
    //profile
      // Show authenticated user's profile
      Route::get('/profile', [ProfileController::class, 'showAuthenticatedUserProfile'])->name('profile.show');
    //user update their profile
    Route::put('/profile', [ProfileController::class, 'update']);

      Route::get('/profiles', [ProfileController::class, 'index'])->name('profiles.index');
      Route::get('/profile/{id}', [ProfileController::class, 'show'])->name('profiles.show');
      Route::post('/profile', [ProfileController::class, 'store'])->name('profiles.store');
      Route::put('/profile/{id}', [ProfileController::class, 'update'])->name('profiles.update');
      Route::delete('/profile/{id}', [ProfileController::class, 'destroy'])->name('profiles.destroy');



});

<<<<<<< HEAD
Route::get('/checkout', [StripeController::class, 'checkout']);
Route::post('/purchase', [StripeController::class, 'purchase']);
Route::get('/success', [StripeController::class, 'success']);



//meal
Route::controller(MealController::class)->group(function(){
    Route::get('/meals','index');
    Route::get('/showPartnerMeals', 'showPartnerMeals');
=======
//meal
Route::controller(MealController::class)->group(function(){
    Route::get('/meals','index')->middleware('auth:sanctum');
<<<<<<< HEAD
>>>>>>> develop
    Route::post('/meal','store');
    Route::get('/meal/{meal}','show');
    Route::put('/meal/{meal}','update');
    Route::delete('/meal/{meal}','destroy');
=======
    Route::post('/meal','store')->middleware(['auth:sanctum','admin']);
    Route::get('/meal/{meal}','show')->middleware('auth:sanctum');
    Route::put('/meal/{meal}','update')->middleware(['auth:sanctum','admin']);
    Route::delete('/meal/{meal}','destroy')->middleware(['auth:sanctum','admin']);
>>>>>>> b35c4a680d2a0d0b02633842b8d296442cd3baf3
});


Route::middleware(['admin'])->group(function () {

    Route::get('/admin/dashboard', function () {
        return response()->json(['message' => 'Admin Dashboard']);
    });
});
//meal schedule when the customer make order

Route::post('/meal-schedule',[MealsDeliverController::class,'store'])->middleware('auth:sanctum');
// Route to retrieve scheduled meal deliveries for a partner
Route::get('/partner-meal-deliveries/{partnerId}', [MealsDeliverController::class, 'partnerMealDeliveries'])->middleware('auth:sanctum');

// Route to retrieve scheduled meal deliveries for a volunteer
Route::get('/volunteer-meal-deliveries/{volunteerId}', [MealsDeliverController::class, 'volunteerMealDeliveries'])->middleware('auth:sanctum');


