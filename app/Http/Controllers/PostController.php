<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\Admin;
use App\Models\PurchaseDataItems;
use App\Models\CablePackages;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\DataPackList;

use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;


class PostController extends Controller
{



    public function SendNotification(Request $request){
        $request->validate([
            'title' => 'required',
            'body' => 'required',
        ]);
        $body = $request->input('body');
        // $token = $querydata->fcm_token;
        // Initialize the Firebase Messaging service
        $messaging = app('firebase.messaging');
        // Create the notification message
        $message = CloudMessage::withTarget('token', "d1mBwzByT1K8OZ4FWoCufL:APA91bHg5305IpC6GQdsHfFAucnwZweIGsp31rCjstinbRY5dABkp1bzYeHF-ph_NSELY0q9rpW8du9JR4BvMiSXJgUhxkXJ9UwxJBAImSrFzO4g-NY1Y5Q")
            ->withNotification(notification: Notification::create($request->title ?? 'Title body', $body ?? 'Message body'));
        // Send the message
        try {
            $response = $messaging->send($message);
            return response()->json(['message' => "success", 'status' => 'success', 'response' => $response]);
        } catch (\Exception $e) {
            return response()->json([ 'message' => 'Error',  'status' => "false", 'error' => $e->getMessage()], 500);
         }
    }
    
    private function detectDurationType($planName)
{
    $planName = strtolower($planName);

    $map = [
        'daily' => ['daily', '1 day', '24 hours','2Day'],
        'weekly' => ['weekly', '7 days', '1 week'],
        'monthly' => ['30days', 'a month', 'month', '30Days', '30', '(7Days)'],
        '2Month' => ['60Days', '60'],
        '3Month' => ['90Days', '90'],
        'yearly' => ['365 days', 'a year', 'year'],
        'cheapy' => ['3 days', '10 days', 'flexi'] // fallback or other edge cases
    ];

    foreach ($map as $type => $keywords) {
        foreach ($keywords as $keyword) {
            if (str_contains($planName, $keyword)) {
                return $type;
            }
        }
    }

    return 'cheapy';
}


private function applyMarkup($amount){
    if ($amount <= 100) {
        return ceil($amount * 1.0001); // 0.01%
    } elseif ($amount <= 3000) {
        return ceil($amount * 1.012); // 1.2%
    } else {
        return ceil($amount * 1.025); // 2.5%
    }
}


    // public function UpdateDataPackages(Request $request)
    // {
    //     $request->validate([
    //         'type' => 'required', 
    //     ]);
    
    //     try {
    //         $networks = ['mtn', 'airtel', 'glo', '9mobile']; 
    //         $all_plans = [];
    
    //         foreach ($networks as $network) {
    //             $url = "https://api.payscribe.ng/api/v1/data/lookup?network={$network}";
    //             $headers = [
    //                 'Authorization' => 'Bearer ' . env('PAYSCRIBE_PUBLIC_KEY'),
    //                 'Content-Type' => 'application/json',
    //                 // 'User-Agent' => 'Mozilla/5.0 (compatible; MyLaravelApp/1.0)'
    //             ];

    //             $requestProcess = Http::withHeaders($headers)
    //             ->get($url);

    //             if ($requestProcess->successful()) {
    //                 $json_data = json_decode($requestProcess->body());
    
    //                 if ($json_data && isset($json_data->message->details)) {
    //                     $plans_init = $json_data->message->details;
    
    //                     foreach ($plans_init as $entry) {
    //                         $networkid = $entry->network_name;
    //                         Log::info('Network Id', ['info' => $networkid]);
    
    //                         $existingPlans = DataPackList::where('network', $networkid)->get()->keyBy('plan_code');
    //                         $apiPlanCodes = [];
    //                         $bulkInsert = [];   
    
    //                         foreach ($entry->plans as $plan) {
    //                             $originalAmount = floatval($plan->amount);
    //                             $adjustedAmount = $this->applyMarkup($originalAmount);

    //                             $durationType = $this->detectDurationType( $plan->name);
    //                             $apiPlanCodes[] = $plan->plan_code;
    
    //                             if ($existingPlans->has($plan->plan_code)) {
    //                                 $existingPlan = $existingPlans[$plan->plan_code];
    //                                 if (
    //                                     $existingPlan->name !== $plan->name ||
    //                                     $existingPlan->alias !== $plan->alias ||
    //                                     $existingPlan->amount != $plan->amount ||
    //                                     $existingPlan->network != $networkid
    //                                     || $existingPlan->duration_type !== $durationType
    //                                     || $existingPlan->current_amount !== $adjustedAmount
    //                                 ) {
    //                                     $existingPlan->update([
    //                                         'name' => $plan->name,
    //                                         'alias' => $plan->alias,
    //                                         'amount' => $plan->amount,
    //                                         'network' => $networkid,
    //                                         'current_amount' => $adjustedAmount,
    //                                         'duration_type' => $durationType ??  'Duration',
    //                                     ]);
    //                                 }
    //                             } else {
    //                                 $bulkInsert[] = [
    //                                     'network' => $networkid,
    //                                     'plan_code' => $plan->plan_code,
    //                                     'name' => $plan->name,
    //                                     'alias' => $plan->alias,
    //                                     'amount' => $plan->amount,
    //                                     'current_amount' => $adjustedAmount,
    //                                     'duration_type' => $durationType ?? 'Duration',
    //                                     'created_at' => now(),
    //                                     'updated_at' => now()
    //                                 ];
    //                             }
    
    //                             // Optional: add to output array
    //                             $all_plans[] = [
    //                                 'network' => $networkid,
    //                                 'plan_code' => $plan->plan_code,
    //                                 'name' => $plan->name,
    //                                 'alias' => $plan->alias,
    //                                 'amount' => $plan->amount,
    //                                 'current_amount' => $adjustedAmount,
    //                                 'durationType' => $durationType ??  'Duration',
    //                             ];
    //                         }
    
    //                         if (!empty($bulkInsert)) {
    //                             DataPackList::insert($bulkInsert);
    //                         }
    
    //                         DataPackList::where('network', $networkid)
    //                             ->whereNotIn('plan_code', $apiPlanCodes)
    //                             ->delete();
    //                     }
    //                 } else {
    //                     Log::warning("No valid details returned for network: {$network}");
    //                 }
    //             } else {
    //                 Log::error("Failed to fetch plans for network: {$network}");
    //                 // continue;
    //             }
    //         }
    
    //         return response()->json(['details' => $all_plans, 'status' => 'success']);
    
    //     } catch (\Exception $e) {
    //         Log::error('UpdateDataPackages Exception', ['error' => $e->getMessage()]);
    //         return response()->json(['message' => 'An error occurred', 'status' => 'error'], 500);
    //     }
    // }
    


    public function UpdateDataPackages(Request $request)
    {
        $request->validate([
            'type' => 'required',
        ]);
    
        try {
            $networks = ['mtn', 'airtel', 'glo', '9mobile'];
            $bulkInsert = [];
    
            foreach ($networks as $network) {
                $url = "https://api.payscribe.ng/api/v1/data/lookup?network={$network}";
                $headers = [
                    'Authorization' => 'Bearer ' . env('PAYSCRIBE_PUBLIC_KEY'),
                    'Content-Type' => 'application/json',
                ];
    
                $requestProcess = Http::withHeaders($headers)->get($url);
    
                if ($requestProcess->successful()) {
                    $json_data = json_decode($requestProcess->body());
    
                    // Log full API body per network for debugging
                    Log::info("Payscribe response for {$network}", ['body' => $requestProcess->body()]);
    
                    if ($json_data && isset($json_data->message->details)) {
                        $plans_init = $json_data->message->details;
    
                        foreach ($plans_init as $entry) {
                            $networkid = $entry->network_name;
    
                            foreach ($entry->plans as $plan) {
                                if (!isset($plan->plan_code)) {
                                    Log::warning("Skipped plan with missing plan_code", (array) $plan);
                                    continue;
                                }
    
                                $originalAmount = floatval($plan->amount);
                                $adjustedAmount = $this->applyMarkup($originalAmount);
                                $durationType = $this->detectDurationType($plan->name);
    
                                $bulkInsert[] = [
                                    'network' => $networkid,
                                    'plan_code' => $plan->plan_code,
                                    'name' => $plan->name,
                                    'alias' => $plan->alias ?? '',
                                    'amount' => $plan->amount,
                                    'current_amount' => $adjustedAmount,
                                    'duration_type' => $durationType ?? 'Duration',
                                    'created_at' => now(),
                                    'updated_at' => now(),
                                ];
                            }
                        }
                    } else {
                        Log::warning("Invalid or unexpected data structure for {$network}", ['response' => $json_data]);
                    }
                } else {
                    Log::error("Failed to fetch plans for network: {$network}", ['status' => $requestProcess->status()]);
                }
            }
    
            // Replace old data
            DataPackList::truncate();
    
            // Chunked insertion for large data
            if (!empty($bulkInsert)) {
                $chunks = array_chunk($bulkInsert, 500);
                foreach ($chunks as $chunk) {
                    DataPackList::insert($chunk);
                }
            }
    
            return response()->json(['details' => $bulkInsert, 'status' => 'success']);
    
        } catch (\Exception $e) {
            Log::error('UpdateDataPackages Exception', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'An error occurred', 'status' => 'error'], 500);
        }
    }
    

    public function UpdateCable(Request $request){
        $request->validate([
         'varid' => 'required',
         'vaname' => 'required',
         'vacode' => 'required|string',
         'vaamount' => 'required|numeric',
        ]);
        try{
        $queryinsert = CablePackages::where( 'id', $request->input('varid'))->first();

        // dd( $queryinsert );
        if($queryinsert){
         $queryinsert->update(['packagename' => $request->input('vaname'), 
         'variation_code' => $request->input('vacode'), 
         'fixed_price' => $request->input('vaamount')]);
         return response()->json(['message' => 'Updated Successfully', 'status' => 'success']);
        }else{
         return response()->json(['message' => 'Couldn\'t update data package','status' => 'success' ]);
        }
     }catch(\Exception $e){
         return response() ->json(['message'=> $e->getMessage()]);
     }
 }


    public function UpdateData(Request $request){
       $request->validate([
        'networkid' => 'required',
        'networkprice' => 'required|numeric',
        'networkplan' => 'required|string',
        'networkpackagespace' => 'required|string',
       ]);
       try{
       $queryinsert = PurchaseDataItems::where('id', $request->input('networkid'))->first();
       if($queryinsert){
        $queryinsert->update(['networkPrice' => $request->input('networkprice'), 
        'networkPlansList' => $request->input('networkplan'), 
        'networkPackageSpace' => $request->input('networkpackagespace')]);
        return response()->json(['message' => 'Updated Successfully', 'status' => 'success']);
       }else{
        return response()->json(['message' => 'Couldn\'t update data package','status' => 'success' ]);
       }
    }catch(\Exception $e){
        return response() ->json(['message'=> $e->getMessage()]);
    }
}

    public function UpdateUtility(Request $request){
        
    } 



    public function LoginPost(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

    try{
    $validatecheck = Admin::where('email', $request->input(key: 'email'))->first();
    // dd($validatecheck);
    if($validatecheck && Hash::check($request['password'], $validatecheck->password)){
        // $username = $validatecheck->firstname;

        Session::put('userid', $request->input('email'));
        return response()->json(['message' => $request->input('email'), 'status' =>'success']);
    }else{
        return response()->json(['message' => 'Incorrect Details', 'status' => 'error']);
    }
}catch(\Exception $e){
    return response()->json(['message' => $e->getMessage()]);
}
    }
}
