<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\CaregiverController;
use App\Http\Controllers\DonorController;
use App\Http\Controllers\MealController;
use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\VolunteerController;

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



// //partner
// Route::controller(PartnerController::class)->group(function(){
//     Route::get('/partners','index');
//     Route::post('/partner','store');
//     Route::get('/partner/{partner}','show');
//     Route::put('/partner/{partner}','update');
//     Route::delete('/partner/{partner}','destroy');
// });

// //volunteer
// Route::controller(VolunteerController::class)->group(function(){
//     Route::get('/volunteers','index');
//     Route::post('/volunteer','store');
//     Route::get('/volunteer/{volunteer}','show');
//     Route::put('/volunteer/{volunteer}','update');
//     Route::delete('/volunteer/{volunteer}','destroy')->middleware(['auth:sanctum','admin']);
// });

//meal
Route::controller(MealController::class)->group(function(){
    Route::get('/meals','index')->middleware('auth:sanctum');
    Route::post('/meal','store')->middleware('admin');
    Route::get('/meal/{meal}','show')->middleware('admin');
    Route::put('/meal/{meal}','update');
    Route::delete('/meal/{meal}','destroy');
});


Route::middleware(['admin'])->group(function () {

    Route::get('/admin/dashboard', function () {
        return response()->json(['message' => 'Admin Dashboard']);
    });
});
