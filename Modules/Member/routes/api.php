<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Member\App\Http\Controllers\MemberController;



Route::middleware(['auth:sanctum'])->prefix('v1')->name('api.')->group(function () {
    Route::get('member', fn (Request $request) => $request->user())->name('member');

});


