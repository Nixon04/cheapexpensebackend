<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\Admin;
use App\Models\PurchaseDataItems;
use App\Models\CablePackages;


class PostController extends Controller
{
    //

    // Route::post('/cheapx/auth/loginpost', 'LoginPost');
    // Route::post('/cheapx/auth/updatedata', 'UpdateData');
    // Route::post('/cheapx/auth/utility', 'UpdateUtility');
    // Route::post('/cheapx/auth/cable', 'UpdateCable');
    // Route::post('/cheapx/auth/sendnotification', 'sendNotification');



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


     public function sendNotification(Request $request){
        
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
