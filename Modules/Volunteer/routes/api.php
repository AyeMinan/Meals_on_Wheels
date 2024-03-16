<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Volunteer\App\Http\Controllers\VolunteerController;

/*
    |--------------------------------------------------------------------------
    | API Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register API routes for your application. These
    | routes are loaded by the RouteServiceProvider within a group which
    | is assigned the "api" middleware group. Enjoy building your API!
    |
*/


//volunteer
Route::controller(VolunteerController::class)->group(function(){
    Route::get('/volunteers','index');
    Route::post('/volunteer','store');
    Route::get('/volunteer/{volunteer}','show');
    Route::put('/volunteer/{volunteer}','update');
    Route::delete('/volunteer/{volunteer}','destroy');
})->middleware(['auth:sanctum','admin']);
