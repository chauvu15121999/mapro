<?php

namespace App\Http\Controllers;
use App\Models\listCart;
use App\Models\User;
use App\Models\checkList;
use App\Models\cards;
use Carbon\Carbon; // dùng auth để đăng nhập
use Illuminate\Http\Request;

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
		return response()->json($list);
	}
	public function getAll($id_board) {
		$list = listCart::where('board', $id_board)->orderBy('order')->get();
		return response()->json($list);
	}
	public function chanNameList(Request $Request, $id) {
		# code...
		$list = listCart::find($id);
		$list->list_name = $Request->name;
		$list->save();

	}
	public function remove($id){
		$list = listCart::find($id);
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
	}
}
