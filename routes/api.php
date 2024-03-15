<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MemberCaregiverController;
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post("register", [AuthController::class,"register"]);
Route::post("login", [AuthController::class,"login"]);
Route::get("login", [AuthController::class, "show"])->name('api.login');



Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('logout', [AuthController::class,'logout']);
    //profile
      // Show authenticated user's profile
      Route::get('/profile', [ProfileController::class, 'showAuthenticatedUserProfile'])->name('profile.show');

      Route::get('/profiles', [ProfileController::class, 'index'])->name('profiles.index');
      Route::get('/profiles/{id}', [ProfileController::class, 'show'])->name('profiles.show');
      Route::post('/profiles', [ProfileController::class, 'store'])->name('profiles.store');
      Route::put('/profiles/{id}', [ProfileController::class, 'update'])->name('profiles.update');
      Route::delete('/profiles/{id}', [ProfileController::class, 'destroy'])->name('profiles.destroy');
});





//member register
// Route::post('/store-data', [MemberCaregiverController::class, 'storeData']);
