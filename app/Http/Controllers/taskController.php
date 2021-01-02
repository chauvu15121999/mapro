<?php

namespace App\Http\Controllers;
use App\Models\listCart;
use App\Models\User;
use App\Models\cards;
use App\Models\checkList;
use Carbon\Carbon; // dùng auth để đăng nhập
use Illuminate\Http\Request;
use Auth;
use Mail;
class taskController extends Controller
{
    //
    function getTask($id_card)
    {
    	$card = cards::find($id_card);
    	echo json_encode($card->tasks);
    }
    function addTask(Request $request,$id_card)
    {
    	$time = $request->time;
    	$date = $request->date;
    	$dt = $date.' '.$time;
        $dt = Carbon::parse($dt);
    	if($request->reminder == 'at' || $request->reminder == 'None'){
    		$reminder = $dt;
    	}elseif ($request->reminder == '5m') {
    		$reminder = Carbon::parse($dt)->subMinutes(5);
    	}elseif ($request->reminder == '10m') {
    		$reminder = Carbon::parse($dt)->subMinutes(5);
    	}elseif ($request->reminder == '15m') {
    		$reminder = Carbon::parse($dt)->subMinutes(15);
    	}elseif ($request->reminder == '1h') {
    		$reminder = Carbon::parse($dt)->subHour(1);
    	}elseif ($request->reminder == '2h') {
    		$reminder = Carbon::parse($dt)->subHour(2);
    	}elseif ($request->reminder == '1d') {
    		$reminder = Carbon::parse($dt)->subDay(1);
    	}elseif ($request->reminder == '2d') {
    		$reminder = Carbon::parse($dt)->subDay(2);
    	};
    	$reminder =  (string)$reminder;
 		$card = cards::find($id_card);
 		$card->tasks=[
 			'time' => $time , 'date' => $date, 'reminder' => $reminder , 'option' => $request->reminder, 'active' => 0 ,
 		];
 		$card->save();
    }
    function changeActived(Request $request,$id_card)
    {
    	$users = cards::where('_id', $id_card)->get();

		$users->toQuery()->update([
		    'tasks.active' => $request->active,
		]);
    }
    function revokeTask($id_card)
    {
    	$card = cards::find($id_card);
 		$card->tasks= null;
 		$card->save();
    }

}
