<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\APIControllerBackend;
use App\Http\Controllers\ServicePostController;





// post request 


Route::prefix('')->group(function(){
    Route::get('viewimage', [APIControllerBackend::class, 'FetchImage']);
    Route::post('signup', [APIControllerBackend::class, 'signupController']);
    Route::post('login', [APIControllerBackend::class, 'LoginController']);
    Route::post('loginwithpin', [APIControllerBackend::class, 'LoginViaPin']);
    Route::post('userbalance', [APIControllerBackend::class, 'UserBalance']);
    Route::post('transactions', [APIControllerBackend::class, 'Transactions']);
    Route::get('getresponseservices', [APIControllerBackend::class, 'GetResponseServices']);
    Route::post('populate', [APIControllerBackend::class, 'Populate']);
    Route::post('thirdsub', [APIControllerBackend::class, 'ThirdSub']);
    Route::post('purchaseairtime', [ServicePostController::class, 'AirtimePurchase']);
    Route::post('purchasedata', [ServicePostController::class, 'DataPurchase']);
    Route::post('viewsuccessreceipt', [APIControllerBackend::class, 'ViewSuccessReceipt']);
    Route::post('fetchcablesubscriptions', [ServicePostController::class, 'FetchCableSubscription']);
    Route::post('verifycable', [ServicePostController::class, 'VerifyCableMeter']);
    Route::post('verifyutility', [ServicePostController::class, 'VerifyUtility']);
    Route::post('cablepurchase', [ServicePostController::class, 'CablePurchase']);
    Route::post('utilitypurchase', [ServicePostController::class, "UtilityPurchase"]);
    Route::post('viewcablepackages', [ServicePostController::class, 'ViewPackage']);
    Route::get('fetchbanks', [ServicePostController::class, 'FetchAllBanks']);
    Route::post('savebank', [APIControllerBackend::class, 'SaveUserBank']);
    Route::post('fetchuserbank', [APIControllerBackend::class, 'FetchSaveUserBank']);
    Route::post('deleteuserbank', [APIControllerBackend::class, 'DeleteUserBank']);
    Route::post('saveuserimage', [APIControllerBackend::class, 'SaveUsersImage']);
    Route::post('fetchusersimage', [APIControllerBackend::class, 'FetchSpecificImage']);
    Route::post('fetchuserinfo', [APIControllerBackend::class, 'FetchUserInfo']);
    Route::post('updateuserspassword', [APIControllerBackend::class, 'UpdateUserPassword']);
    Route::post('verifyuserpin', [APIControllerBackend::class, 'VerifyUserPin']);
    Route::post('confirmverificationpin', [APIControllerBackend::class, 'ConfirmVerifyUserPin']);
    Route::post('resetpin', [APIControllerBackend::class, 'ResetTransactionPin']);
    Route::post('flushnotification',[APIControllerBackend::class, 'FlushNotification']);
    Route::post('transfer', [ServicePostController::class, 'LocalTransferAuth']);
    Route::post('createrecipient', [ServicePostController::class, 'CreateTransferRecipient']);
    Route::post('createvirtualaccount', [ServicePostController::class,'AssignDedicatedVirtual']);
    Route::post('airtimetocash', [ServicePostController::class,'AirtimeToCash']);
    Route::post('confirmairtimetocash', [ServicePostController::class,'ConfirmAirtimeToCash']);
    Route::post('forgotpassword', [APIControllerBackend::class,'ForgotPasswordState']);
    Route::post('confirmpassword', [APIControllerBackend::class,'ConfirmPassword']);
    Route::post('setnewpassword', [APIControllerBackend::class,'SetNewPassword']);
    Route::post('referralpackage', [APIControllerBackend::class,'ReferralPackage']);
    Route::post('referrallink', [APIControllerBackend::class,'ReferralLink']);
    Route::post('refbonusfund', [APIControllerBackend::class, 'RefBonusReturn']);
    Route::post('deleteaccount', [APIControllerBackend::class, 'DeleteAccount']);


    route::post('fetchdedicatedaccount', [APIControllerBackend::class, 'FetchDedicatedAccount']);
    route::post('sendnotification', [APIControllerBackend::class,'sendNotificationToAll']);
    Route::post('deactivate', [APIControllerBackend::class, 'DeleteAccount']);

    
    // get service request 
    Route::post('airtimetocashpercentage', [APIControllerBackend::class, 'AirtimeToCashPercentage']);
    Route::get('listbanks', [APIControllerBackend::class, 'ListBanks']);
    Route::get('fetchaccountname', [APIControllerBackend::class, 'FetchAccountName']);
    Route::get('fetchnotification', [APIControllerBackend::class, 'FetchNotification']);
    Route::get('notificationcount', [APIControllerBackend::class, 'NotificationCount']);
    Route::get('newsfeed', [APIControllerBackend::class, 'NewsFeed']);
    Route::get('adshow', [APIControllerBackend::class, 'Adshow']);
    Route::get('updatedversion', [APIControllerBackend::class, 'UpdatedVersion']);
    Route::get('getallservice', [APIControllerBackend::class, 'GetAllServices']);


    Route::post('datavend', action: [ServicePostController::class,'NewDataVend']);

    Route::get('showtoken', [APIControllerBackend::class, 'ShowToken']);

    
    Route::post('webhook', [ServicePostController::class, 'Webhook']);
    Route::post('uzowebhook', [ServicePostController::class, 'UzoBest']);
    Route::post('vtpasswebhook', [ServicePostController::class, 'Vtpasswebhook']);
    
    Route::post('payscribehook',  [ServicePostController::class, 'PayscribeWebhook']);

    



    // link for all utility packages

    Route::post('allutilitypurchase', [ServicePostController::class, 'AllPublicPurchase']);
});


// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
