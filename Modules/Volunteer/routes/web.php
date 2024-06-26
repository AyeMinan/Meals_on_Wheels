<?php

use Illuminate\Support\Facades\Route;
use Modules\Volunteer\App\Http\Controllers\VolunteerController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group([], function () {
    Route::resource('volunteer', VolunteerController::class)->names('volunteer')->middleware(['admin','auth:sanctum']);
});
