<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\APIControllerBackend;
use App\Http\Controllers\ServicePostController;
use App\Http\Controllers\Webhook;

// $image = $request->file('productImage');
// $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
// $path = $image->storeAs('mealxpress_images', $imageName, 'local');

// post request 
Route::prefix('')->group(callback: function(){
   Route::controller(Webhook::class)->group(function(){
      Route::post('verifyhook', 'BackHook');
      Route::post('webhook',  'WebhookHandle');
   });
   // APIControllerBackend BOth Post and Get Request 
   Route::controller(APIControllerBackend::class)->group(function(){
   Route::get('viewimage',  'FetchImage');
   Route::post('signup',  'signupController');
   Route::post('login',  'LoginController');
   Route::post('loginwithpin',  'LoginViaPin');
   Route::post('userbalance',  'UserBalance');
   Route::post('transactions',  'Transactions');
   Route::get('getresponseservices',  'GetResponseServices');
   Route::post('populate',  'Populate');
   Route::post('thirdsub',  'ThirdSub');
   Route::post('savebank',  'SaveUserBank');
   Route::post('fetchuserbank',  'FetchSaveUserBank');
   Route::post('deleteuserbank',  'DeleteUserBank');
   Route::post('saveuserimage',  'SaveUsersImage');
   Route::post('fetchusersimage',  'FetchSpecificImage');
   Route::post('fetchuserinfo',  'FetchUserInfo');
   Route::post('updateuserspassword',  'UpdateUserPassword');
   Route::post('verifyuserpin',  'VerifyUserPin');
   Route::post('confirmverificationpin',  'ConfirmVerifyUserPin');
   Route::post('resetpin',  'ResetTransactionPin');
   Route::post('flushnotification', 'FlushNotification');
   Route::post('forgotpassword', 'ForgotPasswordState');
   Route::post('confirmpassword', 'ConfirmPassword');
   Route::post('setnewpassword', 'SetNewPassword');
   Route::post('referralpackage', 'ReferralPackage');
   Route::post('referrallink', 'ReferralLink');
   Route::post('refbonusfund',  'RefBonusReturn');
   Route::post('deleteaccount',  'DeleteAccount');
   Route::post('fetchdedicatedaccount',  'FetchDedicatedAccount');
   Route::post('sendnotification', 'sendNotificationToAll');
   Route::post('deactivate',  action: 'DeleteAccount');
   Route::post('viewsuccessreceipt',  'ViewSuccessReceipt');
    // get service request 
    Route::post('airtimetocashpercentage',  'AirtimeToCashPercentage');
    Route::get('listbanks',  'ListBanks');
    Route::get('fetchaccountname',  'FetchAccountName');
    Route::get('fetchnotification',  'FetchNotification');
    Route::get('notificationcount',  'NotificationCount');
    Route::get('newsfeed',  'NewsFeed');
    Route::get('adshow',  'Adshow');
    Route::get('updatedversion',  'UpdatedVersion');
    Route::get('getallservice',  'GetAllServices');
   });

//    service Controller BOth Post and Get Request 
   Route::controller(ServicePostController::class)->group(function(){
    Route::post('purchaseairtime',  'AirtimePurchase');
    Route::post('purchasedata',  'DataPurchase');
    Route::post('newpurchasedata', 'NewDataPurchase');

    Route::post('fetchcablesubscriptions',  'FetchCableSubscription');
    Route::post('verifycable',  'VerifyCableMeter');
    Route::post('verifyutility',  'VerifyUtility');
    Route::post('cablepurchase',  'CablePurchase');
    Route::post('utilitypurchase',  "UtilityPurchase");
    Route::post('viewcablepackages',  'ViewPackage');
    Route::get('fetchbanks',  'FetchAllBanks');
    Route::post('transfer',  'LocalTransferAuth');
    Route::post('createrecipient',  'CreateTransferRecipient');
    Route::post('createvirtualaccount', 'AssignDedicatedVirtual');
    Route::post('airtimetocash', 'AirtimeToCash');
    Route::post('confirmairtimetocash', 'ConfirmAirtimeToCash');
    Route::post('datavend',  'NewDataVend');
    Route::post('eligible',   'EligibleForCardStat');
    Route::post('verifycardstatus', 'ValidatedCardStatus');
    Route::post('createvirtualmembers',  'CreateVirtualCardCustomer')
    ->middleware('throttle:virtual_accounts');
    Route::get('showtoken',  'ShowToken');
    Route::get('fetchnewdata',  'NewDataFetch');

    Route::post('uzowebhook',  'UzoBest');
    Route::post('vtpasswebhook',  'Vtpasswebhook');
    Route::post('payscribehook',   'PayscribeWebhook');
    Route::post('allutilitypurchase',  'AllPublicPurchase');
    Route::post('validatebvnandnin', 'ValidateBvnAndNin');
    Route::post('fetchuservirtualamounts', 'FetchVirtualBalance');
    Route::post('singlevirtualcreation', 'SingleVirtualCreation');
    Route::post('fetchvirtualtransactions', 'FetchVirtualTransactions');
    Route::post('verifybillingstatus', 'VerifyBillingStatus');
    Route::post('virtualcardlistcount','Virtualcardlistcount');
    Route::post(uri: 'listcards', action: 'EnlistCards');
    Route::get('currentconversionrate', 'CurrentConversationRate');
    Route::post('freezeaccount', 'FreezeCard');
    Route::post('unfreezeaccount',  'UnFreezeCard');
    Route::post('switchcards', 'SwitchMainCard');
    Route::post('datapacklists', 'ListDataPackages');
    Route::post('fundcard', 'FundCard');
    Route::post('withdrawcard', 'WithdrawCard');
    Route::post('terminatecard', 'TerminateCard');
    Route::post('fetchcardtransactions', 'CardTransactionsList');

    Route::post('createsavings', 'CreateSavings');

    
   });
});


