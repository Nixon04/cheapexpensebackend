<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\APIController;

use App\Http\Controllers\ViewHomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;


use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;


Route::get('/', [ViewHomeController::class, 'homeview'])->name('homeview');
Route::get('/privacy', [ViewHomeController::class, 'Privacy'])->name('Privacy');



Route::get( '/mealxpress_drinks/{filename}', action: function($filename){
    $path = storage_path('app/private/mealxpress_drinks/' .$filename);
    if(!file_exists($path)){
      abort(404, 'Image Not Found');
    }
    return response()->file($path);
  })->name('mealxpress_drinks');


  Route::get('/userimages/{filename}', action: function($filename){
  $path = storage_path('app/private/userimages/'. $filename);
  if(!file_exists($path)){
    abort(404, 'Image Not Found');
  }
  return response()->file($path);
  })->name('userimages');


Route::get('/test', function(){
   $title = "TGIF"; 
    $body = "I have a dream that one day all things will be alright";
    $token = "dMWOwjk-TsKnPbHedK_Fnq:APA91bG5mdH-P0eKcM5ZvEzSuSQ8kcT2oTOFCQg306rbBTCjhhDePnkWwUJHZzd9r6mKD3InjMPYsxFTGaxbqP-Kiu2rRC8FQY3W8HrSo2CdIzUeZplF9O8";
    $body ="We are here to gain momentum";
    $messaging = app('firebase.messaging');

    $message = CloudMessage::withTarget('all_users', $token)
    ->withNotification(notification: Notification::create($title, $body));
    try {
        $response = $messaging->send($message);
        return response()->json(['success' => true, 'response' => $response]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'error' => $e->getMessage()]);
    }
});


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
    Route::post('/updatedatapackages', 'UpdateDataPackages');
    Route::post('/sendnotification','SendNotification');
});




