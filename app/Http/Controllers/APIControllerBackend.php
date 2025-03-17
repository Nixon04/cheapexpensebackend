<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserSignup; 
use App\Models\UserAccountDetails;
use App\Models\Transactions;
use App\Models\DataBodyPackage;
use App\Models\PurchaseDataItems;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Message;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\cache;
use App\Models\AppControlPanelVersion;
use App\Models\VirtualAccounts;
use App\Models\NotificationList;
use App\Mail\ForgotEmailConfirm;
use App\Models\NotificationReg;
use App\Models\AdsShow;
use App\Models\ClearNotification;
use App\Models\UsersBankDetails;
use App\Mail\VerifyPinEmail;
use App\Models\Referrals;
use App\Models\DeactivateAccount;
use App\Models\NewsFeedModel;
use App\Models\BankList;
use App\Models\AllServices;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\JsonResponse;
use Google\Auth\Credentials\ServiceAccountCredentials;


class APIControllerBackend extends Controller
{

  public function GetAllServices(){
   $getallservices = AllServices::orderBy('id')->get();
   return response()->json(['message' => $getallservices]);
  }



  private function getAccessToken()
  {
      // Path to the service account key file
      $keyFilePath = public_path('cheapexpense-811a4-firebase-adminsdk-xgppx-2fe3f56164.json'); // Adjust the filename as needed
      // Ensure the file exists
      if (!file_exists($keyFilePath)) {
          throw new \Exception("Service account key file not found at path: {$keyFilePath}");
      }
      // Define the required scopes
      $scopes = ['https://www.googleapis.com/auth/firebase.messaging'];
      // Create a new ServiceAccountCredentials object
      $credentials = new ServiceAccountCredentials($scopes, $keyFilePath);
      // Fetch the access token
      $accessToken = $credentials->fetchAuthToken()['access_token'];
      return $accessToken;
  }

  public function sendNotificationToAll(Request $request): JsonResponse
  {
      $accessToken = $this->getAccessToken(); // Your method to get the access token
  
      $client = new Client();
      $url = 'https://fcm.googleapis.com/v1/projects/cheapexpense-811a4/messages:send';
  
      $payload = [
          'message' => [
              'topic' => 'all_users',
              'data' => [
                  'title' => $request['title'] ?? "The time is here",
                  'body' => $request['body'] ?? "Lets get it now",
                  'extraData' => 'Your additional data here',
              ],
          ],
      ];
      try {
          $response = $client->post($url, [
              'headers' => [
                  'Authorization' => 'Bearer ' . $accessToken,
                  'Content-Type' => 'application/json',
              ],
              'json' => $payload,
          ]);
  
          return response()->json(['response' => json_decode($response->getBody()->getContents())]);
      } catch (\Exception $e) {
          return response()->json(['error' => $e->getMessage()], 500);
      }
  }



  public function signupController(Request $request)
  {
    $request->validate([
      'username' => 'required',
      'fullname' => 'required',
      'contact'  => 'required|numeric',
      'email'  => 'required|email',
      'password'  => 'required',
      'referral',
      'dob' => 'required',
      "userpin" => 'required',
  ]);
      
      $checkstatusdeletecall = DeactivateAccount::where('username', $request['username'])->first();
      if($checkstatusdeletecall){
        return response()->json(['message' => 'Incorrect details']);
      }

    
      $passwordhash = hash::make($request['password']);

       if($request['dob'] == ""){
        return response()->json(['message' =>  'date of birth is empty, please fill in', 'status' => 'info']);
       }
      $ref = rand(999999,111111);
      $user_ref_id = rand(999999,111111).$request['username'];

      $confirmuser = userSignup::where('username', $request->input('username'))->first();
      if($confirmuser){
        return response()->json(['message' => 'Username already registered']);
      }
      
      $confirmemail = userSignup::where('email', $request->email)->first();
      if($confirmemail){
        return response()->json(['message' => 'Email already registered']);
      }


      
      $insertdata = new UserSignup();
       $insertdata->fullname = $request->input('fullname');
       $insertdata->username = $request->input('username');
       $insertdata->contact = $request->input('contact');
       $insertdata->email = $request->input('email');
       $insertdata->password = $passwordhash;
       $insertdata->profileimage = "0";
       $insertdata->dob = $request->input('dob');
       $insertdata->resetcode = "";
       $insertdata->date = now();
       $insertdata->referral_id =  $ref;
       $insertdata->users_id = $request->input('userpin');
      $insertdata->save();

      if($insertdata){
        if($request['referral'] != ""){
        $reglink = UserSignup::where('referral_id', $request['referral'])->first();
          if($reglink){
          $regusername = $reglink->username;
        $insertdata = new Referrals();
        $insertdata->username = $request->input('username');
        $insertdata->reg_user = $regusername;
        $insertdata->reg_amount = "";
        $insertdata->reg_transact_total = "1";
        $insertdata->reglink = "";
        $insertdata->earning_per_referral = "";
        $insertdata->reg_total = "1";
        $insertdata->reg_date = Carbon::now('Africa/Lagos')->format('d M, Y');
        $insertdata->save();
        }else{
          return response()->json(['message' => 'No referral link found', 'status' => 'error']);
        }
        }
        // else{
    
        // }
      }
      if($insertdata){
          $insertdata = new UserAccountDetails();
          $insertdata->username = $request->input('username');
          $insertdata->user_ref_id = $user_ref_id;
          $insertdata->user_amount = "0.00";
          $insertdata->user_bonus = "0.00";
          $insertdata->last_update = now();
          $insertdata->withdrawer_count = "0";
          $insertdata->save();
      }

      $url = "https://api.paystack.co/dedicated_account/assign";
      $headers = [
         'Authorization' => "Bearer ".env('TEST_PAYSTACK_KEY'),
         'accept' => 'application/json',
     ];
     $response = Http::withHeaders($headers)->post($url, [
         "email" => $request['email'],
         "first_name" => $request['username'],
         "middle_name" => " ",
         "last_name" => "Doe",
         "phone" => $request['contact'],
         "preferred_bank" => "test-bank",
         "country" => "NG"
     ]);
     if($response->successful()){
       $checkvirtualaccountuser = VirtualAccounts::where('username', $request['username'])->first();
       if($checkvirtualaccountuser){
        return response()->json(['Name for this particular user is already registered']);
       }
      $date = Carbon::now()->setTimeZone('Africa/Lagos')->format('m d, Y : h:i:s');
      $insertvalue = new VirtualAccounts([
        "username" => $request['username'],
        "account_name" => "0",
        "account_number" => "0",
        "current_bank" => "WEMA",
        "date" => $date,
    ]);
      $insertvalue->save();
     $jsondecode = json_decode($response->body());  
    //  return response()->json(['message' => $jsondecode->message]);
     }
      return response()->json(['status' => 'success']);

  }



  public function showToken(): JsonResponse
  {
      $accessToken = $this->getAccessToken();
      return response()->json(['access_token' => $accessToken]);
  }

  public function DeleteAccount(Request $request){
    $request->validate(['username' => 'required', 'status' => 'required']);
     $valstatus = DeactivateAccount::where('username', $request['username'])->first();
     if($valstatus){
      return;
     }
     $deactivatefinal = new DeactivateAccount([
      'username' => $request->input('username'),
      'status' => $request->input('status'),
     ]);
     $deactivatefinal->save();
     return response()->json(['message' => 'Account Deactivated', 'status' => 'success']);
  }

  public function UpdatedVersion(Request $request){
    $valstatus = AppControlPanelVersion::where('name','AppVersion')->first();
    return response()->json(['message' => $valstatus]);
  }

  public function FetchDedicatedAccount(Request $request){
    $request->validate(['username' => 'required']);
    $valaccounts = VirtualAccounts::where('username', $request['username'])->first();
    if($valaccounts){
       return response()->json(['message' => $valaccounts, 'status' => 'success']);
    }else{
      return response()->json([ 'status' => 'Not succesful']);
    }
  }

  public function RefBonusReturn(Request $request){
    $request->validate(['username' => 'required|string',
  ]);
  $uservalue = Referrals::where('username', $request['username'])->get();
    $totaluseramount  = $uservalue->pluck('reg_amount')->sum();
    if($totaluseramount < 0 || $totaluseramount == 0 ){
      return response()->json(['message' => 'Insufficient Balance']);
    }
    $userwallet = UserAccountDetails::where('username', $request['username'])->first();
    try{
    if($userwallet){
      $usercurrentamount = $userwallet->user_amount;
      $usernewbalance = $usercurrentamount + $totaluseramount;
      $userwallet->update(['user_amount' => $usernewbalance ]);
      Referrals::where('username', $request['username'])->update(['reg_amount' => '0']);
      return response()->json(['message' => 'Fund transfer to wallet successful', 'status' => 'success']);
    }
    else{
      return response()->json(['message' => 'Not successful', 'status' => 'error']);
    }
  }catch(\Exception $e){
    return response()->json(['message' => 'Oops seems something went wrong, try again later',  'status' => $e->getMessage()]);
  }
}

public function ReferralLink(Request $request){
  $request->validate(['username' => 'required']);
  $val = UserSignup::where('username', $request['username'])->first();

  if($val){
    $link = $val->referral_id;
   return response()->json(['message' => $link]);
  }else{
    return response()->json(['message' => 'No referral Link']);
  }
}
  public function ReferralPackage(Request $request){
    $request->validate(['username' => 'required']);
    $valref = Referrals::where('username', $request['username'])->get();
    $message = [];
    // Fetching all necessary fields in one query
    $referrals = Referrals::where('username', $request['username'])
                          ->select('reg_amount', 'id', 'earning_per_referral', 'reg_status') // Fetch all required fields
                          ->get();

    $paidCount = $referrals->where('reg_status', 'paid')->count();
    $unpaidCount = $referrals->where('reg_status', 'unpaid')->count();
    $totalReg = $referrals->count();
    
    try { 
        if ($valref) {
          $message = [
           "reg_user" => $totalReg,
           "reg_paid" => $paidCount,
           "reg_unpaid" => $unpaidCount,
           "reg_status" => "status",
           "reg_total" => $referrals->where('reg_status', 'paid')->pluck('reg_amount')->sum(),
           "earning_per_referral" =>  '10',
          ];
     
            return response()->json(['message' => $message, 'status' => 'success']);
        }
    } catch (\Exception $e) {
        return response()->json(['message' => 'Something went wrong', 'status' => $e->getMessage()]);
    }

  }
  public function FetchImage(){
    return "fetch";
  }

   
  

    public function LoginController(Request $request){
        $request->validate([
            'username' => 'required',
            'password' => 'required',
            'fcmtoken' =>  'required',
        ]);

        try{
        $checkstatusdeletecall = DeactivateAccount::where('username', $request['username'])->first();
        if($checkstatusdeletecall){
          return response()->json(['message' => 'Incorrect details']);
        }

        $validatecheck = UserSignup::where('username', $request->input('username'))->orWhere('email', $request->input('username'))->first();
         $validatecheck->update(['fcmtoken' => $request->input('fcmtoken')]);
            if($validatecheck && hash::check($request['password'], $validatecheck->password)){
                return response()->json(['message' => 'information correct', 'status' =>'success', 'username' => $request->input('username')]);
            }else{
                return response()->json(['message' => 'sorry we couldn\'t validate your response', 'status' => 'failed']);
            }
        }catch(\Exception $e){
          return response()->json([$e->getMessage()]);
        }
    }

    public function LoginViaPin(Request $request){
       $request->validate([
         'username' => 'required',
         'pin' => 'required',
       ]);


       $check = Usersignup::where('username', $request['username'])->first();  
       try{    
       if($request['pin'] == $check->users_id){
        return response()->json(['message' =>'successful','status'=>'success']);
       }else{
        return response()->json(['message' =>'not successful', 'status' => 'failed']);

       }
       }catch(\Exception $error){
        return response()->json($error->getMessage());
       }
    }
    public function UserBalance(Request $request){
      $request->validate(['username' => 'required']);

      $checkuser = UserAccountDetails::where('username', $request['username'])->first();

      if($checkuser){
        return response()->json(['message' => $checkuser->user_amount, 'status' => 'success']);
      }else{
        return response()->json(['message' => 'Couldnt find the response data', 'status' => 'failed']);
      }
    }

    public function Transactions(Request $request){
     
      $request->validate(['username' => 'required']);
      $username = $request['username'];
       $airtimecachekey = "user_transaction_{$username}";
      //  if(cache::has($airtimecachekey)){
      //   return response()->json([
      //     'message' => Cache::get($airtimecachekey),
      //     'status' => 'success'
      //   ],);
      //  }
    
      $user_transaction = Transactions::where('username', $request['username'])->orderBy('id','DESC')->get();   
      $transaction = [];
     if($user_transaction){
       $transaction = $user_transaction;
   
      return response()->json(['message' => $transaction, 'status' => 'success']);
     }else{
      return response()->json(['messasge' => "Couldn't find user transaction", 'status' => 'failed']);
     }
    }

    public function GetResponseServices(Request $request){
      $getall = DataBodyPackage::orderBy('id', 'asc')->get();
     return response()->json(['packagesubname' => $getall]);
    }
    public function Populate(Request $request){
      $request->validate(['packagesubname' => 'required']);
      $collectitem = DataBodyPackage::where('packagereference', $request['packagesubname'])->get();
      if($collectitem){
        return response()->json(['message' => $collectitem]);
      }
      
    }

    public function ThirdSub(Request $request){
      $request->validate(['packagereference' => 'required']);

       $fetchdata = PurchaseDataItems::where('networkId', $request['packagereference'])->get();
       if($fetchdata){
        return response()->json(['message' => $fetchdata]);
       }else{
        return response()->json(['message' => 'response failed']);
       }
    }

    public function ViewSuccessReceipt(Request $request){
      $request->validate(['reference' => 'required']);
      $data = Transactions::where('reference', $request['reference'])->first();
      return response()->json(['message' => $data]);
    }

    public function AirtimeToCashPercentage(Request $request){
      $request->validate([]);
      $getversion = AppControlPanelVersion::where('name', "AirtimeToCashPercentage")->first();
      if($getversion){
         $version = $getversion->statement_approved;
         return response()->json(['message' => $version]);
      }else{
        return response()->json(['message' => "service turned down"]);
      }
    } 
    public function ListBanks(Request $request){
        // Retrieve the list of banks
        $getbanks = BankList::orderBy('id', 'DESC')->get();
        if ($getbanks->isNotEmpty()) {
            $getbanks->transform(function ($bank){
              $bank->bank_image = "https://cheapexpense.com/bankimages/". $bank->bank_image;
              return $bank;
            });
            // Return the modified collection as a JSON response
            return response()->json(["data" => $getbanks]);
        }
        return response()->json(["message" => "No banks found"], 404);
    }
    public function FetchAccountName(Request $request){
      $request->validate(
        [
          "account_number" => "required",
          "account_code" => 'required',
        ],
      );
      $accountname = $request['account_number'];
      $accountcode = $request['account_code'];
      $url = "https://api.paystack.co/bank/resolve?account_number=$accountname&bank_code=$accountcode";
      $headers = [
        'Authorization' => "Bearer ". env('PAYSTACK_SECRET_KEY'),
        'accept' => 'application/json',
    ];
      $requestcall = Http::withHeaders($headers)->get($url);
      try{
      if($requestcall && $requestcall->successful()){
        $jsonbody = $requestcall->getbody();
        $status = $requestcall->getStatusCode();
        $data = json_decode($jsonbody);
        $account_name = $data->data->account_name;
        return response()->json(['message' => $account_name]);
      }else{
        $jsonbody = $requestcall->json();
        $data = json_encode($jsonbody);
        return response()->json(['message' => "Couldn't verify account"]);
      }
    }catch(\Exception $e){
      return response()->json(['message' => "Oops seems something went wrong", "status" => "error"]);
    }
  }
    public function SaveUserBank(Request $request){
      $request->validate([
        'username' => "required",
        "bank_name" => "required",
        "bank_user_name" => "required",
        "user_account" => "required",
      ]);

      $validateaccount = UsersBankDetails::where('username', $request['username'])->first();
      if($validateaccount){
        return response()->json(['message' => 'Account already created']);
      }
      $insert = new UsersBankDetails();
      $insert->username = $request->input('username');
      $insert->bank_name_id = $request->input('bank_name');
      $insert->bank_user_name = $request->input('bank_user_name');
      $insert->user_bank = $request->input('user_bank') ?? "";
      $insert->user_account = $request->input('user_account');
      $insert->save();

    
      try{
      if($insert){
        return response()->json(['message' => "Account added successful", "status" => "success"]);
      }else{
        return response()->json(['message' => "Account not successful", "status" => "error"]);
      }
    }catch(\Exception $e){
      return response()->json(['message' => $e->getMessage()]);
    }
}
  
  public function FetchSaveUserBank(Request $request){
    $request->validate(['username' => 'required']);
    $result = DB::select('SELECT user_account as account, bank_user_name as bankuser, bank_name_id as bankname, bank_image as bankimage FROM `users_bank_details`
     ON users_bank_details.bank_name_id = bank_lists.bank_name WHERE `username` = :username', ['username' => $request['username']]);

     $url = "https://cheapexpense.com/bankimages/";
     if(!empty($result)){
      foreach ($result as $record){
        $record->bankimage = $url.$record->bankimage;
        $record->status = "success";
      }
     return response()->json(['message' => $result]);
     }else{
      return response()->json(['message' => "user account empty"]);
     }
  }   

  public function DeleteUserBank(Request $request){
    $request->validate(['username' => 'required']);

   
    $validate = UsersBankDetails::where('username', $request['username'])->first();
    $userholder = $request['username'];
    try{
    if($validate){
     $deletestatus = $validate->delete();
     $messages  = [];
     if($deletestatus){
      $messages = [
      'message' => 'Deleted successfully',
      "status" => 'success',
      "holder" => $userholder,
      ];

      // return response()->json(['message' => 'Deleted successfully', 'status' => 'success', 'holder' => $userholder]);
     return response()->json(['message' => $messages]);
     }else{
      $message = [
        'message' => 'Could\'nt validate',
        "status" => 'error',
        "holder" => '',
        ];
        return response()->json(['message' => $message]);
     }
    }else{
      $message = [
        'message' => 'Not active',
        "status" => 'error',
        "holder" => '',
        ];
      return response()->json(['message' => $message]);

    }
  }catch(\Exception $e){
    return response()->json(['message' => 'Oops seems something went wrong', 'status' => 'error']);
  }


  }

  public function SaveUsersImage(Request $request) {
    $request->validate(['image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
  "username" => "required"]);
    try {
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $imagepath = $file->getClientOriginalName();
            $file->move(public_path('userimages'), $imagepath); // Ensure the 'userimages' directory is within the 'public' directory
            $imageurl = asset('userimages/' . $imagepath);
            $validateuser = UserSignup::where('username', $request['username'])->first();
            if($validateuser){
              $validateuser->update(['profileimage' => $imagepath]);
            }
            return response()->json(['url' => $imageurl], 200);
        } else {
            return response()->json(['message' => 'No file found in the request'], 400);
        }
    } catch (\Exception $e) {
        return response()->json(['message' => $e->getMessage()], 500);
    }
}

  public function FetchSpecificImage(Request $request){
    $request->validate(['username' => "required"]);
    $fetchimage = UserSignup::where('username', $request['username'])->first();
    $getimage = asset('userimages/' .$fetchimage->profileimage);
    if($getimage){
      return response()->json(['message' => $getimage, "status" => "success"]);
    }else{
      return response()->json(['message' => $getimage, "status" => "success"]);
    }
  }
  public function FetchUserInfo(Request $request){
    $request->validate(['username' => 'required']);
    $validate = UserSignup::where('username', $request['username'])->first();
    try{
      $datavalues = [];
    if($validate){
      //  $datavalues [] = [
      //    "username" => $validate->username,
      //  ];
      return response()->json(['message' => $validate]);
    }else{
      return response()->json(['message' => 'Couldn\'t fetch data']);
    }
  }catch(\Exception $e){
    return response()->json(['message' => 'Oops something went wrong']);
  }
}
public function UpdateUserPassword(Request $request){
  $request->validate([
    'username' => 'required',
    'newpassword' => 'required',
  ]);

  $validateupdate = UserSignup::where('username', $request['username'])->first();

  if($validateupdate){
    $hashpassword = Hash::make($request['newpassword']);
    $validateupdate->update(['password' => $hashpassword ]);
    return response()->json(['message' => 'password saved successfully', 'status' => 'success']);
  }else{
    return response()->json(['message' => 'error occurred', 'status' => 'error']);
  }

}

public function VerifyUserPin(Request $request){
  $request->validate(['username' => 'required']);
  $valcheck = UserSignup::where('username', $request['username'])->first();
  try{
  if($valcheck){
    $email = $valcheck->email;
    $username = $valcheck->username;
    $passwordpin = rand(9999,1111);
    Mail::to($valcheck->email)->send(new verifyPinEmail($email, $passwordpin, $username));
    $update = $valcheck->update(['resetcode' => $passwordpin]);
     if($update){
    return response()->json(['message' => 'success', 'status' => 'success']);
     }else{
      return response()->json(['message' => 'Data not saved']);
     }
  }
  return response()->json(['message' => 'not success', 'status' => 'error']);
}catch(\Exception $e){
  return response()->json(['message' => 'Oops seems something went wrong, please try again later']);
}
}

public function ConfirmVerifyUserPin(Request $request){
  $request->validate([
    "username" => "required",
    "validpin" => "required",
  ]);

  $valcheck = UserSignup::where('username', $request['username'])->first();
  if($valcheck){
    try{
    $valpin = $valcheck->resetcode;
    if($valpin== $request['validpin']){
      return response()->json(['message' => "success"]);
    }
    return response()->json(['message' => 'Invalid Password']);
  }catch(\Exception $e){
    return response()->json(['message' => 'Oops seems something went wrong, please try again later']);
  }
}
}

public function ResetTransactionPin(Request $request){
  $request->validate(['username' => 'required', 'pin' => 'required']);

  $valcheck = UserSignup::where('username', $request['username'])->first();
  if($valcheck){
    try{
      $val = $valcheck->update(['users_id' => $request['pin']]);
      if($val){
        return response()->json(['message' => "success"]);
      }
      return response()->json(['message' => 'try again']);

    }catch(\Exception $e){
      return response()->json(['message' => 'Oops seems something went wrong, please try again later']);
    }
  }
}

public function FetchNotification(Request $request){
  $request->validate([
    "username" => 'required',
  ]);
  Log::info('content', ['content' => $request['username']]);
  $fetchnotification = NotificationList::orderBy('id', 'DESC')->get();
  $currentregusernotifier = NotificationReg::where('username', $request['username'])->first();
  $cregdate = $currentregusernotifier->created_at;
  try {
    if ($fetchnotification) {
        $newNotifications = [];
        foreach ($fetchnotification as $notification) {
            if ($notification->created_at > $cregdate) {
                $newNotifications[] = $notification;
            }
        }
        if (!empty($newNotifications)) {
            return response()->json(['message' => $newNotifications]);
        } else {
            return response()->json(['message' => $newNotifications]);
        }
    } else {
        return response()->json(['message' => []]);
    }
} catch (\Exception $e) {
    return response()->json(['error' => $e->getMessage()], 500);
}
}
public function FlushNotification(Request $request){
  $request->validate(['username' => "required"]);
  $notificationupdate = NotificationReg::where('username', $request['username'])->first();
  try{
      if($notificationupdate){
     $date = Carbon::now()->setTimeZone('africa/Lagos');
     $status = $notificationupdate->update(['created_at' => $date]);
     if($status){
      return response()->json(['message' => 'true']);
     }else{
      return response()->json(['message' => 'not true']);
     } 
  }
  return response()->json(['message' => 'false']);
 
}catch(\Exception $e){
  return response()->json(['message' => $e->getMessage()]);
}
}

public function NotificationCount(Request $request){
  $notification = NotificationList::orderBy('id', 'ASC')->get();
  $count = count($notification);
  return response()->json(['message' => $count]);
}

public function NewsFeed(Request $request){
  $getallcontents = NewsFeedModel::orderBy('id','DESC')->get();
  if($getallcontents->isNotEmpty()){
  $getallcontents->transform(function ($blogpost_image){
    $blogpost_image->news_image = "https://cheapexpense.com/blogimages/" .$blogpost_image->news_image;
    return $blogpost_image;
  });
  return response()->json(['message' => $getallcontents]);
}
}

public function Adshow(Request $request){
  $request->validate([]);
  $info = AdsShow::orderBy('id', 'DESC')->get();
  if($info){
    $info->transform(function($imagecall){
      $imagecall->image = "https://cheapexpense.com/blogimages/" .$imagecall->image;
      return $imagecall;
    });
    return response()->json(['message' => $info]);
  }else{

  }
}

public function ForgotPasswordState(Request $request){
  $request->validate(['email' => 'required|email']);

  $validateEmail = UserSignup::where('email', $request['email'])->first();
  if(!$validateEmail){
    return response()->json(['message' => 'Email isn\'t registered on our platform', 'status' => 'error']);
  }
  $tokencode = rand(9999,1111);

  try{
 $valuestate =  Mail::to($request['email'])->send(new ForgotEmailConfirm( $tokencode));
 if($valuestate){
  $validateEmail->update(['resetcode' => $tokencode]);
  return response()->json(['message' => 'Email sent successfully', 'status' => 'success']);
 }
}catch(\Exception $e){
  return response()->json(['message' => 'Oops seems something went wrong', 'status' => 'error']);
}
}
public function ConfirmPassword(Request $request){
  $request->validate(['code' => 'required|numeric']);
  $validateCode = UserSignup::where('resetcode', $request['code'])->first();
  if($validateCode){
    return response()->json(['message' => 'Code successfully', 'status' => 'success']);
  }else{
    return response()->json(['message' => 'Incorrect Code ', 'status' => 'error']);
  }
}

public function SetNewPassword(Request $request){
  $request->validate(['email' => 'required|string', 'password' => 'required']);

  $validate = UserSignup::where('email', $request['email'])->first();
  if($validate){
    $hash = Hash::make($request['password']);
   $status = $validate->update(['password' => $hash]);
  if($status){
    return response()->json(['message' => 'New password updated successfully', 'status' => 'success']);
  }
  return response()->json(['message' => 'Not updated', 'status' => 'success']);
  }
}
    
}
