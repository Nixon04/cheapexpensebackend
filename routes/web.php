<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\APIController;

use App\Http\Controllers\ViewHomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;


use Inertia\Inertia;

Route::get('/', [ViewHomeController::class, 'homeview'])->name('homeview');

// defining the route for authcontroller bodies for get request
Route::controller(AuthController::class)->group(function () {
   Route::get('/cheapx/auth/login',action: 'LoginPage');
   Route::get('/cheapx/dashboard/home', 'HomeDashboard');
   Route::get('/cheapx/dashboard/regusers','RegUsers');
   Route::get('/cheapx/dashboard/transactions','Transactions');
   Route::get('/cheapx/dashboard/airtime','Airtime');
   Route::get('/cheapx/dashboard/data','Data');
   Route::get('/cheapx/dashboard/cable','Cable');
   Route::get('/cheapx/dashboard/utility','Utility');
   Route::get('/cheapx/dashboard/notification','SendNotification');
   Route::get('/cheapx/dashboard/logout','LogOut');
   Route::get('/cheapx/auth/fetchrecord','FetchRecord');

});
// defining routes for post request on admin panel
Route::controller(PostController::class)->group(function () {
    Route::post('/cheapx/auth/loginpost', 'LoginPost');
    Route::post('/cheapx/auth/updatedata', 'UpdateData');
    Route::post('/cheapx/auth/utility', 'UpdateUtility');
    Route::post('/cheapx/auth/cable', 'UpdateCable');
    Route::post('/cheapx/auth/sendnotification', 'sendNotification');
});




