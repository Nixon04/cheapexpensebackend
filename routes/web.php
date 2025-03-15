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


Route::get('/test', function(){
    $title = "TGIF";
    $body = "I have a dream that one day all things will be alright";
    $token = 'csavHiPnToK3971OUno1K_:APA91bFZ7_DHpb4lFfJf48gaJ4LOYJbAs7y80uYAgEX7b68OurF0F3R9Uu2y8TgACOwFkMVXdP231epiGZGr1dXB3iA0bRW1hwakevQxlQERqqvS8i6uiYM';
    $body ="We are here to gain momentum";
    $messaging = app('firebase.messaging');

    $message = CloudMessage::withTarget('token', $token)
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
});




