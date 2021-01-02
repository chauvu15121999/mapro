<?php

namespace App\Http\Controllers;
use DB;
use App\Models\Team;
use App\Models\typeteam;
use App\Models\Broads;
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
        $this->middleware(['auth','verified']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $teamtype = typeteam::all();
        $user = Auth::user()->id;
        $team = Team::where('by_user',$user)->get();
       
        return view('backend.pages.home',compact('teamtype','team'));
    }
}
