<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {   
       $type=Auth()->user()->type;
      if($type=='admin')
      {
        return view('admin.admin');
      }
      elseif($type=='customer')
      {
        return view('admin.vendor');
      }
      else
      {
         Auth::logout();
         return redirect('/login');
      }
    }
}
