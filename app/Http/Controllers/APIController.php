<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class APIController extends Controller
{
   public function UserAuthentication(Request $request){
     $request->validate([
      'fullname' => 'required',
      'username' => 'required',
      'email' => 'required',
      'password' => 'required',
      'contact'  => 'required',
     ]);
   }
}
