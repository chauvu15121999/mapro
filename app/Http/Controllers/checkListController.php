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
class checkListController extends Controller
{
    //
	function addCheckList(Request $Request,$id_card)
	{
			$Request->validate(
				['checkList_name' => 'required'],
				['checkList_name.required' => 'Bạn chưa nhập tên']
			);
		$checkList = new checkList;
		$checkList->checkList_name = $Request->checkList_name;
		$checkList->item = [];
		$checkList->card_id  = $id_card;
		$checkList->by_user = $Request->user;
		$checkList->time_duo = [];
		$checkList->save();
		cards::find($id_card)->push('checkList',$checkList->_id);
		$time = Carbon::now();
		$finduser = User::where('email', $Request->userReceived)->first();
        if($finduser == true){
			$nofication = ['user_send' => $Request->user
            ,'content' => $Request->nofication,'time' => $time->toDayDateTimeString(),
            'id_board' => $Request->id_board ];
          nofications::where('user_id',$finduser->_id)->push('content',[$nofication]);
        }
        $mess = 'đã thêm checkList '.$checkList->checkList_name.' này vào lúc'.$time;
        Broadcast(new updateCards(User::find($Request->user['_id']),$mess,$id_card))->toOthers();
		
	}

	function getCheckList($id_card)
	{
		$checkList = checkList::where('card_id',$id_card)->get();
		return response()->json($checkList);
	}
	function deleteCheckList(Request $request, $id,$id_card)
	{
		$checkList = checkList::find($id);
		$time = Carbon::now();
        $mess = 'đã xóa checkList '.$checkList->checkList_name.' này vào lúc'.$time;
		$finduser = User::where('email', $request->userReceived)->first();
        if($finduser == true){
			$nofication = ['user_send' => $request->user
            ,'content' => $request->nofication,'time' => $time->toDayDateTimeString(),
            'id_board' => $request->id_board ];
          nofications::where('user_id',$finduser->_id)->push('content',[$nofication]);
        }
        checkList::find($id)->delete();
		cards::find($id_card)->pull('checkList',$id);
		Broadcast(new updateCards(User::find($request->user['_id']),$mess,$id_card))->toOthers();
		
	}
	function addItem(Request $Request , $id_checklist,$id_card)
	{
		$Request->validate(
				['name' => 'required'],
				['name.required' => 'Bạn chưa nhập tên']
			);
		$count = checkList::where('_id',$id_checklist)->get();
		$id = count($count[0]->item) + 1; 
		$name = $Request->name ; 
		$item = array('id' => $id ,'name' => $name , 'active' => 0);
		$time = Carbon::now();
        $mess = '';
		checkList::find($id_checklist)->push('item',$item);	
        Broadcast(new updateCards(User::find($Request->user['_id']),$mess,$id_card))->toOthers();
		
	
	}
	function getItem($id_checklist)
	{
		$item = checkList::where('_id',$id_checklist)->get();
		return json_encode($item[0]->item);
	}
	function changeActived(Request $Request , $id_checklist,$id_card)
	{
		$item = checkList::where('_id',$id_checklist)->get();
		$items = $item[0]->item;
		$t = checkList::find($id_checklist);
		foreach ($items as $key => $value) {
				if($value['id'] == $Request->data['id']){
					if ($Request->active == 1) {
						$items[$key]['active'] = 0;
					}else{
						$items[$key]['active'] = 1;
					}
				}
			}
		$t->item = $items;
		$t->save();
		$time = Carbon::now();
        $mess = '';
        Broadcast(new updateCards(User::find($Request->user['_id']),$mess,$id_card))->toOthers();
		
	}
	function deleteItem(Request $Request,$id_checklist,$id_card){
	$item = checkList::where('_id',$id_checklist)->get();
		$items = $item[0]->item;
		$t = checkList::find($id_checklist);
		foreach ($items as $key => $value) {
				if($value['id'] == $Request->item['id']){
					unset($items[$key]);
				}
			}
		checkList::where('_id', $id_checklist)->update(['item' => $items]);
		$time = Carbon::now();
        $mess = '';
        Broadcast(new updateCards(User::find($Request->user['_id']),$mess,$id_card))->toOthers();
		
	}
}