<?php

namespace App\Http\Controllers;
use App\Models\listCart;
use App\Models\User;
use App\Models\cards;
use App\Models\checkList;
use App\Models\nofications;
use Carbon\Carbon; // dùng auth để đăng nhập
use Illuminate\Http\Request;
use Auth;
use App\Events\updateCards;
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
        //  tính toán thời gian 
        // Trước bao nhiêu phút
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
		 $finduser = User::where('email', $request->userReceived)->first();
        if($finduser == true){
          $nofication = ['user_send' => $request->user
            ,'content' => $request->nofication,'time' => $time,
            'id_board' => $request->id_board ];
          nofications::where('user_id',$finduser->_id)->push('content',[$nofication]);
        }
 		$card->save();
        $time = Carbon::now();
        $mess = 'đã thêm task '.$card->card_name.' này vào lúc'.$time;
        Broadcast(new updateCards(User::find($request->user['_id']),$mess,$id_card))->toOthers();
        
    }
    function changeActived(Request $request,$id_card)
    {
    	$users = cards::where('_id', $id_card)->get();

		$users->toQuery()->update([
		    'tasks.active' => $request->active,
		]);
        $time = Carbon::now();
        $mess = '';
		$finduser = User::where('email', $request->userReceived)->first();
        if($finduser == true){
          $nofication = ['user_send' => $request->user
          ,'content' => $request->nofication,'time' => $time->toDayDateTimeString(),
          'id_board' => $request->id_board ];
          nofications::where('user_id',$finduser->_id)->push('content',[$nofication]);
        }
        Broadcast(new updateCards(User::find($request->user['_id']),$mess,$id_card))->toOthers();
        
    }
    function revokeTask(Request $Request,$id_card)
    {
    	$card = cards::find($id_card);
 		$card->tasks= null;
 		$card->save();
        $time = Carbon::now();
        $mess = 'đã xóa task '.$card->card_name.' này vào lúc'.$time;
		$finduser = User::where('email', $Request->userReceived)->first();
        if($finduser == true){
          $nofication = ['user_send' => $Request->user
          ,'content' => $Request->nofication,'time' => $time->toDayDateTimeString(),
          'id_board' => $Request->id_board ];
          nofications::where('user_id',$finduser->_id)->push('content',[$nofication]);
        }
        Broadcast(new updateCards(User::find($Request->user['_id']),$mess,$id_card))->toOthers();
        
    }

}
