<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Partner\App\Http\Controllers\PartnerController;

//partner
Route::controller(PartnerController::class)->group(function(){
    Route::get('/partners','index');
    Route::post('/partner','store');
    Route::get('/partner/{partner}','show');
    Route::put('/partner/{partner}','update');
    Route::delete('/partner/{partner}','destroy');
})->middleware(['auth:sanctum','admin']);

