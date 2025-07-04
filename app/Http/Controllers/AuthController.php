<?php

namespace App\Http\Controllers;

use App\Models\PurchaseDataItems;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

use App\Models\UserSignup;
use App\Models\Transactions;
use App\Models\DataPackList;
use App\Models\CablePackages;

use Inertia\Inertia;

class AuthController extends Controller
{
    //
    public function FetchRecord() {
        $currentYear = now()->year;
        $currentMonth = now()->month;
    
        // Get monthly data only for the current year
        $monthlyData = DB::table('transactions')
            ->selectRaw('MONTH(created_at) as month_num, SUM(amount) as total')
            ->whereYear('created_at', $currentYear)
            ->groupByRaw('MONTH(created_at)')
            ->orderByRaw('MONTH(created_at)')
            ->get();
    
        // Prepare an array of months up to the current month
        $months = [];
        for ($i = 1; $i <= $currentMonth; $i++) {
            $months[$i] = date('F', mktime(0, 0, 0, $i, 10));
        }
    
        // Format the data with zero fallback
        $formattedData = [];
        foreach ($months as $num => $name) {
            $data = $monthlyData->firstWhere('month_num', $num);
            $formattedData[] = [
                'month' => $name,
                'total' => $data->total ?? 0
            ];
        }
    
        return response()->json(['returns' => $formattedData]);
    }
    
    public function LoginPage(){
        
      return Inertia::render('cheapx/auth/login');
    }
    public function HomeDashboard() {
        if (!Session::get('userid')) {
            return Inertia::render('cheapx/auth/login');
        }
    
        $today = now()->toDateString();
        $currentYear = now()->year;
    
        // Get the latest 4 transactions
        $populate = Transactions::orderByDesc('id')->take(4)->get(); 
    
        // Get total amount and number of all transactions
        $total = Transactions::sum('amount');
        $count = Transactions::count('id');
    
        // Get monthly aggregated data for the current year
        $monthlyData = DB::table('transactions')
            ->selectRaw('MONTH(created_at) as month_num, MONTHNAME(created_at) as month_name, SUM(amount) as total')
            ->whereYear('created_at', $currentYear)
            ->groupByRaw('MONTH(created_at), MONTHNAME(created_at)')
            ->orderByRaw('MONTH(created_at)')
            ->get();
    
        // Prepare months (Jan to Dec) and map data accordingly
        $currentMonth = now()->month;

        $months = [];
        for ($i = 1; $i <= $currentMonth; $i++) {
            $months[$i] = date('F', mktime(0, 0, 0, $i, 10));
        }
    
        $formattedData = [];
        foreach ($months as $num => $name) {
            $data = $monthlyData->firstWhere('month_num', $num);
            $formattedData[] = [
                'month' => $name,
                'total' => $data->total ?? 0
            ];
        }
    
        // Get today's transactions
        $todaysTransactions = Transactions::whereDate('created_at', $today)->get();
        $todaysCount = $todaysTransactions->count();

    
        return Inertia::render('cheapx/dashboard/home', [
            'data' => $populate,
            'total' => $total,
            'count' => $count,
            'returns' => $formattedData, // This will only contain current year data
            'todaysTransactions' => $todaysTransactions,
            'todaysCount' => $todaysCount
        ]);
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
            // route('mealxpress_drinks', ['filename' => $url->drinkimage ?? '']);
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

       $populate = DataPackList::orderBy('id','DESC')->get(); 
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
