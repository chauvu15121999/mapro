<?php

namespace App\Http\Controllers;
use App\Models\listCart;
use App\Models\User;
use App\Models\checkList;
use App\Models\cards;
use Carbon\Carbon; // dùng auth để đăng nhập
use Illuminate\Http\Request;
use Auth;
use App\Events\updateBoards; // realtime
class listController extends Controller {
	// thêm list 
	public function addList(Request $Request) {
		# code...
		$list = new listCart();
		$list->list_name = $Request->list_name;
		$list->board = $Request->id_board;
		$list->by_user = $Request->user['_id'];
		$list->order = '';
		$list->watch = 0;
		$list->storage = false;
		$list->save();
		$time = Carbon::now();
        $mess = 'đã thêm list '. $Request->list_name .' vào lúc: '.$time;
        Broadcast(new updateBoards(User::find($Request->user['_id']),$mess,$Request->id_board))->toOthers();
		return response()->json($list);
		// Realtime
	}
	// Lấy toàn bộ
	public function getAll($id_board) {
		// sắp xếp theo order.
		$list = listCart::where('board', $id_board)->orderBy('order')->get();
		return response()->json($list);
	}
	//  Thay đổi tên
	public function chanNameList(Request $Request, $id) {
		# code...
		$list = listCart::find($id);
		$name_old = $list->list_name;
		$list->list_name = $Request->name;
		$list->save();
			$time = Carbon::now();
	        $mess = 'đã thêm list '. $list->list_name .' vào lúc: '.$time;
	        Broadcast(new updateBoards(User::find($Request->user['_id']),$mess,$list->board))->toOthers();
	}
	//  Xóa 
	public function remove(Request $Request ,$id){
		$list = listCart::find($id);
		$time = Carbon::now();
	    $mess = 'đã xóa list '. $list->list_name .' vào lúc: '.$time;
	    Broadcast(new updateBoards(User::find($Request->user['_id']),$mess,$list->board))->toOthers();
        $card = cards::where('list_id',$id)->get();
            foreach ($card as $key => $value) {
             checkList::where('card_id',$value['_id'])->delete();
            }
        cards::where('list_id',$id)->delete();
        $list->delete();
	}
	public function stogareList(Request $Request, $id){
        $list = listCart::find($id);
        $list->storage == true ? $list->storage = false : $list->storage = true;
        $list->save();
        Broadcast(new updateBoards(User::find($Request->user['_id']),'',$id))->toOthers();
      }
	//  Cập nhật vi trí 
	public function updatePosition(Request $Request, $id_board){
		$listCart = listCart::where('board',$id_board)->get();
		// Lấy toàn bộ 
		foreach($listCart as $list){
			$id = $list->id;
			foreach ($Request->lists as $l) {
				// Kiểm tra cần cập nhật list nào 
				if($l['_id'] == $id){
					listCart::where('_id', $id)->update(['order' => $l['order']]);
				}
			}
		}
		$time = Carbon::now();
        $mess = '';
        Broadcast(new updateBoards(User::find($Request->user['_id']),$mess,$id_board))->toOthers();	
	}
}
