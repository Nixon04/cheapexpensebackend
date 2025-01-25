<?php

namespace App\Http\Controllers;

use App\Models\PurchaseDataItems;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

use App\Models\UserSignup;
use App\Models\Transactions;
use App\Models\CablePackages;

use Inertia\Inertia;

class AuthController extends Controller
{
    //

    public function FetchRecord(){
      $monthlyData = DB::table('transactions')
    ->selectRaw('MONTHNAME(created_at) as month, SUM(amount) as total')
    ->groupByRaw('MONTHNAME(created_at), MONTH(created_at)')
    ->orderByRaw('MONTH(created_at)')
    ->get();

    // Ensure all months are present with zero values if no data exists
    $months = [
        'January', 'February', 'March', 'April', 'May', 'June',
        'July', 'August', 'September', 'October', 'November', 'December'
    ];

    $formattedData = array_map(function ($month) use ($monthlyData) {
        $data = $monthlyData->firstWhere('month', $month);
        return [
            'month' => $month,
            'total' => $data->total ?? 0
        ];
    }, $months);
     return response()->json(['returns' => $formattedData]);
    }
    public function LoginPage(){
        
      return Inertia::render('cheapx/auth/login');
    }
 public function HomeDashboard(){
    if(!Session::get('userid')){
        return Inertia::render('cheapx/auth/login');
       }
       $populate = Transactions::orderBy('id','DESC')->take(4)->get(); 
       $total = Transactions::sum('amount');
       $count = Transactions::count('id');


   //  calculating for via monthly changes

   // $monthlyData = DB::table('transactions')
   //      ->selectRaw('MONTHNAME(created_at) as month, SUM(amount) as total')
   //      ->groupByRaw('MONTH(created_at)')
   //      ->orderByRaw('MONTH(created_at)')
   //      ->get();

   $monthlyData = DB::table('transactions')
    ->selectRaw('MONTHNAME(created_at) as month, SUM(amount) as total')
    ->groupByRaw('MONTHNAME(created_at), MONTH(created_at)')
    ->orderByRaw('MONTH(created_at)')
    ->get();

    // Ensure all months are present with zero values if no data exists
    $months = [
        'January', 'February', 'March', 'April', 'May', 'June',
        'July', 'August', 'September', 'October', 'November', 'December'
    ];

    $formattedData = array_map(function ($month) use ($monthlyData) {
        $data = $monthlyData->firstWhere('month', $month);
        return [
            'month' => $month,
            'total' => $data->total ?? 0
        ];
    }, $months);

    return Inertia::render('cheapx/dashboard/home', ['data' => $populate, 'total' => $total, 'count' => $count, 'returns' => $formattedData]);
 }

 public function RegUsers(){
    if(!Session::get('userid')){
        return Inertia::render('cheapx/auth/login');
       }

   $populate  = UserSignup::orderBy('id', 'DESC')->get();
   if($populate->isNotEmpty()){
    $populate->transform(function($entry){
        if ($entry->profileimage === '0') {
            $entry->profileimage = "http://127.0.0.1:8000/userimages/0.png";
        } else{
        $entry->profileimage = "http://127.0.0.1:8000/userimages/". $entry->profileimage;
        }
        return $entry;
    });
   }

    return Inertia::render('cheapx/dashboard/regusers',['data' => $populate]);
 }

 public function Transactions(){
    if(!Session::get('userid')){
        return Inertia::render('cheapx/auth/login');
       }
       $populate = Transactions::orderBy('id','DESC')->get();
    return Inertia::render('cheapx/dashboard/transactions',['data' => $populate]);
 }


 public function Airtime(){
    if(!Session::get('userid')){
        return Inertia::render('cheapx/auth/login');
       }
    return Inertia::render('cheapx/dashboard/airtime');
 }

 public function Data(){
    if(!Session::get('userid')){
        return Inertia::render('cheapx/auth/login');
       }

       $populate = PurchaseDataItems::orderBy('id','DESC')->get(); 
    return Inertia::render('cheapx/dashboard/data', ['data' => $populate]);
 }

 public function Cable(){
    if(!Session::get('userid')){
        return Inertia::render('cheapx/auth/login');
       }
       $populate = CablePackages::orderBy('id','DESC')->get(); 
    return Inertia::render('cheapx/dashboard/cable', ['data'=> $populate]); 
 }

 public function Utility(){
    if(!Session::get('userid')){
        return Inertia::render('cheapx/auth/login');
       }
    return Inertia::render('cheapx/dashboard/utility');
 }

 public function SendNotification(){
    if(!Session::get('userid')){
        return Inertia::render('cheapx/auth/login');
       }
    return Inertia::render('cheapx/dashboard/notification');
 }

 public function LogOut(Request $request){
    if(!Session::get('userid')){
        return Inertia::render('cheapx/auth/login');
       }
      Session::pull('userid');

      return Inertia::render('cheapx/auth/login');
 }



}
