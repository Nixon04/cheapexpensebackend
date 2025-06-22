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

use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

use Carbon\Carbon;


class Webhook extends Controller
{



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
