<?php

namespace App\Http\Controllers;
use App\Models\PurchaseDataItems;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\cache;
use Illuminate\Support\Facades\Http;
use App\Models\Transactions;
use App\Models\UserSignup;
use App\Models\UserAccountDetails;
use App\Models\VirtualAccounts;
use Illuminate\Support\Facades\DB;
use App\Models\CablePackages;
use Illuminate\Support\Facades\Log;

class ServicePostController extends Controller
{


    public function Vtpasswebhook(Request $request){
        $paymentfield = $request->getContent();
        $headers = $request->headers->all();
        file_put_contents(public_path('file7.html'), "<pre>". htmlspecialchars($paymentfield) . "<pre>");

        $json = json_decode($paymentfield);
        $approve = $json->data->code;

          if($approve == "040"){
            $vtcatchreverse = $json->data->content;
            $vtreference = $json->data->requestId;
            $vtstatus = $vtcatchreverse->transactions->status;
            $updatedata = Transactions::where('reference', $vtreference)->first();
            $vtamount = $vtcatchreverse->transactions->amount;

            $username = $updatedata->username;
            $update = UserAccountDetails::where('username', $username)->first();

            if($updatedata){
                if($vtstatus == "reversed"){
                   $updatedata->update(['status' => 'reversed']);
                    $update->update(['user_amount' => $vtamount]);
                   Log::info('entry', 'success'); 
                   return response(['message' => 'Updated']);
                 }else{
                  return response(['message' => 'Not Updated']);
               }
           }else{
            Log::info('Wasn\'t Updated at all' );
           }
        }
      
        if($approve == "000"){
            $vtcatch = $json->data->content;
           $vtreference = $json->data->requestId;
           $vtstatus = $vtcatch->transactions->status;
           $vtamount = $vtcatch->transactions->amount;
           $updatedata = Transactions::where('reference', $vtreference)->first();

           if($updatedata){

             if($vtstatus == "delivered"){
                $updatedata->update(['status' => 'success', 'amount' => $vtamount]);
                Log::info('entry', ['status' => $vtreference]); 
                return response(['message' => 'Successful', 'amount' => $vtamount]);
              }else{
               return response(['message' => 'Not Updated']);
            }
        }
           Log::info('ref', ['reference' => $vtreference, 'status' => $vtstatus]);
        }else{
            Log::info('No data was true');
        }
        Log::info('Reference', ['reference' => $vtreference]);
     }



    public function UzoBest(Request $request){
        $paymentDetails = $request->getContent();
        $headers = $request->headers->all();
        $headers = json_encode($headers);

        file_put_contents(public_path('file5.html'), "<pre>". htmlspecialchars($paymentDetails) . "<pre>");
        file_put_contents(public_path('file6.html'), "<pre>". htmlspecialchars($headers) . "<pre>");

       $json = json_decode($paymentDetails);

    //    $uzoreference = $json->ident;
    //    $status = $json->transaction_status;
    //    Log::info('content', ['status' => $status,  'reference' => $uzoreference]);
    //    $updatedata = Transactions::where('reference', $uzoreference)->first();
    //    if($updatedata){
    //    $uzoamount = $updatedata->amount;
    //    $uzousername = $updatedata->username;
    //     if($status == "successful"){
    //         $updatedata->update(['status' => 'success']);
    //         Log::info('entry', 'success'); 
    //        return response(['message' => 'Updated']);
    //     }
    //     else
    //      if($status == "failed"){
    //     $update = UserAccountDetails::where('username', $uzousername)->first();
    //     $update->update(['user_amount', $uzoamount]);
    //     return response()->json(['message' => 'Wallet reversed', 'status' => 'success']);
    //     }
    //     else  if($status == "pending"){
    //       return response()->json(['message' => 'Please be patient we are working on it Asap']);
    //     } 
    //    }else{
    //        return response(['message' => 'Not Updated']);
    //    }
    }

  
    public function Webhook(Request $request){
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

    Log::info('reference', ['referenceLog' => $notify_transaction_ref->reference ?? "Nothing was found", 'initialref' => $reference]);
    
    try{
    
    if(!$notify_transaction_ref){
    
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
         
         if($transaction_data){
             Log::info('Arraytrue', ['updated_successfully', 'true']);
         }else{
                Log::info('Arraytrue', ['updated_unsuccessfully', 'false']);
         }
    }else{
             Log::info('CheckLog', ['updated_successfully', 'false statemet']);
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
        
 

    public function AssignDedicatedVirtual(Request $request){
         $url = "https://api.paystack.co/dedicated_account/assign";
         $headers = [
            'Authorization' => "Bearer ".env('TEST_PAYSTACK_KEY'),
            'accept' => 'application/json',
        ];
        $response = Http::withHeaders($headers)->post($url, [
            "email" => "nixonsampson04@gmail.com",
            "first_name" => "Nengi",
            "middle_name" => " ",
            "last_name" => "Doe",
            "phone" => "+2348100000000",
            "preferred_bank" => "test-bank",
            "country" => "NG"
        ]);
        if($response->successful()){

            if($response->successful()){
                $checkvirtualaccountuser = VirtualAccounts::where('username', 'Nengi')->first();
                if($checkvirtualaccountuser){
                 return response()->json(['Name for this particular user is already registered']);
                }
            }

        $jsondecode = json_decode($response->body());  

        $date = Carbon::now()->setTimeZone('Africa/Lagos')->format('m d, Y : h:i:s');

        $insertvalue = new VirtualAccounts([
            "username" => "Nengi",
            "account_name" => "0",
            "account_number" => "0",
            "current_bank" => "WEMA",
            "date" => $date,
        ]);
        $insertvalue->save();
        return response()->json(['message' => $jsondecode->message]);
        }
    }

  

   private function CreateMainRecipient($type, $name, $account, $bankcode, $currency){
   
   }

    public function CreateTransferRecipient(Request $request){
        // verify users output
        $request->validate(
            [
              "account_number" => "required",
              "account_code" => 'required',
            ]
          );
          try {
          $accountnumber = $request['account_number'];
          $accountcode = $request['account_code'];
          $cachekey = "{$accountnumber}_{$accountcode}";

          if(Cache::has($cachekey)){
            return response()->json(Cache::get($cachekey));
          }

          $url = "https://api.paystack.co/bank/resolve?account_number=$accountnumber&bank_code=$accountcode";
          $headers = [
            'Authorization' => "Bearer ". env('TEST_PAYSTACK_KEY'),
            'accept' => 'application/json',
        ];
        $requestcall = Http::withHeaders($headers)->get($url);
        if($requestcall && $requestcall->successful()){
            $jsonbody = $requestcall->getbody();
            $status = $requestcall->getStatusCode();
            $data = json_decode($jsonbody);
            $account_name = $data->data->account_name;
            $account_num = $data->data->account_number;
            // create recipient
            $type = "nuban";
            $name = $account_name;
            $account = $account_num;
            $bankcode = $accountcode;
            $currency = "NGN";
            // create a recipient 
            $url = "https://api.paystack.co/transferrecipient";
            $headers = [
                'Authorization' => "Bearer ". env('TEST_PAYSTACK_KEY'),
                'accept' => 'application/json',
            ];
            $response = Http::withHeaders($headers)->post($url, [
                'type' => $type,
                'name' => $name,
                'account_number' => $account,
                'bank_code' => $bankcode,
                'currency' => $currency,
            ]);
        
            if($response->successful()){
            $responsejson = $response->getbody();
            $messagejson = json_decode($responsejson);
            $recipient = $messagejson->data->recipient_code;
            $bank = $messagejson->data->details->bank_name;
             $message =  [
                'message' => [
                'recipient' => $recipient,
                'account_name' => $name,
                ]
             ];
             Cache::put($cachekey, $message, now()->addMinutes(30));
            return  response()->json($message);
            }

        }else{
            $body = $requestcall->json();
            $message =  [
                'message' => [
                    'recipient' => $body,
                'account_name' => 'Couldn\'t verify account',
                ]
             ];
            return response()->json($message);
        }
    }catch(\Exception $e){

        return response()->json(['message' =>  $e->getMessage()]);
    }
}
          
        




    public function LocalTransferAuth(Request $request){

        $request->validate([
            'amount' => 'required|numeric',
            'reasons',
            'pin' => 'required|numeric',
            'reference' => 'required',
            'recipient' => 'required',
            'currentdate' => 'required'
        ]);
        

        $checktransact = Transactions::where('reference', $request['reference'])->first();
        if($checktransact){
            return response()->json(['message' => 'can\'t use duplicated references', 'status' => 'error']);
        }

        $password_pin_check = UserSignup::where('users_id', $request['pin'])->first();
        if(!$password_pin_check){
           return response()->json(['message' => 'Incorrect PIN', 'status' => 'error']);
        }
        
        $check_amount = UserAccountDetails::where('username', $request['username'])->first();
        if($check_amount->user_amount < $request['amount']  || 0 == $request['amount'] || $request['amount'] > $check_amount->user_amount ){
         return response()->json(['message' => 'Insufficient Balance', 'status' => 'error']);
        } else{
           $new_amount = $check_amount->user_amount - $request['amount'];
           $check_amount->update(['user_amount' => $new_amount]);
        }

        $url = "https://api.paystack.co/transfer";
        $headers = [
            'Authorization' => "Bearer ".env('TEST_PAYSTACK_KEY'),
            'accept' => 'application/json',
        ];
        $requeststatus = Http::withHeaders($headers)->post($url, [
            "source" => "balance", 
            "reason" => $request->input('reason'), 
            "amount" => $request->input('amount'), 
            "reference" => $request->input('reference'),
            "recipient" => $request->input('recipient'),
            "reason" => $request->input('reasons'),
        ]);
        try{

       if($requeststatus->successful()){
         $transaction_data = new Transactions();
         $transaction_data->username = $request->input('username');
         $transaction_data->amount = $request['amount'];
         $transaction_data->type_of_purchase = 'VirtualWallet';
         $transaction_data->sub_type_purchase = "Transfer";
         $transaction_data->data_type = $request->input('reasons') ?? '';
         $transaction_data->status = 'success';
         $transaction_data->ref_num_purchase = "";
         $transaction_data->reference = $request->input('reference');
         $transaction_data->date_of_purchase = $request->input('currentdate');
         $transaction_data->save();

         if($transaction_data){
            return response()->json(['message' => 'Successful', 'status' => 'success']);
         }else{
            return response()->json(['message' => 'Not successful', 'status' => 'failed']);
         }
     }else{
        $requeststatus->json();
                return response()->json(['message' => $requeststatus]);
            }
        }catch(\Exception $e){
            return response()->json(['message' => $e->getMessage()]);
        }
}

// 
            public function ViewPackage(Request $request){

                $url = "https://sandbox.vtpass.com/api/service-variations?serviceID=startimes";
                $headers = [
                    'api-key' => env('VTPASS_API_KEY'),
                    'secret-key' => env('VTPASS_SECRET_KEY'),
                    'accept' => 'application/json',
                ];
                $request  = Http::withHeaders($headers)->get($url);
                echo $request;
            }
            // fetch current banks in Nigeria
            public function FetchAllBanks(Request $request){
                $url = "https://api.paystack.co/bank";
                $headers = [
                    'secret-key' => env('TEST_PAYSTACK_KEY'),
                    'public_key' => env('PAYSTACK_PUBLIC_KEY'),
                    'accept' => 'application/json',
                ];
                $request  = Http::withHeaders($headers)->get($url);
                echo $request;
            }

        public function VerifyCableMeter(Request $request)
        {
            
            $request->validate(['meternumber' => "required", "metertype" => "required"]);
            
            $url = "https://vtpass.com/api/merchant-verify";
            
            $headers = [
                "api-key" => env('VTPASS_API_KEY'),
                "secret-key" => env('VTPASS_SECRET_KEY'),
            ];
            $response = Http::withHeaders($headers)->post($url, [
                "billersCode" => $request->input("meternumber"),
                "serviceID" => $request->input('metertype'),
            ]);
            
        
            if ($response->successful()) {
                $data = $response->json();
                // $json_r = json_encode($data);
                // echo $json_r;
                if(isset($data['content'])){
                    $cardverify = $data['content'];
                    if(isset($cardverify['Customer_Name'])){
                        if($cardverify['Customer_Name']){
                          return response()->json(['message'=> "valid", "status" => "success", 'UtilityIDName' => $cardverify['Customer_Name']]);
                        }else{
                            return response()->json(['message'=> "Invalid", "status" => "failed"]);
                        }
                    }else{
                        return response()->json(['message'=> "Invalid", "status" => "failed"]);
                    }
                }else{
                    return response()->json(['message'=> "Invalid", "status" => "failed"]);
                }
            }else{
                return response()->json(['message' => $response->getStatusCode(), 'body' => $response->body()]);
            }
        }
        

public function fetchCableSubscription(Request $request){
    $request->validate(['packagename' => 'required']);
    $fetch = CablePackages::where('packagename', $request['packagename'])->get();
    if($fetch){
        return response()->json(['message' => $fetch]);
    }
   }
   
   public function AirtimePurchase(Request $request)
{
    $request->validate([
        "username" => 'required|string',
        "amount" => 'required|integer|min:10', // Minimum amount set to 20
        "type_of_purchase" => 'required',
        "sub_type_purchase" => 'required',   
        "ref_num_purchase" => 'required',
        "userpin" => 'required',
        "date_of_purchase" => 'required',
        "data_type" => 'required',
        "reference" => 'required|unique:transactions,reference',
    ]);  


        // Fetch user balance with row-level locking
        $check_amount = UserAccountDetails::where('username', $request->input('username'))->first();

        if (!$check_amount || $check_amount->user_amount < $request->input('amount')) {
            return response()->json(['message' => 'Insufficient Balance', 'status' => 'error']);
        }else{
                    $check_amount->update([
                    'user_amount' => $check_amount->user_amount - $request->input('amount'),
                ]);
        }

        // Generate unique reference ID
        $currentyear = Carbon::now()->timezone('Africa/Lagos')->format('Ymdhi');
        $rand_ref = mt_rand(11111111, 99999999);
        $generated_reference = $currentyear . $rand_ref;

        // Validate user PIN
        $password_pin_check = UserSignup::where('users_id', $request->input('userpin'))->first();
        if (!$password_pin_check) {
            return response()->json(['message' => 'Incorrect PIN', 'status' => 'error']);
        }

        $url = "https://vtpass.com/api/pay";
        $headers = [
            'api-key' => env('VTPASS_API_KEY'),
            'secret-key' => env('VTPASS_SECRET_KEY'),
            'accept' => 'application/json',
        ];

        // Make API request to VTPass
        try {
            $response = Http::withHeaders($headers)->post($url, [
                'request_id' => $generated_reference, // Use generated reference
                'serviceID' => $request->input('sub_type_purchase'),
                'amount' => $request->input('amount'),
                'phone' => $request->input('ref_num_purchase'),
            ]);

            if ($response->successful()) {
              
                $transaction_data = new Transactions();
                 $transaction_data->username = $request['username'];
                 $transaction_data->amount = $request['amount'];
                 $transaction_data->type_of_purchase = $request['type_of_purchase'];
                 $transaction_data->sub_type_purchase = $request['sub_type_purchase'];
                 $transaction_data->data_type = $request['data_type'];
                 $transaction_data->status = $request['status'];
                 
                 $transaction_data->ref_num_purchase = $request['ref_num_purchase'];
                 $transaction_data->reference = $request['reference'];
                 $transaction_data->date_of_purchase = $request->input('date_of_purchase');
                 $transaction_data->save();

                return response()->json(['message' => 'success', 'status' => 'success']);
            } else {
                return response()->json(['message' => 'Airtime Purchase Failed', 'status' => 'error']);
            }
        } catch (\Exception $e) {
            \Log::error('Error purchasing airtime: ' . $e->getMessage());
            return response()->json(['message' => 'An error occurred', 'status' => 'error']);
        }
    
}
    
    
 
    public function DataPurchase(Request $request){
        $request->validate([
            "username" => "required",
            "amount" => 'required|integer|min:1',
            "type_of_purchase" => "required",
            "data_type" => 'required',
            "status" => 'required',
            "userpin" => 'required',
            "ref_num_purchase" => 'required',
            "date_of_purchase" => 'required',
            "reference" => 'required|unique:transactions,reference',
            "network" => "required",
            "plan" => "required",
        ]);



     DB::beginTransaction();

       $password_pin_check = UserSignup::where('users_id', $request['userpin'])->first();
       if(!$password_pin_check){
          return response()->json(['message' => 'Incorrect PIN', 'status' => 'error']);
       }
       $check_amount = UserAccountDetails::where('username', $request['username'])->first();
       
          if ($request['amount'] <= 0 || $check_amount->user_amount < $request['amount']) {
        return response()->json(['message' => 'Invalid or Insufficient Balance', 'status' => 'error']);
        }
        
         else{
          $new_amount = $check_amount->user_amount - $request['amount'];
          $check_amount->update(['user_amount' => $new_amount]);
       }


        $url="https://uzobestgsm.com/api/data/";
        $headers = [
            'Authorization' => "Token ".env('UZOBEST_KEY'),
            "accept" => "application/json",
        ];


        $requestDataCall = Http::withHeaders($headers)->post($url,[
            "network" => $request->input('network'),
            "mobile_number" => $request->input('ref_num_purchase'),
            "plan" => $request->input('plan'),
            "Ported_number" =>  "true",
        ]);
        Log::info('reference', ['plan' => $request->input('plan'), 'network' => $request->input('network')]);
         DB::commit();
        try{

            $dusername = $request->input('username');
            $damount = $request->input('amount');
            $dtypeofpurchase = $request->input('type_of_purchase');
            $dsubtypepurchase = $request->input('sub_type_purchase');
            $ddatatype = $request->input('data_type');
            $dstatus = $request->input('status');
            $drefnumpurchase = $request->input('ref_num_purchase');
            $dreference = $request->input('reference');
            $ddateofpurchase = $request->input('date_of_purchase');

        if($requestDataCall->successful()){

            $requestdata = $requestDataCall->getbody();
            $messagedecode = json_decode($requestdata);
            $status =  $messagedecode->Status;
            $uzoreference = $messagedecode->ident;
            $transaction_data = new Transactions();
            $transaction_data->username = $dusername;
            $transaction_data->amount = $damount;
            $transaction_data->type_of_purchase = $dtypeofpurchase;
            $transaction_data->sub_type_purchase = $dsubtypepurchase;
            $transaction_data->data_type = $ddatatype;
            $transaction_data->status = $dstatus;
            $transaction_data->ref_num_purchase = $drefnumpurchase;
            $transaction_data->reference = $dreference;
            $transaction_data->date_of_purchase = $ddateofpurchase;
            $transaction_data->save();

            Log::info('datapurchase Line for successful page', ['reference' =>  $uzoreference]);

            if($transaction_data){
                return response()->json(['message' => $status, 'status' => 'success']);
            }
            
        }
        
        else{  
            if($requestDataCall->getStatusCode() == "400"){
                    $return_amount = UserAccountDetails::where('username', $request['username'])->first();
                  if($return_amount){
                      $initial_amount = $return_amount->user_amount;
                      $totalremainingsum = $initial_amount + $request->input('amount');
                      $return_amount->update(['user_amount' =>$totalremainingsum]);
                  }
                return response()->json(['message' => 'Current Package not available', 'status' => 'error']);
                
              
            }
            
         return response()->json(['message' => 'not successful', 'status' => 'false', 'code' => $requestDataCall->body()]);
        }
        // DB::commit();
    }catch(\Exception $e){
        // DB::rollBack();
        return response()->json(['message' => 'Oops something went wrong, try again later','status' =>'error', 'log' => $e->getMessage()]);
    }
        
    }

    public function CablePurchase(Request $request){
        $request->validate([
            "username" => 'required',
            "requestId" => "required",
            "serviceId" => "required",
            "billersCode" => "required",
            "variationCode" => "required",
             "amount" => 'required|integer|min:1',
            "transactionpin" => "required",
            "subscriptionType" => "required",
            "currentdatepurchase" => "required",
        ]);        
        $getphoneuser = UserSignup::where('username', $request['username'])->first();
        $currentusercontact = $getphoneuser->contact;
        // get the pin and validate
        $validatepin =  UserSignup::where('users_id', $request['transactionpin'])->first();
        if(!$validatepin){
            return response()->json(['message' => "Invalid Pin", "status" => "error"]);
        }
        $check_amount = UserAccountDetails::where('username', $request['username'])->first();
       
         if ($check_amount->user_amount <=0 ||  $request->input('amount') > $check_amount->user_amount ) {
        return response()->json(['message' => 'Insufficient Balance', 'status' => 'error']);
        }
        else{
          $new_amount = $check_amount->user_amount - $request['amount'];
          $check_amount->update(['user_amount' => $new_amount]);
       }
       
        
        $url = "https://vtpass.com/api/pay";
        $headers = [
            'api-key' => env('VTPASS_API_KEY'),
            'secret-key' => env('VTPASS_SECRET_KEY'),
            'accept' => 'application/json',
        ];

           if($request['serviceId'] == "dstv"){
            $response = Http::withHeaders($headers)->post($url,[
              "request_id" => $request->input('requestId'),
              "serviceID" => $request->input('serviceId'),
              "billersCode" => $request->input('billersCode'),
              "variation_code" => $request->input('variationCode'),
              "amount" => $request->input('amount'),
              "phone" => $currentusercontact,
              "subscriptionType" => "change",
            ]);
        }else if($request['serviceId'] == "startimes"){
             $response = Http::withHeaders($headers)->post($url,[
                "request_id" => $request->input('requestId'),
                "serviceID" => $request->input('serviceId'),
                "billersCode" => $request->input('billersCode'),
                "variation_code" => $request->input('variationCode'),
                "amount" => $request->input('amount'),
                "phone" => $currentusercontact,
              ]);
        }
        else if($request['serviceId'] == "gotv"){
            $response = Http::withHeaders($headers)->post($url,[
               "request_id" => $request->input('requestId'),
               "serviceID" => $request->input('serviceId'),
               "billersCode" => $request->input('billersCode'),
               "variation_code" => $request->input('variationCode'),
               "amount" => $request->input('amount'),
               "phone" => $currentusercontact,
             ]);
       }

       try{
            if($response->successful()){
                $responsestack = $response->json();
                $jsoncall = $response->getbody();
                $json = json_decode($jsoncall);
                // $currentstatus = $json->content->status;
                if(isset($responsestack['code'])){
                    if($responsestack['code'] == "014"){
                        return response()->json(['message'=>"Reference already made", "status"=> "error"]);
                    } 
                  if($responsestack['code'] == "000"){
                    // requery transaction to understand the cause of the status
                    $url = "https://vtpass.com/api/requery";
                    $headers =[
                        "api-key" => env('VTPASS_API_KEY'),
                        "secret-key" => env('VTPASS_SECRET_KEY'),
                        "accept" => env('application/json'), 
                    ];
                    $requery = Http::withHeaders($headers)->post($url,[
                      "request_id" => $request->input('requestId'),
                    ]);
                    if($requery->successful() && $requery){
                       $requeryjson = $requery->getbody();
                       $json = json_decode($requeryjson);
                            //    check for the valuable content of status on the json call
                            $statuschecker = $json->content->transactions;
                            $main_status = $statuschecker->status;
                            if($main_status == "delivered"){
                                $main_status = "success";
                            }else if($main_status = "pending"){
                                $main_status = "pending";
                            }else{
                                $main_status = "reversed";
                            }
                            if(in_array($json->code, ['020','011','012','030'])){
                                // update current user wallet back 
                                $check_amount = UserSignup::where('username', $request['username'])->first();
                                $new_amount = $check_amount->user_amount + $request['amount'];
                                $check_amount->update(['user_amount' => $new_amount]);
                            // save new failed transaction
                                $transaction_data = new Transactions();
                                $transaction_data->username = $request->input('username');
                                $transaction_data->amount = $request->input('amount');
                                $transaction_data->type_of_purchase = "Cable";
                                $transaction_data->sub_type_purchase = $request->input('serviceId');
                                $transaction_data->data_type = $request->input('subscriptionType');
                                $transaction_data->status = "failed";
                                $transaction_data->ref_num_purchase = $currentusercontact;
                                $transaction_data->reference = $request->input('requestId');
                                $transaction_data->date_of_purchase = $request->input('currentdatepurchase');
                                $transaction_data->save();
                                return response()->json(['message' => "Transaction failed, wallet amount reversed", "status" => "error"]);
                            }
                        if($json->code == "000"){
                          
                            // 
                            $transaction_data = new Transactions();
                            $transaction_data->username = $request->input('username');
                            $transaction_data->amount = $request->input('amount');
                            $transaction_data->type_of_purchase = "Cable";
                            $transaction_data->sub_type_purchase = $request->input('serviceId');
                            $transaction_data->data_type = $request->input('subscriptionType');
                            $transaction_data->status = $main_status;
                            $transaction_data->ref_num_purchase = $currentusercontact;
                            $transaction_data->reference = $request->input('requestId');
                            $transaction_data->date_of_purchase = $request->input('currentdatepurchase');
                            $transaction_data->save();
                            return response()->json(['message' => "successful","status"=> "success"]);
                        }
                      
                        else{
                        return response()->json(['message'=> "Oops seems something happened", "info" => $json]);
                        }
                    }
                  }else{
                    return response()->json(['message' => "do not proceed", "status" => "error", "statuscode" => $responsestack['code']]);
                  }
                }else{
                    return response()->json(['message' => "Process value Invalid", "status" => "error", "statuscode" => $responsestack['code']]);
                }
            }
              else{
                return response()->json(['message'=> 'Oops seems something went wrong', "status" => "error"]);

            }
            
        }catch(\Exception $e){
            return response()->json(['message' => $e->getMessage()]);
        }
    }

    //  verify Utility payment means
    public function VerifyUtility(Request $request){ 
        $request->validate([
            "billerscode" => "required",
            "serviceid" => "required",
            "type" => "required",
        ]);
        $url = "https://vtpass.com/api/merchant-verify";
        $headers = [
            'api-key' => env('VTPASS_API_KEY'),
            'secret-key' => env('VTPASS_SECRET_KEY'),
            'accept' => 'application/json',
        ];
        try{
        $response = Http::withHeaders($headers)->post($url,[
         "billersCode" => $request->input('billerscode'),
         "serviceID" => $request->input('serviceid'),
         "type" => $request->input('type'),
        ]);
        if($response->successful()){
            $jsonencode = $response->json();
            if(isset($jsonencode['content'])){
                $verifycard = $jsonencode['content'];
                if(isset($verifycard['Customer_Name'])){
                    if($verifycard['Customer_Name']){
                return response()->json(['message' => "valid", "status" => "success",  'UtilityIDName' => $verifycard['Customer_Name']]);
                    }
                else{
                    return response()->json(['message' => "invalid custom", "status" => "error"]);
                }
            }
            else{
                return response()->json(['message' => "Invalid IUC Number", "status" => "error"]);
            }
        }
    }
    }catch(\Exception $e){
        return response()->json(['message' => $e->getMessage()]);
    }
    }

    // Utility Payment pathway -----------------------------------------------------------------------------------------------------





    public function AllPublicPurchase(Request $request){
        $request->validate([
            "username" => "required",
             "amount" => 'required|integer|min:1',
            "networkType"=> "required",
            "billType"=> "required",
            "iuc"=> "required",
            "type" => "required",
            "date"=> "required",
            "pin" => "required",
            "currentpurchase"=> "required",
            "reference"=> "required|unique:transactions,reference",
        ]);

        try{
        $getphoneuser = UserSignup::where('username', $request['username'])->first();
        $currentusercontact = $getphoneuser->contact;
        // get the pin and validate
        $validatepin =  UserSignup::where('users_id', $request['pin'])->first();
        if(!$validatepin){
            return response()->json(['message' => "Invalid Pin", "status" => "error"]);
        }
        $check_amount = UserAccountDetails::where('username', $request['username'])->first();
        
         if ($check_amount->user_amount <=0 ||  $request->input('amount') > $check_amount->user_amount ) {
        return response()->json(['message' => 'Insufficient Balance', 'status' => 'error']);
        }
        else{
          $new_amount = $check_amount->user_amount - $request['amount'];
          $check_amount->update(['user_amount' => $new_amount]);
       }
       

             $url = "https://vtpass.com/api/pay";
             $headers = [
                "api-key" => env('VTPASS_API_KEY'),
                "secret-key" => env('VTPASS_SECRET_KEY'),
                "access" => "application/json",
             ];

             $requestinfo = Http::withHeaders($headers)->post($url, [
                "request_id" => $request->input('reference'),
                "serviceID" => $request->input('billType'),
                "billersCode" => $request->input('iuc'),
                "variation_code" => $request->input('type'),
                "amount" => $request->input('amount'),
                "phone" => $currentusercontact,
             ]);
             if($requestinfo->successful() && $requestinfo){
                $json = $requestinfo->getbody();
                $jsonstatus = $requestinfo->getStatusCode();
                $data = json_decode($json);

                if(in_array($data->code, ['020','011','012','030','014'])){
                    // update current user wallet back 
                    $check_amount = UserAccountDetails::where('username', $request['username'])->first();
                    $new_amount = $check_amount->user_amount + $request['amount'];
                    $check_amount->update(['user_amount' => $new_amount]);
                        // save new failed transaction
                        $transaction_data = new Transactions();
                        $transaction_data->username = $request->input('username');
                        $transaction_data->amount = $request->input('amount');
                        $transaction_data->type_of_purchase = "Utility";
                        $transaction_data->sub_type_purchase = $request->input('networkType') ?? "";
                        $transaction_data->data_type = $token ?? "";
                        $transaction_data->status = "failed";
                        $transaction_data->ref_num_purchase = $currentusercontact;
                        $transaction_data->reference = $request->input('reference');
                        $transaction_data->date_of_purchase = $request->input('currentpurchase') ?? "";
                      $transaction_data->save();
                    return response()->json(['message' => "Transaction failed, wallet amount reversed", "status" => "error", 'code' => $data->code]);
                }
             
                if($jsonstatus == 200 && $data->code == "000"){
                //    requery transaction data 
                   $url = "https://vtpass.com/api/requery";
                   $headers = [
                    "api-key" => env('VTPASS_API_KEY'),
                    "secret-key" => env('VTPASS_SECRET_KEY'),
                    "accept" => "application/json",
                   ];
                   $requery = Http::withHeaders($headers)->post($url,[
                    "request_id" => $request->input('reference'),
                   ]);
                   if($requery && $requery->successful()){
                      $getstate = $requery->getStatusCode();
                      $json = $requery->getbody();
                      $data = json_decode($json);
                      if($getstate == 200 && $data->code == "000"){
                          $dataform = $data->content->transactions;
                          $status = $dataform->status;
                          $token = $data->purchased_code;
                        //   insert data transactions for use
                             if($status == "delivered"){
                                $status = "success";
                             }else if($status == "pending"){
                                $status = "pending";
                             }else{
                                $status = "failed";
                             }
                            $transaction_data = new Transactions();
                            $transaction_data->username = $request->input('username');
                            $transaction_data->amount = $request->input('amount');
                            $transaction_data->type_of_purchase = "Utility";
                            $transaction_data->sub_type_purchase = $request->input('networkType') ?? "";
                            $transaction_data->data_type = $token ?? "";
                            $transaction_data->status = $status;
                            $transaction_data->ref_num_purchase = $currentusercontact;
                            $transaction_data->reference = $request->input('reference');
                            $transaction_data->date_of_purchase = $request->input('currentpurchase') ?? "";
                            $transaction_data->save();
                          return response()->json(['message'=> "Successful", "status" => "success", 'token' => $token]);
                      }else{
                          return response()->json(['message' => "", "status" => $getstate]);
                      }
                   }else{
                    return response()->json(['message' => "Not successful"]);
                   }
                }else{
                  return response()->json(['message'=> $data]);
                }
            
                return response()->json(['message' => $requestinfo]);
             }else{
                return response()->json(['message' => "Not successful", "state" => $response->getStatusCode()]);
             }
            }catch(\Exception $e){
                return response()->json(['message' => "Server timeout, please try again", "status" => "error"]);
            }
         }


        
    
     public function AirtimeToCash(Request $request){
        $request->validate([
            "username" => 'required',
              "amount" => 'required|integer|min:1',
            "networktype" => 'required',
            "bill_type" => 'required',
            "package_number" => 'required',
            "status" => 'required',
            "reference" => 'required|unique:transactions,reference',
            "pin" => 'required',
            "date" => 'required',
        ]);

        $validateref = Transactions::where('reference', $request['reference'])->first();
        if($validateref){
          return response()->json(['message' => 'Reference already existing', 'status' => 'failed']);
        }

        $validatepin =  UserSignup::where('users_id', $request['pin'])->first();
        if(!$validatepin){
            return response()->json(['message' => "Invalid Pin", "status" => "error"]);
        }
      
    
     try{
        $transaction_data = new Transactions();
        $transaction_data->username = $request->input('username');
        $transaction_data->amount = $request->input('amount');
        $transaction_data->type_of_purchase = $request->input('bill_type');
        $transaction_data->sub_type_purchase = $request->input('networktype') ?? "";
        $transaction_data->data_type =   "VTU";
        $transaction_data->status = $request->input('status');
        $transaction_data->ref_num_purchase = $request->input('package_number');
        $transaction_data->reference = $request->input('reference');
        $transaction_data->date_of_purchase = $request->input('date') ?? "";
        $transaction_data->save();
        if($transaction_data){
            return response()->json(['message' => 'initiated', 'status' => 'success']);
        }
        else{
            return response()->json(['message' => 'Wasn\'t initiated', 'status' => 'error']);
        }
    }catch(\Exception $e){
        return  response()->json(['message' => 'Oops something went wrong']);
    }

}

public function ConfirmAirtimeToCash(Request $request){
    $request->validate([
        "username" => 'required',
        "reference" => 'required',
    ]);
    $transaction = Transactions::where('reference', $request['reference'])->first();

    if($transaction){
        $transaction->update(['status' => 'pending']);
        return response()->json(['message' => 'Successful', 'status' => 'success']);
    }else{
        return response()->json(['message' => 'Wasn\'t initiated', 'status' => 'error']);

    }
    
}



}
