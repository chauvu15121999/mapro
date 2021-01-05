<?php

namespace App\Http\Controllers;
use App\Models\listCart;
use App\Models\User;
use App\Models\checkList;
use App\Models\cards;
use Carbon\Carbon; // dùng auth để đăng nhập
use Illuminate\Http\Request;
use Auth;
use App\Events\UpdateData; // realtime
class listController extends Controller {
	//
	public function addList(Request $Request) {
		# code...
		$list = new listCart();
		$list->list_name = $Request->list_name;
		$list->board = $Request->id_board;
		$list->by_user = $Request->user;
		$list->order = '';
		$list->watch = 0;
		$list->save();
		$time = Carbon::now();
        $mess = 'đã thêm list '. $Request->list_name .' vào lúc: '.$time;
        Broadcast(new UpdateData(Auth::user(),$mess));
		return response()->json($list);
		// Realtime
	}
	public function getAll($id_board) {
		$list = listCart::where('board', $id_board)->orderBy('order')->get();
		return response()->json($list);
	}
	public function chanNameList(Request $Request, $id) {
		# code...
		$list = listCart::find($id);
		$name_old = $list->list_name;
		$list->list_name = $Request->name;
		$list->save();
			$time = Carbon::now();
	        $mess = 'đã thêm list '. $list->list_name .' vào lúc: '.$time;
	        Broadcast(new UpdateData(Auth::user(),$mess));
	}
	public function remove($id){
		$list = listCart::find($id);
		$time = Carbon::now();
	    $mess = 'đã xóa list '. $list->list_name .' vào lúc: '.$time;
	    Broadcast(new UpdateData(Auth::user(),$mess));
        $card = cards::where('list_id',$id)->get();
            foreach ($card as $key => $value) {
             checkList::where('card_id',$value['_id'])->delete();
            }
        cards::where('list_id',$id)->delete();
        $list->delete();
	}
	public function updatePosition(Request $Request, $id_board){
		$listCart = listCart::where('board',$id_board)->get();
		foreach($listCart as $list){
			$id = $list->id;
			foreach ($Request->lists as $l) {
				if($l['_id'] == $id){
					listCart::where('_id', $id)->update(['order' => $l['order']]);
				}
			}
		}
		$time = Carbon::now();
        $mess = 'list  đã thay đổi vị trí lúc '.$time;
        Broadcast(new UpdateData(Auth::user(),$mess));	
	}
}
