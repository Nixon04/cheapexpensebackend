<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Inertia\Inertia;
class ViewHomeController extends Controller
{
    //
public function homeview(){
    return Inertia::render('home', ['message' =>'status required and finished to the starter']);
}

public function AuthHome(){
    return Inertia::render('auth.home');
}
public function Privacy(){
    return Inertia::render('privacy');
}
}
