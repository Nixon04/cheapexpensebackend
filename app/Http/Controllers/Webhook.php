<?php

namespace App\Http\Controllers;


use App\Models\VirtualAccounts;
use App\Models\VirtualCardList;
use App\Models\VirtualCardAmounts;
use App\Models\UserSignup;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\UserDetailsForCard;
use App\Models\Transactions;
use App\Models\UserAccountDetails;

use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

use Carbon\Carbon;


class Webhook extends Controller
{



    public function WebhookHandle(Request $request){
        $paymentDetails = $request->getContent();

        // Retrieve headers
        $headers = $request->headers->all();
        $headersJson = json_encode($headers);

        // Save the payload and headers to files
        file_put_contents(public_path("file3.html"), "<pre>" . htmlspecialchars($paymentDetails) . "</pre>");
        file_put_contents(public_path("file4.html"), "<pre>" . htmlspecialchars($headersJson) . "</pre>");

        // Get Paystack signature header
        $paystackSignature = $request->header('x-paystack-signature');

        define('SECOND_SECRET_KEY', env('SECOND_SECRET_KEY'));
        if ($paystackSignature !== hash_hmac('sha512', $paymentDetails, SECOND_SECRET_KEY)) {
            return response()->json(['status' => 'error', 'message' => 'Invalid signature'], 400);
        }

        $result= json_decode($paymentDetails);

        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error('JSON Decode Error', ['error' => json_last_error_msg(), 'content' => $paymentDetails]);
            return response()->json(['error' => 'Invalid JSON'], 400);
        }
    

        if(isset($result->event)){
        Log::info('paymentreference', ['checker' => $result]);
        if($result->event == "dedicatedaccount.assign.success"){
            Log::info('reference', ['status' => $result->data->customer]);
        }
    }

      
        if($result->event == "transfer.success"){
            
       try{
            
          $amount = $result->data->amount;
          $reference_id = $result->data->reference;
          $status = $result->data->status;
          
          Log::info('all_status', ['status' => $status, 'amount' => $amount, 'reference' => $reference_id]);
         $validatetransfer = Transactions::where('reference', $reference_id)->first();
         if($validatetransfer){
             $validatetransfer->update(['data_type' => 'Transfer', 'status' => $status]);
             Log::info('info', ['statusreport' => 'Already found']);
             return;
         }else{
            
               Log::info('info', ['statusreport' => ' status log']);
               return;
         }
         
       }catch(\Exception $e){
         Log::info('reference', ['delug' => $e->getMessage()]);
     }
        }
         
    
        else if($result->event == "transfer.failed"){
             $amount = $result->data->amount / 100;
          $reference_id = $result->data->reference;
          $status = $result->data->status;
         
          $validatetransfer = Transactions::where('reference', $reference_id)->first();
         if($validatetransfer){
             $validatetransfer->update(['data_type' => 'Transfer', 'status' => $status]);
             Log::info('info', ['statusreport' => 'Already found']);
             return;
         }else{
            
               Log::info('info', ['statusreport' => ' status log']);
               return;
         }
         
     }else if($result->event == "transfer.reversed"){
          $amount = $result->data->amount / 100;
          $reference_id = $result->data->reference;
          $status = $result->data->status;
         $validatetransfer =Transactions::where('reference', $reference_id);
         $validatetransfer = Transactions::where('reference', $reference_id)->first();
         if($validatetransfer){
             $validatetransfer->update(['data_type' => 'Transfer', 'status' => $status]);
             
            //  refunding user money back to his/her main wallet
             
              $trackrefuser = Transactions::where('reference', $reference_id)->first();
              
             if($trackrefuser){
                 $username  = $trackrefuser->username;
                 
                 $updateuseramount = UserAccountDetails::where('username', $username)->first();
                 if($updateuseramount){
                     $newamount = $updateuseramount->user_amount + $amount;
                     $updateuseramount->update(['user_amount' => $newamount ]);
                 }
                 
         }
         
             Log::info('info', ['statusreport' => 'Already found']);
             return;
         }else{
            
               Log::info('info', ['statusreport' => ' status log']);
               return;
         }
     }
     
     
    //  charge for virtual payment slip
    
    
    else if($result->event == "charge.success"){


    $amount = $result->data->amount / 100;
    $reference = $result->data->reference;
    $status = $result->data->status;
    $username = $result->data->customer->first_name;
    $customer_ref_id = $result->data->customer->customer_code;
    
    $notify_transaction_ref = Transactions::where('reference', $reference)->first();

    Log::info('reference', ['referenceLog' => $notify_transaction_ref->reference ?? "Nothing was found", 'initialref' => $reference, $notify_transaction_ref]);
    
    try{
    
    if(!$notify_transaction_ref){
        $querytokenuser = UserSignup::where('username', $username)->first();
        if($querytokenuser){
            $querytoken = $querytokenuser->fcmtoken;
        }

    
    Log::info('reference', ['referenceLog' =>  $querytoken,]);
    

    $date = Carbon::now()->setTimeZone('Africa/Lagos')->format('d M Y h:i A');    
        
    $useramountupdate = UserAccountDetails::where('username', $username)->first();
    if($useramountupdate){
        $newamount = $useramountupdate->user_amount + $amount;
        $useramountupdate->update(['user_amount' => $newamount]);
    }
    
      $transaction_data = new Transactions();
         $transaction_data->username = $username;
         $transaction_data->amount = $amount;
         $transaction_data->type_of_purchase = 'VirtualWallet';
         $transaction_data->sub_type_purchase = "AutoFund";
         $transaction_data->data_type = "Virtual";
         $transaction_data->status = $status;
         $transaction_data->ref_num_purchase = "";
         $transaction_data->reference = $reference;
         $transaction_data->date_of_purchase = $date;
         $transaction_data->save();

         $token = $querytoken;
         $messaging = app('firebase.messaging');
         $message = CloudMessage::withTarget('token', $token)
         ->withNotification(notification: Notification::create("🇳🇬 Cash Deposit 🤑'", body: "Your account has been credited with the sum of ". number_format($amount, 2)));
        $status =  $messaging->send($message);
        if($status){
            Log::info('information', ['server' => 'Deposited successfully']);
        }
        Log::info('information', ['server' => 'Unreachable']);
         
         if($transaction_data){
             Log::info('Arraytrue', ['updated_successfully', 'true']);
         }else{
                Log::info('Arraytrue', ['updated_unsuccessfully', 'false']);
         }
    }else{
             Log::info('CheckLog', ['Already found on our database', 'false statemet']);
    }
    }catch(\Exception $e){
        Log::info('Errorlog', ['Log data' => $e]);
    }
        
        // Log::info('state', ['amount' => $amount, 'reference' => $reference, 'status' => $status, 'username' => $username, 'customer_ref' => $customer_ref_id ]);
    }
    
     else if($result->event == "dedicatedaccount.assign.success"){
         $first= $result->data->customer->first_name;
         $account_name = $result->data->dedicated_account->account_name;
         $account_number = $result->data->dedicated_account->account_number;

         $checkvirtualname = VirtualAccounts::where('username', $first)->first();
         if($checkvirtualname){
           $checkvirtualname->update(['account_name' => $account_name, 'account_number' => $account_number ]);
         }else{
            return response()->json(['message' => 'Names do not align']);
         }
      
         Log::info('information', ['name' => $first]);
        return response()->json(['message' => 'Dedicated account received']);
     }
    }
        
 


    //
    public function BackHook(Request $request){
       $body = $request->getContent();
        $headers = $request->headers->all();
        $stats  = json_decode($body);

        if($stats->event_type == "bills.created"){
            $reference_state = $stats->trans_id;
            $status = $stats->transaction_status;
            $querycheck = Transactions::where('reference',  $reference_state)->first();
            if($querycheck){
                $querycheck->update([
                    'status' => $status
                ]);
                return response()->json([
                    'message' => 'saved successfully',
                ]);
            }
            return response()->json([
                'message' => 'Failed, couldn\'t process through',
            ]);
        }

       if($stats->event_type == "issuing.created.successful"){
        try{
         $tranx_id = $stats->trans_id;
         $bodycontents = $stats->card;

         $fullcheck = $stats->customer->name;
         $nameparts = explode(' ', $fullcheck);
         $username = implode(' ', array_slice($nameparts,0,2));

         $querycheck = VirtualCardList::where('transaction_id', $tranx_id)->first();
         if($querycheck){
            $querycheck->update(attributes: [
                'customer_id' => $stats->customer->id,
                'customer_name' => $username,
                'card_id' => $bodycontents->id,
                'card_type' => $bodycontents->card_type,
                'currency' => $bodycontents->currency,
                'brand' => $bodycontents->brand,
                'name' => $bodycontents->name,
                'first_six' => $bodycontents->first_six,
                'last_six' => $bodycontents->last_four,
                'masked' => $bodycontents->masked,
                'frontnumber' => $bodycontents->number,
                'expiry' => $bodycontents->expiry,
                'cvv' => $bodycontents->ccv,
                'street' => $bodycontents->billing->street,
                'city' => $bodycontents->billing->city,
                'state' => $bodycontents->billing->state,
                'country' => $bodycontents->billing->country,
                'postal_code' => $bodycontents->billing->postal_code,
            ]);
            $fcm_token_user = $querycheck->username;
            $querytokenuser = UserSignup::where('username', $fcm_token_user)->first();
            if($querytokenuser){
                $token  = $querytokenuser->fcmtoken;
                $title = "Congrats 😊";
                $body ="Your virtual Card has been created successfully";
                $messaging = app('firebase.messaging');
              CloudMessage::withTarget('token', $token)
                ->withNotification(notification: Notification::create($title, $body));
            }else{
                Log::info('No information of such for users');
            }
         }
         else{
            Log::info('No virtual card enlisted');
         }
       }
       catch(\Exception $e){
        Log::info('issued cards'. $e->getMessage() .' '. $e->getLine());
       }
    }
       //card failure;
       else if($stats->event_type == "card.created.failed"){
        $bodycontents = $stats->card;
        $tranx_id = $stats->trans_id;
        $querycheck = VirtualCardList::where('transaction_id', $tranx_id)->first();
        if($querycheck){
            $fcm_token_user = $querycheck->username;
            $querytokenuser = UserSignup::where('username', $fcm_token_user)->first();
            if($querytokenuser){
                $token  = $querytokenuser->fcmtoken;
                $title = "Oops";
                $body ="You don\'t have sufficient balance in your account for this purchase";
                $messaging = app('firebase.messaging');
              CloudMessage::withTarget('token', $token)
                ->withNotification(notification: Notification::create($title, $body));
            }
        }
       }

       else if($stats->event_type == "customers.created"){
        $address = json_decode($stats->address, true);
        $identity = json_decode($stats->identity, true);
        $customer_id  = $stats->customer_id;
     
        $fullcheck = $stats->name;
        $nameparts = explode(' ', $fullcheck);
        $username = implode(' ', array_slice($nameparts,0,2));
        $city = $address['city'];
        $street = $address['street'];
        $postal = $address['postal_code'];
        $revoke = $identity['image'];
        Log::info('Customer Created', ['status' => $stats]);
        $current_date = Carbon::now()->setTimezone('Africa/Lagos')->format('d M Y _ h:i:s_A');
        
        $queryuser = UserDetailsForCard::where('email', $stats->email)->first();
        if($queryuser){
            $queryuser->update([
                'customer_id' => $customer_id,
                'username' => $username,
                'email' =>  $stats->email,
                'address_city' => $city,
                'address_state' => $street,
                'postal_code' => $postal,
                'image' => $revoke,
                'time_created' => $current_date,
            ]);
        }else{
        $queryinsert = new UserDetailsForCard([
            'customer_id' => $customer_id,
            'username' => $username,
            'email' => $stats->email,
            'address_city' => $city,
            'address_state' => $street,
            'postal_code' => $postal,
            'image' => $revoke,
            'time_created' => $current_date,
         ]);
         $queryinsert->save();
       }
       }
   }
}
