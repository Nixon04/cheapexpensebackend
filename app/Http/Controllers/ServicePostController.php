<?php

namespace App\Http\Controllers;
use App\Models\AppControlPanelVersion;
use App\Models\UserDetailsForCard;
use App\Models\VirtualCardAmounts;
use App\Models\VirtualCardList;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use App\Models\Transactions;
use App\Models\UserSignup;
use App\Models\UserAccountDetails;
use App\Models\VirtualAccounts;
use Illuminate\Support\Facades\DB;
use App\Models\CablePackages;
use Illuminate\Routing\Controller;
use App\Models\EligibleForCard;
use Illuminate\Support\Facades\Log;
use App\Models\DataPackList;
use App\Models\PinAttempt;
use Carbon\CarbonImmutable;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Client\ConnectionException;


use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class ServicePostController extends Controller
{


    public function Fillcheck(Request $request){
        $url="https://uzobestgsm.com/api/network/";
        $headers = [
            'Authorization' => "Token ".env('UZOBEST_KEY'),
            "accept" => "application/json",
        ];
        $response = Http::withHeaders($headers)->get($url);
        return response()->json(json_decode($response->body()));
    }

    public function CreateSavings(Request $request){
        // $url = "https://payscribe.ng/api/v1//savings/create";
        $url = "https://payscribe.ng/api/v1/data/lookup?network=airtel";

        $headers = [
            'Authorization' => 'Bearer ' . env('PAYSCRIBE_PUBLIC_KEY'),
            'Content-Type' => 'application/json',
        ];
        $payload = [
            "plan_id" => "sj8sjsksks7",
            "target_amount" => 500000,
            "target_title" => "Get a new house",
            "category" => "9sjjs1js18ss"
        ];

        $response = Http::withHeaders($headers)->get($url);
        if($response && $response->successful()){
            $json = json_decode($response->body());
            return response()->json([
                'message' => $json,
                'error' => 'check',
            ]);
        }else{
            $json = json_decode($response->body());
            return response()->json([
                'message' => $json,
                'error' => 'failed',
            ]);
        }


    }

    public function CardTransactionsList(Request $request){
        $request->validate([
            'ref_id' => 'required',
            'username' => 'required',
        ]);
        try{
        $reference = Str::uuid();
        $querydollaramount = VirtualCardAmounts::where('flag', $request->input('ref_id'))->first();
        if($querydollaramount){
            $userCardRefId = $querydollaramount->card_id;
        }
        

            $transactions = [];

            $start = Carbon::now()->startOfYear();
            $end = Carbon::now();

        while ($start->lessThanOrEqualTo($end)) {
        $monthStart = $start->copy()->startOfMonth()->toDateString();
        $monthEnd = $start->copy()->endOfMonth()->toDateString();

        $url = "https://payscribe.ng/api/v1/cards/$userCardRefId/transactions?start_date=$monthStart&end_date=$monthEnd&page_size=100&page=1";

        $headers = [
            'Authorization' => 'Bearer ' . env('PAYSCRIBE_PUBLIC_KEY'),
            'Content-Type' => 'application/json',
        ];

        $response = Http::withHeaders($headers)->get($url);
        
        if ($response->ok()) {
            $monthlyData = $response->json()['data'] ?? [];
            $transactions = array_merge($transactions, $monthlyData);
        }

        $start->addMonth();
    }
       $response = Http::withHeaders($headers)->get($url);
       if($response && $response->successful()){
        $json = json_decode($response->body());
        // $transactionstate = $json->message->details->transactions;
        $transactionstate = $json;
        return response()->json([
             'message' => $transactionstate,
        ]);
       }
       else{
        $json = json_decode($response->body());
        return response()->json([
            'message' => [],
        ]);
       }

    }catch(ConnectionException $e){
        return response()->json(['message' => 'Connection Error', 'status' => 'error']);
    }
    catch(\Exception $e){
        Log::info('CardTransactionList', [
            'error' => $e->getMessage(),
            'line' => $e->getLine(),
        ]);
        return response()->json([
            'message' => 'Oops seems something went wrong',
            'status' => 'error',
        ]);
    }

    }

    public function TerminateCard(Request $request){
        $request->validate([
            'username' => 'required',
            'ref' => 'required',
        ]);
        try{
            DB::beginTransaction();
        $reference = Str::uuid();
        $querydollaramount = VirtualCardAmounts::where('flag', $request->input('ref'))->first();
        if($querydollaramount){
            $userCardRefId = $querydollaramount->card_id;
        }

        $url = "https://payscribe.ng/api/v1/cards/$userCardRefId/terminate";
        $headers = [
            'Authorization' => 'Bearer ' . env('PAYSCRIBE_PUBLIC_KEY'),
            'Content-Type' => 'application/json',
        ];
       
        $payload = [
            'ref' => $reference,
        ];

        $response = Http::withHeaders($headers)->post($url, $payload);
        if($response && $response->successful()){
            $json = json_decode($response->body());
            $status = $json->status;
            if($status == true){
                DB::commit();
                $querydollaramount->update([
                    'card_flag' => 'terminate',
                ]);
                return response()->json([
                    'message' =>'Card Terminated Successfully',
                    'status' => 'success',
                ]);
            }else{
                DB::rollBack();
                return response()->json([
                    'message' =>'Couldn\'t Terminate Card',
                    'status' => 'error',
                ]);
            }
        }
    }
    catch(ConnectionException $e){
        DB::rollBack();
        return response()->json(['message' =>  'Connection Error', 'status' => 'error']);
    }
    catch(\Exception $e){
        DB::rollBack();
        Log::info('TerminateCard', [
            'error' =>  $e->getMessage(),
            'line' => $e->getLine(),
        ]);
        return response()->json(['message' => 'Oops seems something went wrong', 'status' => 'error']);
    }
    }



    public function WithdrawCard(Request $request){

        $request->validate([
            'username' => 'required',
            'amount' => 'required|numeric|min:1',
            'flag' => 'required',
            'card_amount' => 'required|numeric|min:1',
        ]);


        try{
        DB::beginTransaction();
        $amount = $request->input('amount');
        $reference = Str::uuid();
        $flag = $request->input('flag');


        $queryvalues = UserAccountDetails::where('username', $request->input('username'))->first();
        if($queryvalues){
            $queryvalues->increment('user_amount', $amount);    
        }

        $querydollaramount = VirtualCardAmounts::where('flag', $request->input('flag'))->first();
        if($querydollaramount){

            if ($querydollaramount->card_flag == "terminate") {
                return response()->json(['message' => 'Card is terminated, and can\'t be used', 'status' => 'error']);
            }
            if($querydollaramount->amount  <= 0){
                return response()->json([
                    'message' =>  'Insufficient Balance',
                    'status' => 'error',
                ]);
            }
            $querydollaramount->decrement('amount', $request->input('card_amount'));    
            $userCardRefId = $querydollaramount->card_id;
        }

        $url = "https://payscribe.ng/api/v1//cards/$userCardRefId/withdraw";
        $headers = [
            'Authorization' => 'Bearer ' . env('PAYSCRIBE_PUBLIC_KEY'),
            'Content-Type' => 'application/json',
        ];
       
        $payload = [
            'amount' => $request->input('card_amount'),
            'ref' => $reference,
        ];

        $response = Http::withHeaders($headers)->post($url, $payload);

        Log::info('Log', ['error' =>  $response]);
        if($response && $response->successful()){
            $json = json_decode($response->body());
            $status = $json->status;
            if($status == true){
                DB::commit();             
                return response()->json([
                    'message' => 'Withdrawer successful',
                    'status' => 'success',
                ]);
            }
            else{
                DB::rollBack();
                $querydollaramount->increment('amount', $request->input('amount'));
                $queryvalues->decrement('user_amount', $request->input('card_amount'));
             
                return response()->json([
                    'message' => 'Couldn\'t deduct amount from card. try again',
                    'status' => 'error',
                ]);
             }
           }
        }
        catch(ConnectionException $e){
            DB::rollBack();
            return response()->json(['message' =>  'Connection Error', 'status' => 'error']);
        }
        catch(\Exception $e){
            DB::rollBack();
            Log::info('WithdrawCard', [
                'error' =>  $e->getMessage(),
                'line' => $e->getLine(),
            ]);
            return response()->json(['message' => 'Oops seems something went wrong', 'status' => 'error']);
        }
    }

    public function FundCard(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'flag' => 'required',
            'amount' => 'required|numeric|min:1',
            'rate_amount' => 'required|numeric|min:1',
        ]);

        Log::info('FundStart', ['flag status' => $request['flag']]);
    
        DB::beginTransaction();
        try {
            $getuseramount = UserAccountDetails::where('username', $request->input('username'))->first();
            $getuserref = VirtualCardAmounts::where('flag', $request->input('flag'))->first();
    
            if (!$getuserref && $getuseramount) {
                return response()->json(['message' => 'User not found', 'status' => 'error']);
            }
    
            if ($getuserref->card_flag == "freeze") {
                return response()->json(['message' => 'You cannot fund a card in freeze state', 'status' => 'error']);
            }

            if ($getuserref->card_flag == "terminate") {
                return response()->json(['message' => 'Card is terminated, and can\'t be used', 'status' => 'error']);
            }
    
            if ($getuseramount->user_amount < $request->input('amount')) {
                return response()->json(['message' => 'Insufficient virtual card balance.', 'status' => 'error']);
            }

            $getuseramount->decrement('user_amount', $request->input('rate_amount'));
            $userRef = $getuserref->card_id;
    
            $url = "https://payscribe.ng/api/v1/cards/$userRef/topup";
            $headers = [
                'Authorization' => 'Bearer ' . env('PAYSCRIBE_PUBLIC_KEY'),
                'Content-Type' => 'application/json',
            ];

            $reference = Str::uuid();
    
            $payload = [
                "amount" => $request->input('amount'),
                "ref" => $reference,
            ];
    
            $response = Http::withHeaders($headers)->post($url, $payload);
    
            if ($response->successful()) {
                $json = json_decode($response->body());
                if ($json->status == true) {
                    DB::commit();
                    $getuserref->increment('amount', round($request->input('amount'), 2));
                    return response()->json(['message' => 'Card Funded Successfully', 'status' => 'success']);
                } else {
                    $getuseramount->decrement('user_amount', $request->input('rate_amount'));
                    DB::rollBack();
                    return response()->json(['message' => 'Card couldn\'t be funded.', 'status' => 'error']);
                }
            } else {
                Log::info('Failed Card Topup', ['error' => json_decode($response->body())]);
                $getuseramount->decrement('user_amount', $request->input('rate_amount'));
                DB::rollBack();
                return response()->json(['message' => 'Funding failed. Try again.', 'status' => 'error']);
            }
        } catch (ConnectionException $e) {
            DB::rollBack();
            return response()->json(['message' => 'Network Connection issue', 'status' => 'error']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('FundCard Error', ['error' => $e->getMessage(), 'line' => $e->getLine()]);
            return response()->json(['message' => 'An error occurred', 'status' => 'error']);
        }
    }
    

    public function ViewSuccessReceipt(Request $request){
        $request->validate(['reference' => 'required']);
        $data = Transactions::where('reference', $request['reference'])->first();
        if($data){
            return response()->json(['message' => $data]);
        }
        return response()->json(['message' => []]);
      }

      

      public function ListDataPackages(Request $request)
      {
          $request->validate([
              'network_type' => 'required',
          ]);
      
          $queryselect = DataPackList::where('network', $request->input('network_type'))
                          ->orderBy('id', 'DESC')
                          ->get();
      
          $customOrder = ['cheapy','daily', 'weekly', 'monthly', '2Month', '3Month', 'yearly'];
      
          $optionlists = $queryselect->pluck('duration_type')
              ->unique()
              ->sortBy(function ($item) use ($customOrder) {
                  $index = array_search($item, $customOrder);
                  return $index === false ? PHP_INT_MAX : $index;
              })
              ->values()
              ->toArray();
      
          if ($queryselect) {
              return response()->json([
                  'options' => $optionlists,
                  'data' => $queryselect,
              ]);
          }
      
          return response()->json([
              'message' => [],
              'status' => 'error'
          ]);
      }
      

      
    public function CurrentConversationRate(){
        $queryversion = AppControlPanelVersion::where('name', 'DollarPercentage')->first();
        if($queryversion){
            return response()->json(['message' => $queryversion]);
        }else{
            return response()->json(['message' => []]);
        }
    }

    

    public function Virtualcardlistcount(Request $request){
        $request->validate([
            'username' => 'required',
        ]);
        $querycard = VirtualCardAmounts::where('username', $request->input('username'))->get();
        if($querycard){
            return response()->json(['message' => count($querycard)]);
        }
        return response()->json(['message' => []]);
    }

    public function SwitchMainCard(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:virtual_card_amounts,card_id',
        ]);
    
        try {
            DB::transaction(function () use ($request) {
                // Reset any existing main card for the user
                VirtualCardAmounts::where('convert', 'main')
                    ->update(['convert' => '1500']);
                // Set the selected card to main
                VirtualCardAmounts::where('card_id', $request->input('id'))
                    ->update(['convert' => 'main']);
            });
            return response()->json(['message' => 'Card switched successfully', 'status' => "success"]);
    
        } catch (\Exception $e) {
            Log::error('switchCards', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'An error occurred', 'status' => 'error']);
        }
    }
    

    public function EnlistCards(Request $request){
        $request->validate([
            'username' => 'required',
        ]);
        try{
        $querycheckbind = DB::select('SELECT amount, virtual_card_lists.card_id, card_flag,transaction_id, flag, customer_name, card_type, currency, brand, first_six,
        last_six, masked, frontnumber, expiry, cvv, street, city, state, country, postal_code, `convert` FROM `virtual_card_amounts`
         LEFT JOIN `virtual_card_lists` ON virtual_card_amounts.card_id = virtual_card_lists.card_id WHERE virtual_card_amounts.username = :username', ['username' =>  $request->input('username')]);
      $last = null;
     if($querycheckbind){
            foreach($querycheckbind as $row){
               if($row->convert == "main"){
                $last = $row;
               }
            }
            return response()->json(['message' => $querycheckbind,
            'latest' => $last,
            'status' => 'success'],);
        }else{
            return response()->json(['message' => 'user not found' ,'status' => 'error']);
        }
      }catch(\Exception $e){
        Log::info('enlistcards', ['error' => $e->getMessage() . $e->getLine() ]);
        return response()->json(['message' => 'Oops something went wrong, contact customer card', 'status' => 'error']);
      }
    }


    public function UnFreezeCard(Request $request){
        $request->validate([
            'username' => 'required',
            'card_ref_id' => 'required',
        ]);
       
        try{
        $url = "https://payscribe.ng/api/v1/cards/558b6d65-48f1-4309-8fd4-c48ff07494b6/unfreeze";
        $headers =  [
            'Authorization' => 'Bearer '.env('PAYSCRIBE_PUBLIC_KEY'),
            'content-Type' => 'application/json',
        ];
        $response = Http::withHeaders($headers)->post($url, [
            'ref' => $request->input('card_ref_id'),
        ]);
        if($response->successful()){
            $jsondata = json_decode($response);
            Log::info('log', ['Unfreeze status' => $response]);
            if($jsondata->status ==  true){
                $querystatus  = VirtualCardAmounts::where('flag', $request->input('card_ref_id'))->where('username', $request->input('username'))->first();
                if($querystatus){
                    $querystatus->update(['card_flag' => 'unfreeze']);
                    return response()->json(['message'=> 'unfreezed Account deactivated successfully', 'status' => 'success']);           
                }else{
                }
                return response()->json(['message' => 'Card couldn\'t fully disactivate, contact customer care', 'status' => 'error']);
            }
        }else{
          Log::info('card declined on', ['error' => $response]);
            return response()->json(['message' => 'Was not successful', 'status' => 'error']);
        }
    }
    catch(ConnectionException $e){
        return response()->json([
            'message' => 'Connection Error',
            'status' => 'error', 
        ]);
    }
    catch(\Exception $e){
        Log::info('statuserror', ['info_unfreezedCard' => $e->getMessage()]);
        return response()->json(['message' => 'Oops seems something went wrong, try again', 'status_message' => $e->getMessage()]);
    }
    }

    public function FreezeCard(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'card_ref_id' => 'required',
        ]);
    
        try {
            DB::beginTransaction();
    
            $querystatus = VirtualCardAmounts::where('flag', $request->input('card_ref_id'))
                ->where('username', $request->input('username'))
                ->first();
    
            if (!$querystatus) {
                return response()->json(['message' => 'Card not found or does not belong to user', 'status' => 'error']);
            }
    
            $url = "https://payscribe.ng/api/v1//cards/cdaa128b-e591-4efc-830f-185a2973f865/freeze";
            $headers = [
                'Authorization' => 'Bearer ' . env('PAYSCRIBE_PUBLIC_KEY'),
                'Content-Type' => 'application/json',
            ];
    
            $response = Http::withHeaders($headers)->post($url, [
                'ref' => $request->input('card_ref_id'),
            ]);
    
            if ($response->successful()) {
                $jsondata = json_decode($response->body());
                Log::info('log', ['freeze status' => $jsondata]);
    
                if (isset($jsondata->status) && $jsondata->status == true) {
                    $querystatus->update(['card_flag' => 'freeze']);
                    DB::commit();
                    return response()->json(['message' => 'Freeze Activated successfully', 'status' => 'success']);
                } else {
                    return response()->json(['message' => 'API call failed, unable to freeze', 'status' => 'error']);
                }
            } else {
                Log::info('card declined on', ['error' => $response->body()]);
                return response()->json(['message' => 'API call was not successful', 'status' => 'error']);
            }
        } catch (ConnectionException $e) {
            DB::rollBack();
            return response()->json(['message' => 'Network connection issue', 'status' => 'error']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::info('statuserror', ['info_freezeCard' => $e->getMessage()]);
            return response()->json(['message' => 'Oops, something went wrong', 'status_message' => $e->getMessage()]);
        }
    }
    
    public function VerifyBillingStatus(Request $request){
        $request->validate([
            'username' => 'required',
        ]);
        $queryuser = UserSignup::where( 'username', $request->input('username'))->first();
        if(!$queryuser){
            return response()->json(['message' => 'username not found']);
        }
        $querycheck = UserDetailsForCard::where('email', $queryuser->email )->first();
        if($querycheck){
            return response()->json(['message' => 'true']);
        }
        else{
            return response()->json(['message' =>  'false']);
        }
    }

    public function FetchVirtualBalance(Request $request){
        $request->validate(
            [
                'username' => 'required',
            ],
        );
        $querycheck = VirtualCardAmounts::where('username', $request->input('username'))->first();
        if($querycheck){
            return response()->json(['message' => $querycheck->amount]);
        }
        else{
            return response()->json(['message' =>  '']);
        }
    }

    public function FetchVirtualTransactions(Request $request){
        $request->validate(
            [
                'username' => 'required',
            ],
        );
        $querycheck = Transactions::where('username', $request->input('username'))->Where('type_of_purchase', 'VirtualCard')->get();
        if($querycheck){
            return response()->json(['message' => $querycheck]);
        }
        else{
            return response()->json(['message' =>  []]);
        }
    }

    public function FilterAllCards(Request $request){
        $request->validate([
            'username' => 'required',
        ]);
    }

    public function SingleVirtualCreation(Request $request){
        $request->validate([
            'username' => 'required',
        ]);

        try{
            DB::beginTransaction();

        $querydatacheck = AppControlPanelVersion::where('name', 'DollarPercentage')->first();
        if($querydatacheck){
            $currentdollar_rate = $querydatacheck->version;
        }

        if(VirtualCardAmounts::where('username', $request->input('username'))->count() >= 4){
            return response()->json(['message' => 'Card creation is limited to only 4', 'status' => 'error']);
        };

        $querycharge = UserAccountDetails::where('username', $request->input('username'))->first();
        if($querycharge){
            $date = Carbon::now()->setTimezone('Africa/Lagos')->format('Y m,d, h i s. A');
            // lets check
            $dollarequivalent = $currentdollar_rate; //per 1 dollar
            $currentamount = $querycharge->user_amount;
            $current_covert = round($currentamount / $dollarequivalent, 3);
            Log::info('log', ['check' => $current_covert]);
                if($current_covert >= 2){
                    $getcurrentemail = UserSignup::where('username', $querycharge->username)->first();
                    $queryuserstatus = UserDetailsForCard::where('email', $getcurrentemail->email)->first();
                    if(!$queryuserstatus){
                        return response()->json(['message' => 'Billing information not created yet', 'status' => 'error']);
                    }
                    $charge  = 2 * $dollarequivalent; //on this we are now having back our naira currency
                    $reduction = $currentamount - $charge;
                    $querycharge->update(['user_amount' => $reduction]);
                    // card creation
                            $customer_uid = $queryuserstatus->customer_id;
                            // since we have gotten the customer id if correctly in place, lets create the card and fix in the billing address
                            $link = "https://payscribe.ng/api/v1//cards/create";
                            $headercollection =  [
                                'Authorization' => 'Bearer '.env('PAYSCRIBE_PUBLIC_KEY'),
                                'content-Type' => 'application/json',
                            ];
                            $reference = Str::uuid();
                            $brand_type = Arr::random(["VISA", "MASTERCARD"]);
                            $response = Http::withHeaders($headercollection)->post($link, [
                                "customer_id" => $customer_uid,
                                "currency" => "USD",
                                "brand" => $brand_type,
                                "amount" => 1,
                                "type" => "virtual",
                                "ref" => $reference,
                            ]);
                            if($response->successful()){
                                DB::commit();
                                $json_message = json_decode($response);
                                if($json_message->status == true){
                                    $stamp = $json_message->message;
                                    $transaction_id = $stamp->details->trans_id;
                                    // create virtual list
                                    $insertquery = new VirtualCardList([
                                                    'transaction_id' => $transaction_id,
                                                    'username' => $request->input('username'),
                                                    'customer_id' => '',
                                                    'customer_name' => '',
                                                    'card_id' => '',
                                                    'card_type' => '',
                                                    'currency' => '',
                                                    'brand' => '',
                                                    'name' =>  '',
                                                    'first_six' => '',
                                                    'last_six' => '',
                                                    'masked' => '',
                                                    'frontnumber' => '',
                                                    'expiry' => '',
                                                    'cvv' => '',
                                                    'street' => '',
                                                    'city' => '',
                                                    'state' => '',
                                                    'country' => '',
                                                    'postal_code' => '',
                                    ]);
                                    $insertquery->save();
                                       // sum the card up and assign main state
                                    if(!VirtualCardAmounts::where('username', $request->input('username'))->exists()){
                                        $queryadd = new VirtualCardAmounts([
                                        'username' => $request->input('username'),
                                        'card_id' => $json_message->message->details->card->id,
                                        'amount' => '1',
                                        "convert" => 'main',
                                        'flag' =>  $json_message->message->details->trans_id, //our own reference 
                                        'card_flag' => 'unfreeze',
                                        'date' => $date,
                                    ]);  
                                    }else{
                                        // remove the formal ideal with main
                                        $queryremove = VirtualCardAmounts::where('username', $request->input('username'))->where('convert', 'main')->first();
                                        if($queryremove){
                                            $queryremove->update(['convert' => '1500']);
                                        }
                                        $queryadd = new VirtualCardAmounts([
                                            'username' => $request->input('username'),
                                            'card_id' => $json_message->message->details->card->id,
                                            'amount' => '1',
                                            "convert" => 'main',
                                            'card_flag' => 'unfreeze',
                                            'flag' =>  $json_message->message->details->trans_id, //our own reference 
                                            'date' => $date,
                                        ]);
                               }

                                $queryadd->save();
                                return response()->json(['message' => 'Card Created Successfully', 'status' => 'success']);
                              }else{
                                $errorstatement = $json_message();
                                Log::info('SingleVirtual', ['error' => $errorstatement]);
                                return response()->json(['message' => 'Couldn\'t create card yet, Contact customer care','status' => 'error' ]);
                              }
                            }
                      }
                      else{
                        DB::rollBack();
                        return response()->json(['message' => 'Insufficient Balance', 'status' => 'error']);
                    }
                }
                
         }catch(\Exception $e){
            DB::rollBack();
            Log::info('SingleVirtualCard',['error' => $e->getMessage()]);
        }
    
        }
        


    
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
                   Log::info('entry', ['status' => 'success']); 
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


  

    public function AssignDedicatedVirtual(Request $request){
         $url = "https://api.paystack.co/dedicated_account/assign";
         $headers = [
            'Authorization' => "Bearer ".env('PAYSTACK_SECRET_KEY'),
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

        //   if(Cache::has($cachekey)){
        //     return response()->json(Cache::get($cachekey));
        //   }

          $url = "https://api.paystack.co/bank/resolve?account_number=$accountnumber&bank_code=$accountcode";
          $headers = [
            'Authorization' => "Bearer ". env('PAYSTACK_SECRET_KEY'),
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
                'Authorization' => "Bearer ". env('PAYSTACK_SECRET_KEY'),
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
            //  Cache::put($cachekey, $message, now()->addMinutes(30));
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
          
 public function EligibleForCardStat(Request $request){
    $request->validate([
        "username" => 'required',
    ]);
    $queryusers  = EligibleForCard::where('username', $request->input('username'))->first();
    if($queryusers){
        if($queryusers->bvn == "" || $queryusers->nin == ""){
           return response()->json(['message' => 'success']);
        }
    }
    return response()->json(['message' =>'failed']);
 }  
 
 public function ValidatedCardStatus(Request $request){
    $request->validate([
        "username" => 'required',
    ]);
    $queryusers  = EligibleForCard::where('username', $request->input('username'))->first();
    if($queryusers){
        return response()->json(['message' => $queryusers]);
    }
    return response()->json(['message' => [
        "bvn" => "",
        "nin" => ""
    ]]);
 }  


 public function CreateVirtualCardCustomer(Request $request){
    $request->validate([
        'username' => 'required',
        "othername" => 'required',
        "cityname" => 'required',
        "stateofcityname" => 'required',
        "postalcodename" => 'required',
    ]);

    try{
        DB::beginTransaction();
        $url = "https://payscribe.ng/api/v1/customers/create/full";
        $headers = [
            'Authorization' => 'Bearer '.env('PAYSCRIBE_PUBLIC_KEY'),
            'content-Type' => 'application/json',
        ];
        $queryuser = UserSignup::where('username', $request->input('username'))->first();
        if($queryuser){
             $email = $queryuser->email;
             $phone_contact = $queryuser->contact;
             $firstnamevalue = $queryuser->fullname;
             $dateofbirth = $queryuser->dob;
        }else{

            return response()->json(['message' => 'Invalid parameter','status' => 'error']);
        }
        $querydata = EligibleForCard::where('username', $request->input('username'))->first();
        if($querydata){
            $bvn = $querydata->bvn;
            $nin  = $querydata->nin;
            $revoke =  $querydata->revoke;
        }

        $response  = Http::withHeaders($headers)->post($url, [
            "first_name" => $firstnamevalue,
            "last_name" => $request->input('othername'),
            "phone" => $phone_contact,
            "email" => $email,
            "dob" => $dateofbirth,
            "country" => "NG",
            "address" => [
                "street" => $request->input('othername'),
                "city" => $request->input('cityname'),
                "state" => $request->input('stateofcityname'),
                "country" => "NG",
                "postal_code" =>$request->input('postalcodename'),
            ],
            "identification_type" => "BVN",
            "identification_number" => $bvn,
            "photo" => $revoke,
            "identity" => [
                "type" => "NIN",
                "number" => $nin,
                "country" => "NG",
                "image" => $revoke,
            ]
        ]);

        DB::commit();
        $responsestate  = json_decode($response->getBody());
        if($response->successful()){
            $jsonstatuscall = json_decode($response->getBody());
            Log::info('error', ['fetcher' => $responsestate]);
            if($jsonstatuscall->status == true){
                return response()->json(['message' => 'Created successfully', 'status' => 'success']);
            }
            else{
             return response()->json(['message' => 'Could not create billing record', 'status' => 'error']);
            }

        }else{
            return response()->json(['message' =>'Could not set up customer billing information, contact customer support', 'status' => 'error']);
        }
    }catch(\Exception $e){
        DB::rollBack();
        Log::error('status', ['status_checker' =>$e->getLine()]);
        return response()->json(['message' =>  $e->getMessage(), 'status' => $e->getMessage()]);
    } 
}

public function ValidateBvnAndNin(Request $request){
    $request->validate([
        "username" => "required",
        "type" => 'required|string',
        "value" => "required|integer|min:11"
    ]);
    
    try{
    $type = $request->input('type');
    $value = $request->input('value');

    $url = "https://payscribe.ng/api/v1/kyc/lookup?type=$type&value=$value";
    $headers = [
        'Authorization' => 'Bearer '.env('PAYSCRIBE_PUBLIC_KEY'),
        'Accept' => 'application/json',
    ];
    // to avoid unneccessary changes from payscribe we will validate if the value was passed already
    // incase of endpoint is exposed out elsewhere we can attain the integrity of each call to avoid
    // charges not permitted
    $queryifavailable = EligibleForCard::where('username', $request->input('username'))->first();
    if($queryifavailable){
        if($request->input('type') === $queryifavailable->bvn || $request->input('type') === $queryifavailable->nin){
            return response()->json(['message' => 'Validated already']);
        }
    }
    // end of call
    $response = Http::withHeaders($headers)->get($url);
    if($response->successful()){
        $jsonresponse  = json_decode($response->getBody());

        Log::info('error_state', ['status' => $jsonresponse]);
        if($jsonresponse->status  == "true"){
            $stats = $jsonresponse->message->details;
            $first = $stats->first_name;
            $image = $stats->photo;
            $base64 = $this->saveBase64Image($image);
            $dob = $stats->dob;
            $phone_number = $stats->phone_number;
            $gender = $stats->gender;
            $valuetype = $stats->value;
            $blacklist = $stats->blacklist;
            // crosschecking first if there is any saved data first
           
            $querycheck = EligibleForCard::where('username', $request->input('username'))->first();
            if($querycheck){
               if($request->input('type') == "bvn"){
                $querycheck->update(['bvn' => $valuetype,'revoke' => $base64]);
               }else{
                $querycheck->update(['nin' => $valuetype, 'revoke' => $base64]);
               }
            }
                //  insert new value based on the type (BVN/NIN)
            else{
                if($request->input('type')  === "bvn"){
                    $queryinsert = new EligibleForCard([
                        'username' => $request->input('username'),
                        'bvn' => $request->input('type'),
                        'nin' => "",
                        'revoke' => $base64,
                    ]);
                    $queryinsert->save();
                }
                // else its storing the value for bvn
                else if($request->input('type') == "nin"){
                    $queryinsert = new EligibleForCard([
                        'username' => $request->input('username'),
                        'bvn' => "",
                        'nin' => $request->input('type'),
                        'revoke' => $base64,
                    ]);
                    $queryinsert->save();
                }
              }
            }
            else{
               return response()->json(['message' => 'Identiication Number Declined', 'status' => 'error']); 
            }
          
        // for blocked users we can simply restrict users on that
      return response()->json(['message' => "Verified successfully", "status" => "success"]);
    }
    else{
        // $jsoncall = $response->getBody();
        return response()->json(['message' => "Couldn\'t validate your credential", "status" => "error"]);
    }
    }catch(\Exception $e){
        Log::info('server error', ['status' => $e->getMessage()]);
        return response()->json([ 'message' => 'Oops, please try again', 'error' => $e->getMessage()]);
  }
}
// convert base64 image

private function saveBase64Image($base64Image){
 $imageparts = explode(";base64,",$base64Image);
 if(count($imageparts) >  1){
    $imagebase64 = base64_decode($imageparts[1]);
    $extension = explode('/', $imageparts[0])[1];
 }else{
    $imagebase64 = base64_decode($base64Image);
    $extension = "png";
 }
//  if (!file_exists(dirname($fullPath))) {
//     mkdir(dirname($fullPath), 0777, true);
// }
 $filename = time() . '.'. $extension;

 return $filename;
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
        try{
            DB::beginTransaction();
        
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
            'Authorization' => "Bearer ".env('PAYSTACK_SECRET_KEY'),
            'accept' => 'application/json',
        ];
        $requeststatus = Http::withHeaders($headers)->post($url, [
            "source" => "balance", 
            "reason" => $request->input('reason'), 
            "amount" => $request->input('amount') * 100, 
            "reference" => $request->input('reference'),
            "recipient" => $request->input('recipient'),
            // "reason" => $request->input('reasons'),
        ]);
    

       if($requeststatus->successful() && $requeststatus){
        $status_log = $requeststatus->getBody();

        if($status_log->status == false){
            return response()->json([
                'message' => 'Oops seems there is an issue, please try again later',
                'status' => 'error',
            ]);
        }

        Log::info('Transfer Log', ['status ' => $status_log]);
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
            DB::commit();
            return response()->json(['message' => 'Successful', 'status' => 'success']);
         }else{
            return response()->json(['message' => 'Not successful', 'status' => 'failed']);
         }
        }else{
        $status_log = $requeststatus->getBody();
        Log::info('Error Log', ['status ' => $status_log]);
        DB::rollBack();
        $requeststatus->json();
                return response()->json(['message' => $requeststatus]);
            }
     
        }catch(\Exception $e){
            DB::rollBack();
            Log::info('Transfer Check', ['status' => $e->getMessage()]);
            return response()->json(['message' => 'Oops seems something went wrong', 'status' => 'error' ]);
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
                    'secret-key' => env('PAYSTACK_SECRET_KEY'),
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
   
//    public function AirtimePurchase(Request $request)
// {
//     $request->validate([
//         "username" => 'required|string',
//         "amount" => 'required|integer|min:10', 
//         "type_of_purchase" => 'required',
//         "sub_type_purchase" => 'required',   
//         "ref_num_purchase" => 'required',
//         "userpin" => 'required',
//         "date_of_purchase" => 'required',
//         "data_type" => 'required',
//         "reference" => 'required',
//     ]);  

//     try {

//         DB::beginTransaction();
//         // Fetch user balance with row-level locking
//         $check_amount = UserAccountDetails::where('username', $request->input('username'))->first();
//         if (!$check_amount || $check_amount->user_amount < $request->input('amount')) {
//             return response()->json(['message' => 'Insufficient Balance', 'status' => 'error']);
//         }else{
//                     $check_amount->update([
//                     'user_amount' => $check_amount->user_amount - $request->input('amount'),
//                 ]);
//         }

//         // Generate unique reference ID
//         $currentyear = Carbon::now()->timezone('Africa/Lagos')->format('Ymdhi');
//         $rand_ref = mt_rand(11111111, 99999999);
//         $generated_reference = $currentyear . $rand_ref;

//         // Validate user PIN
//         $password_pin_check = UserSignup::where('users_id', $request->input('userpin'))->first();
//         if (!$password_pin_check) {
//             return response()->json(['message' => 'Incorrect PIN', 'status' => 'error']);
//         }

//         $url = "https://vtpass.com/api/pay";
//         $headers = [
//             'api-key' => env('VTPASS_API_KEY'),
//             'secret-key' => env('VTPASS_SECRET_KEY'),
//             'accept' => 'application/json',
//         ];

//         // |unique:transactions,reference
//             $response = Http::withHeaders($headers)->post($url, [
//                 'request_id' => $generated_reference,
//                 'serviceID' => $request->input('sub_type_purchase'),
//                 'amount' => $request->input('amount'),
//                 'phone' => $request->input('ref_num_purchase'),
                
//             ]);

//             Log::info('Airtime Payloads', ['status' =>  $request,
//              ]);

//             if ($response->successful() && $response) {
//                 $status_log = $response->getBody();
//                 Log::info('AirtimeLog', ['status' => $status_log]);
//                 $transaction_data = new Transactions();
//                  $transaction_data->username = $request['username'];
//                  $transaction_data->amount = $request['amount'];
//                  $transaction_data->type_of_purchase = $request['type_of_purchase'];
//                  $transaction_data->sub_type_purchase = $request['sub_type_purchase'];
//                  $transaction_data->data_type = $request['data_type'];
//                  $transaction_data->status = $request['status'];
                 
//                  $transaction_data->ref_num_purchase = $request['ref_num_purchase'];
//                  $transaction_data->reference = $request['reference'];
//                  $transaction_data->date_of_purchase = $request->input('date_of_purchase');
//                  $transaction_data->save();
//                   DB::commit();

//                   $token = $password_pin_check->fcmtoken;

//                   $title = "Airtime Package";
//                   $body = "Sum of" .number_format($request->input('amount'), 2). "was successful.";
//                   $messaging = app('firebase.messaging');
//                   $message = CloudMessage::withTarget('token', $token)
//                    ->withNotification(notification: Notification::create($title, $body));

//                    Log::info('AirtimeLogToken', ['status'=> $message]);


//                 return response()->json(['message' => 'success', 'status' => 'success']);
//             } else {
//                 DB::rollBack();
//                 $status_log = $response->getBody();
//                 Log::info('AirtimeLog', ['status' => $status_log]);
//                 return response()->json(['message' => 'Airtime subscription failed', 'status' => 'error']);
//             }
//         }
//         catch(ConnectionException $e){
//             return response()->json([
//                 'message' => 'Network Error',
//                 'status' => 'error',
//             ]);
//         }
//         catch (\Exception $e) {
//             Log::error('Error purchasing airtime: ' . $e->getMessage());
//             return response()->json(['message' => 'An error occurred', 'status' => 'error']);
//         }
    
// }
    


private function RetryPasswordLock($username, $count)
{
    $querycheck = PinAttempt::where('user_id', $username)->first();

    if ($querycheck) {
        // check if lock time has passed
        if ($querycheck->attempt >= $count) {
            $lockExpires = Carbon::parse($querycheck->last_attempt_at)->addMinutes(5);

            if (now()->lessThan($lockExpires)) {
                // still locked
                return response()->json([
                    'message' => 'Account is Locked for 5 Minutes',
                    'status' => 'error',
                ]);
            } else {
                // lock expired → reset attempts
                $querycheck->update([
                    'attempt' => 0,
                    'last_attempt_at' => now(),
                ]);
            }
        }

        // if attempts not yet exhausted, increment
        $countid = 1;
        $querycheck->update([
            'attempt' => $querycheck->attempt + $countid,
            'last_attempt_at' => now(),
        ]);

        $remaining = $count - $querycheck->attempt - 1;
        return response()->json([
            'message' => 'Password failed, you have ' . max($remaining, 0) . ' attempts left',
            'status' => 'error',
        ]);
    } else {
        // first failed attempt
        $retryinsert = PinAttempt::create([
            'user_id' => $username,
            'attempt' => 1,
            'last_attempt_at' => now(),
        ]);

        return response()->json([
            'message' => 'Wrong Password, you have ' . ($count - 1) . ' more retries',
            'status' => 'error',
        ]);
    }
}



public function AirtimePurchase(Request $request)
{

    $key = 'global_airtime_requests';
    $limit = 10;
    $decay = 60; // seconds

    $count = Cache::get($key, 0);

    if ($count >= $limit) {
        return response()->json([
            'message' => 'Couldn\'t make purchase, please try again.',
            'status' => 'error',
        ], 429);
    }

    Cache::put($key, $count + 1, now()->addSeconds($decay));

    $request->validate([
        "username" => 'required|string',
        "amount" => 'required|integer|min:10', 
        "type_of_purchase" => 'required',
        "sub_type_purchase" => 'required',   
        "ref_num_purchase" => 'required',
        "userpin" => 'required',
        "date_of_purchase" => 'required',
        "data_type" => 'required',
        "reference" => 'required',
    ]);  


        $username = $request->input('username');
        $amount = $request->input('amount');
        $userpin = $request->input('userpin');
        $reference = $request->input('reference');

        if(Transactions::where('reference', $reference)->exists()){
            return response()->json([
                'message' => 'Duplicated Transaction Id',
                'status' => 'error',
            ]);
        }

        $lockdatereload = Transactions::where('username', $username)
        ->where('created_at', '>=', now()->subSeconds(20))->first();
        if($lockdatereload){
            return response()->json([
                'message' => 'Network Error, please try again',
                'status' => 'error',
            ]);
        }


        $count_timer = 3;
        $userpin = UserSignup::where('users_id', $userpin)->first();
        if(!$userpin){
           return $this->RetryPasswordLock($request->input('username'), $count_timer);
        }

                    
            try {
                        DB::beginTransaction();
                
                        // Check duplicate reference
                        if (Transactions::where('reference', $request->input('reference'))->exists()) {
                   
                            DB::rollBack();
                            Log::warning('Duplicate reference detected', [
                                'username' => $username,
                                'reference' => $request->input('reference'),
                            ]);
                            return response()->json(['message' => 'Duplicated transaction', 'status' => 'error'], 400);
                        }
                
                        // Check PIN
                        $user = UserSignup::where('users_id', $request->input('userpin'))->first();
                        if (!$user) {
                         
                            DB::rollBack();
                            Log::warning('Incorrect PIN', ['username' => $username]);
                            return response()->json(['message' => 'Incorrect PIN', 'status' => 'error'], 400);
                        }
                
                        // Lock and check balance
                        $account = UserAccountDetails::where('username', $username)
                            ->lockForUpdate()
                            ->first();
                
                        if (!$account || $account->user_amount < $request->input('amount')) {
                          
                            DB::rollBack();
                            Log::warning('Insufficient balance', [
                                'username' => $username,
                                'amount' => $request->input('amount'),
                            ]);
                            return response()->json(['message' => 'Insufficient Balance', 'status' => 'error'], 400);
                        }
                
                        // Deduct balance
                        $account->user_amount -= $request->input('amount');
                        $account->save();
                
                        // Generate VTpass request ID
                        $generated_reference = Carbon::now()->timezone('Africa/Lagos')->format('YmdHisv') . mt_rand(10000000, 99999999); // Include milliseconds
                
                        // VTPass API call
                        $response = Http::withHeaders([
                            'api-key' => env('VTPASS_API_KEY'),
                            'secret-key' => env('VTPASS_SECRET_KEY'),
                            'accept' => 'application/json',
                        ])->post("https://vtpass.com/api/pay", [
                            'request_id' => $request->input('reference'),
                            'serviceID' => $request->input('sub_type_purchase'),
                            'amount' => $request->input('amount'),
                            'phone' => $request->input('ref_num_purchase'),
                        ]);
                
                        Log::info('Airtime Payload', [
                            'request' => $request->all(),
                            'generated_reference' => $request->input('reference'),
                            'ip' => $request->ip(),
                        ]);
                
                        if ($response->successful() && isset($response->json()['code']) && $response->json()['code'] === '000') {
                            // Save transaction
                            Transactions::create([
                                'username' => $username,
                                'amount' => $request->input('amount'),
                                'type_of_purchase' => $request->input('type_of_purchase'),
                                'sub_type_purchase' => $request->input('sub_type_purchase'),
                                'data_type' => $request->input('data_type'),
                                'status' => 'success',
                                'ref_num_purchase' => $request->input('ref_num_purchase'),
                                'reference' => $request->input('reference'), // Use server-generated reference
                                'date_of_purchase' => $request->input('date_of_purchase'),
                            ]);
                
                            Log::info('Airtime VTpass Response', ['body' => $response->body()]);
                
                            DB::commit();
                         
                            return response()->json(['message' => 'success', 'status' => 'success'], 200);
                        } else {
                        
                            DB::rollBack();
                            Log::info('Airtime VTpass Failed', ['body' => $response->body()]);
                            return response()->json(['message' => 'Airtime subscription failed', 'status' => 'error'], 400);
                        }
                    } catch (ConnectionException $e) {
                   
                        DB::rollBack();
                        Log::error('Network Error: ' . $e->getMessage());
                        return response()->json(['message' => 'Network Error', 'status' => 'error'], 500);
                    } catch (\Throwable $e) {
                   
                        DB::rollBack();
                        Log::error('Airtime Error: ' . $e->getMessage(), [
                            'username' => $username,
                            'ip' => $request->ip(),
                        ]);
                        return response()->json(['message' => 'An error occurred', 'status' => 'error'], 500);
                    }

        catch(ConnectionException $e){
            return response()->json([
                'message' => 'Network Error',
                'status' => 'error',
            ]);
        }
        catch (\Exception $e) {
            Log::error('Error purchasing airtime: ' . $e->getMessage());
            return response()->json(['message' => 'An error occurred', 'status' => 'error']);
        }
    
}
    

// public function AirtimePurchase(Request $request)
// {
//     // Validate request
//     $request->validate([
//         'username' => 'required|string',
//         'amount' => 'required|integer|min:10',
//         'type_of_purchase' => 'required',
//         'sub_type_purchase' => 'required',
//         'ref_num_purchase' => 'required',
//         'userpin' => 'required',
//         'date_of_purchase' => 'required',
//         'data_type' => 'required',
//         'reference' => 'required',
//     ]);

 
    
//     $username = $request->input('username');
//     $rateKey = 'airtime_' . $username . '_' . $request->ip(); // Include IP for stricter limiting

//     // Check for recent transactions (timing validation)
//     $lastTransaction = Transactions::where('username', $username)
//         ->where('created_at', '>=', now()->subSeconds(5))
//         ->first();
//     if ($lastTransaction) {
//         Log::warning('Concurrent transaction attempt blocked', [
//             'username' => $username,
//             'ip' => $request->ip(),
//             'time' => now(),
//         ]);
//         return response()->json(['message' => 'Please wait 5 seconds before making another purchase', 'status' => 'error'], 429);
//     }

//     // Rate limiter (apply before lock to reduce load)
//     if (RateLimiter::tooManyAttempts($rateKey, 1)) {
//         Log::warning('Rate limit exceeded', [
//             'username' => $username,
//             'ip' => $request->ip(),
//             'time' => now(),
//         ]);
//         return response()->json(['message' => 'Too many requests, please wait', 'status' => 'error'], 429);
//     }
//     RateLimiter::hit($rateKey, 10); // Hit immediately, 1 request per 10 seconds


//     // Check for recent transactions (timing validation)
//     $lastReference = Transactions::where('reference', $request->input('reference'))->first();
//     if($lastReference){
//      return response()->json(['message' => 'Duplicated Transaction','status' => 'error']);
//     }

//     // Acquire Redis lock
//     $lockKey = 'airtime_lock_' . $username;
//     $lock = Cache::lock($lockKey, 5); // 5-second lock to minimize window
//     if (!$lock->get()) {
//         Log::warning('Redis lock acquisition failed', [
//             'username' => $username,
//             'ip' => $request->ip(),
//             'time' => now(),
//         ]);
//         return response()->json(['message' => 'Another transaction is in progress', 'status' => 'error'], 429);
//     }

//     if (Transactions::where('reference', $request->input('reference'))->exists()) {
//      $lock->release();
//      DB::rollBack();
//      Log::warning('Duplicate reference detected', [
//          'username' => $username,
//          'reference' => $request->input('reference'),
//      ]);
//      return response()->json(['message' => 'Duplicated transaction', 'status' => 'error'], 400);
//  }

//     try {
//         DB::beginTransaction();

//         // Check duplicate reference
//         if (Transactions::where('reference', $request->input('reference'))->exists()) {
//             $lock->release();
//             DB::rollBack();
//             Log::warning('Duplicate reference detected', [
//                 'username' => $username,
//                 'reference' => $request->input('reference'),
//             ]);
//             return response()->json(['message' => 'Duplicated transaction', 'status' => 'error'], 400);
//         }

//         // Check PIN
//         $user = UserSignup::where('users_id', $request->input('userpin'))->first();
//         if (!$user) {
//             $lock->release();
//             DB::rollBack();
//             Log::warning('Incorrect PIN', ['username' => $username]);
//             return response()->json(['message' => 'Incorrect PIN', 'status' => 'error'], 400);
//         }

//         // Lock and check balance
//         $account = UserAccountDetails::where('username', $username)
//             ->lockForUpdate()
//             ->first();

//         if (!$account || $account->user_amount < $request->input('amount')) {
//             $lock->release();
//             DB::rollBack();
//             Log::warning('Insufficient balance', [
//                 'username' => $username,
//                 'amount' => $request->input('amount'),
//             ]);
//             return response()->json(['message' => 'Insufficient Balance', 'status' => 'error'], 400);
//         }

//         // Deduct balance
//         $account->user_amount -= $request->input('amount');
//         $account->save();

//         // Generate VTpass request ID
//         $generated_reference = Carbon::now()->timezone('Africa/Lagos')->format('YmdHisv') . mt_rand(10000000, 99999999); // Include milliseconds

//         // VTPass API call
//         $response = Http::withHeaders([
//             'api-key' => env('VTPASS_API_KEY'),
//             'secret-key' => env('VTPASS_SECRET_KEY'),
//             'accept' => 'application/json',
//         ])->post("https://vtpass.com/api/pay", [
//             'request_id' => $request->input('reference'),
//             'serviceID' => $request->input('sub_type_purchase'),
//             'amount' => $request->input('amount'),
//             'phone' => $request->input('ref_num_purchase'),
//         ]);

//         Log::info('Airtime Payload', [
//             'request' => $request->all(),
//             'generated_reference' => $request->input('reference'),
//             'ip' => $request->ip(),
//         ]);

//         if ($response->successful() && isset($response->json()['code']) && $response->json()['code'] === '000') {
//             // Save transaction
//             Transactions::create([
//                 'username' => $username,
//                 'amount' => $request->input('amount'),
//                 'type_of_purchase' => $request->input('type_of_purchase'),
//                 'sub_type_purchase' => $request->input('sub_type_purchase'),
//                 'data_type' => $request->input('data_type'),
//                 'status' => 'success',
//                 'ref_num_purchase' => $request->input('ref_num_purchase'),
//                 'reference' => $request->input('reference'), // Use server-generated reference
//                 'date_of_purchase' => $request->input('date_of_purchase'),
//             ]);

//             Log::info('Airtime VTpass Response', ['body' => $response->body()]);

//             DB::commit();
//             $lock->release();
//             return response()->json(['message' => 'success', 'status' => 'success'], 200);
//         } else {
//             $lock->release();
//             DB::rollBack();
//             Log::info('Airtime VTpass Failed', ['body' => $response->body()]);
//             return response()->json(['message' => 'Airtime subscription failed', 'status' => 'error'], 400);
//         }
//     } catch (ConnectionException $e) {
//         $lock->release();
//         DB::rollBack();
//         Log::error('Network Error: ' . $e->getMessage());
//         return response()->json(['message' => 'Network Error', 'status' => 'error'], 500);
//     } catch (\Throwable $e) {
//         $lock->release();
//         DB::rollBack();
//         Log::error('Airtime Error: ' . $e->getMessage(), [
//             'username' => $username,
//             'ip' => $request->ip(),
//         ]);
//         return response()->json(['message' => 'An error occurred', 'status' => 'error'], 500);
//     }
// }


public function NewDataPurchase(Request $request){
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
        "network_id" => "required",
        "plan" => "required",
    ]);

    try{

 DB::beginTransaction();

   $password_pin_check = UserSignup::where('users_id', $request['userpin'])->first();
   if(!$password_pin_check){
      return response()->json(['message' => 'Incorrect PIN', 'status' => 'error']);
   }
   $check_amount = UserAccountDetails::where('username', $request['username'])->first();
   
      if ($request['amount'] <= 0 || $check_amount->user_amount < $request['amount']) {
         return response()->json(['message' => 'Insufficient Balance', 'status' => 'error']);
        }
    
     else{
      $new_amount = $check_amount->user_amount - $request['amount'];
      $check_amount->update(['user_amount' => $new_amount]);
   }

    $url="https://api.payscribe.ng/api/v1/data/vend";
    $headers = [
        'Authorization' => "Bearer ".env('PAYSCRIBE_PUBLIC_KEY'),
        "accept" => "application/json",
    ];

    $requestDataCall = Http::withHeaders($headers)
    ->post($url,[
        "plan" => $request->input('plan'),
        "recipient" => $request->input('ref_num_purchase'),
        "network" => $request->input('network_id'),
        "ref" => $request->input('reference'),
    ]);


    Log::info('reference', ['plan' => $request->input('plan'), 'network' => $request->input('network_id')]);
     DB::commit();

        $dusername = $request->input('username');
        $damount = $request->input('amount');
        $dtypeofpurchase = $request->input('type_of_purchase');
        $dsubtypepurchase = $request->input('sub_type_purchase');
        $ddatatype = $request->input('data_type');
        $dstatus = $request->input('status');
        $drefnumpurchase = $request->input('ref_num_purchase');
        $dreference = $request->input('reference');
        $ddateofpurchase = $request->input('date_of_purchase');

    if($requestDataCall->successful() && isset($requestDataCall)){
        $requestdata = $requestDataCall->body();
        Log::info('catch', ['reference' =>  $requestdata]);

        $messagedecode = json_decode($requestdata);
        $status =  $messagedecode->status;
        $reference_state = $messagedecode->message->details->trans_id;
    
        Log::info('datapurchase Line for successful page', ['reference' =>  $requestdata]);

       if($status ==  true){
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

        if($transaction_data){
            $token = $password_pin_check->fcmtoken;
            $title = "Data Package";
            $body = "Sum of" .number_format($request->input('amount'), 2). "data worth has been purchased";
            $messaging = app('firebase.messaging');
            $message = CloudMessage::withTarget('token', $token)
             ->withNotification(notification: Notification::create($title, $body));

            return response()->json(['message' => $status, 'status' => 'success']);
        }
        
       }else{
        $check_amount->increment('user_amount', $request['amount']);
        return response()->json(['message' => 'Error Couldn\'t fully transact, amount refunded back', 'status' => 'error']);
       }
    }
    else{
        $check_amount->increment('user_amount', $request['amount']);
        $requestdata = $requestDataCall->body();
        Log::info('Not successful', ['reference' =>  $requestdata]);

        return response()->json(['message' => 'Error Couldn\'t fully transact, amount refunded back', 'status' => 'error']);
    }
  
}
catch (ConnectionException $e) {
    DB::rollBack();
    return response()->json(['message' => 'Network connection issue', 'status' => 'error']);
}

catch(\Exception $e){
    DB::rollBack();
    Log::info('DataPurchaseErrors', ['error' =>  $e->getMessage(), 'line' => $e->getLine()]);
    return response()->json(['message' => 'Oops something went wrong, try again later','status' =>'error', 'log' => $e->getMessage()]);
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
        "reference" => 'required',
        "network_id" => 'required',
        "plan" => "required",
    ]);

    
          
    $key = 'global_data_requests';
    $limit = 2;
    $decay = 60; // seconds

    $count = Cache::get($key, 0);

    if ($count >= $limit) {
        return response()->json([
            'message' => 'Couldn\'t make purchase, please try again.',
            'status' => 'error',
        ], 429);
    }
        

   
    if(Transactions::where('reference', $request->input('reference'))->exists()) {
        return response()->json(['message' => 'Duplicate transaction', 'status' => 'error']);
        }
        
        
           $check_amount = UserAccountDetails::where('username', $request['username'])->first();
   
      if ($request['amount'] <= 0 || $check_amount->user_amount < $request['amount']) {
    return response()->json(['message' => 'Insufficient Balance', 'status' => 'error']);
    }
    
     else{
      $new_amount = $check_amount->user_amount - $request['amount'];
      $check_amount->update(['user_amount' => $new_amount]);
  }
  

    try{
        
            DB::beginTransaction();

   $dusername = $request->input('username');
   $damount = $request->input('amount');
   $dtypeofpurchase = $request->input('type_of_purchase');
   $dsubtypepurchase = $request->input('sub_type_purchase');
   $ddatatype = $request->input('data_type');
   $dstatus = $request->input('status');
   $drefnumpurchase = $request->input('ref_num_purchase');
   $dreference = $request->input('reference');
   $ddateofpurchase = $request->input('date_of_purchase');

   $password_pin_check = UserSignup::where('users_id', $request['userpin'])->lockForUpdate()->first();


   if(!$password_pin_check){
      return response()->json(['message' => 'Incorrect PIN', 'status' => 'error']);
   }
  


    $url="https://uzobestgsm.com/api/data/";
    $headers = [
        'Authorization' => "Token ".env('UZOBEST_KEY'),
        "accept" => "application/json",
    ];

    $networkMap = [
        'MTN' => 1,
        'AIRTEL' => 4,
        'GLO' =>2,
        '9MOBILE' => 3,
    ];
    
    $networkKey = strtoupper(trim($request->input('network_id')));
    if (!isset($networkMap[$networkKey])) {
        return response()->json(['message' => 'Invalid network type', 'status' => 'error']);
    }
    $networkId = $networkMap[$networkKey];


    $requestDataCall = Http::withHeaders($headers)->post($url,[
        "network" => $networkId,
        "mobile_number" => $request->input('ref_num_purchase'),
        "plan" => $request->input('plan'),
        "Ported_number" =>  "false",
    ]);

    if($requestDataCall->successful() && $requestDataCall){

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

        Log::info('datapurchase Line for successful page', ['reference' =>  $dreference]);
        
        DB::commit();


        if($transaction_data){
            return response()->json(['message' => $status, 'status' => 'success']);
        }
        
    }
    else{  
        if($requestDataCall->getStatusCode() == "400"){
            Log::info('LogDataPurchase', ['status' => $requestDataCall->body()]);
            $return_amount = UserAccountDetails::where('username', $request['username'])->first();
              if($return_amount){
                  $initial_amount = $return_amount->user_amount;
                  $totalremainingsum = $initial_amount + $request->input('amount');
                  $return_amount->update(['user_amount' =>$totalremainingsum]);
              }
            return response()->json(['message' => 'Current Package not available', 'status' => 'error']);
        }
     return response()->json(['message' => 'not successful', 'status' => 'false']);
    }

}

catch (ConnectionException $e) {
DB::rollBack();
return response()->json(['message' => 'Network connection issue', 'status' => 'error']);
}


catch(\Exception $e){
    Log::info('DataPurchase Error Catch', ['status' => $e->getMessage(), 'line' => $e->getLine()]);
    DB::rollBack();
    return response()->json(['message' => 'Oops something went wrong, try again later','status' =>'error', 'log' => $e->getMessage(), 'line' => $e->getLine()]);
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
                        "accept" => 'application/json',
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
            
  
             }else{
                return response()->json(['message' => "Not successful"]);
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
