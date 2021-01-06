<?php

namespace App\Http\Controllers;
use App\Models\listCart;
use App\Models\User;
use App\Models\checkList;
use App\Models\cards;
use Carbon\Carbon; // dùng sử lý thời gian 
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Response; 
use Illuminate\Support\Facades\File; // dùng để chỉnh sửa file 
use App\Events\updateCards;
use App\Events\updateBoards; // realtime
class cardController extends Controller
{
    //
    public function getAllCard($id_list)
    {
    	$cards = cards::where('list_id',$id_list)->orderBy('order')->get();
    	return response()->json($cards);
    }
    public function addCard(Request $Request , $id_boards)
    {
    	$Request->validate(
    		['cart_name' => 'required'],
    		['required' => 'bạn phải nhập đủ thông tin']
    	);
    	$card = new cards;
    	$card->card_name = $Request->cart_name;
    	$card->list_id = $Request->list_id;
    	$card->broad_id = $Request->broad;
    	$card->by_user = $Request->user;
    	if($Request->position == 0){
			$card->order = 1;
    	}else{
    		$card->order = '';
    	}
    	$card->attachment = [];
    	$card->members = [

            ];
        $card->tasks = null;
        $card->checkList = [];
    	$card->activity = [];
    	$card->save();
        $time = Carbon::now();
        $mess = 'đã thêm thẻ '. $card->card_name .' vào lúc: '.$time;
        Broadcast(new updateBoards(Auth::user(),$mess,$id_boards));
    }
    function updateCard($id_boards)
    {
        Broadcast(new updateBoards(Auth::user(),'',$id_boards));
    }
    // thay đổi id list của thẻ
    public function changeListOfCard(Request $Request,$id,$id_boards)
    {
    	$card = cards::find($id);
    	$card->list_id = $Request->id_list;
    	$card->save();
        $time = Carbon::now();
        $mess = 'thẻ '. $card->card_name .' đã thay đổi vị trí lúc '.$time;
        Broadcast(new updateBoards(Auth::user(),$mess,$id_boards));
    }
    // thay đổi vị trí thẻ
    public function updatePositionCard(Request $Request,$id_boards)
    {
    	$allCard = cards::all();
		foreach($allCard as $card){
			$id = $card->id;
			foreach ($Request->cards as $c) {
				if($c['_id'] == $id){
					cards::where('_id', $id)->update(['order' => $c['order']]);
				}
			}
		}
        $time = Carbon::now();
        $mess = 'thẻ '. $card->card_name .' đã thay đổi vị trí lúc '.$time;
        Broadcast(new updateBoards(Auth::user(),$mess,$id_boards));
    }
    public function changeNameCard(Request $Request , $id,$id_boards){
    	$card = cards::find($id);
        $name_old = $card->card_name;
    	$card->card_name = $Request->name;
    	$card->save();
        $time = Carbon::now();
        $mess = 'thẻ '. $name_old .' đã thay đổi tên thành '.$card->card_name.' trí lúc '.$time;
        if($name_old != $card->card_name){
            Broadcast(new updateBoards(Auth::user(),$mess,$id_boards));
        }
    }
    public function getMember($id)
    {
    	$card = cards::find($id);
    	$members = $card->members;
    	echo json_encode($members);
    }
    public function joinCard($id)
    {
    	$card = cards::find($id);
    	$card->members =  [
          array('user_name' => Auth::user()->user_name, 'user_email' => Auth::user()->email, 'role' => 1 , 'avatar' => Auth::user()->avatar )
            ];
        $card->save();
        Broadcast(new updateCards(Auth::user(),'',$id));
    }
    public function addMember(Request $Request, $id)
    {
    	$card = cards::where('_id',$id)->get();
    	$email  = $Request->members['user_email'];
    	$check = 0;
    	for ($i = 0 ; $i < count($card[0]->members) ; $i ++){
    		if($email == $card[0]->members[$i]["user_email"]){
    			$check = 1;
    		}
    	}
    	if($check == 0){
    		cards::find($id)->push('members',[$Request->members]);
    	}else{
    		cards::find($id)->pull('members',[$Request->members]);
    	}
        Broadcast(new updateCards(Auth::user(),' ',$id));
    }
    public function changeDescription(Request $Request , $id){
    	$card = cards::find($id);
    	$card->description = $Request->description;
    	$card->save();
        Broadcast(new updateCards(Auth::user(),' ',$id),);
    }
    function updateFile(Request $request , $id)
    {
        if(isset($_FILES['files']))
        {
            $card = cards::find($id);
            $dtn = Carbon::now('Asia/Ho_Chi_Minh');
            $h = $dtn->toDateString(); 
            $dt = $h;
            $rand = rand(0,100000);
            $file_name = $_FILES['files']['name'];// tên 
            $file_save = $id.'-'.$dt.'-'.Auth::user()->_id.'-'.$rand.'-@'.$file_name; //save
           $path = 'public/upload/user/'.$file_save; // Đường dẫn
           $tmp = $_FILES['files']['tmp_name']; 
           $size_file = $_FILES['files']['size'];
           $type_file = $_FILES['files']['type'];
           if($size_file <= 10240000){
            if(move_uploaded_file($tmp,'public/upload/user/'.$file_save)){
                $file = array(
                    'file_name' => $file_name,
                    'file_save' => $file_save , 
                    'path' => $path ,
                    'type' =>  $type_file,
                    'time' => $dt);
                $card->push('attachment', array($file));
                $time = Carbon::now();
                $mess = 'đã cập nhật file '. $file_name .' vào lúc: '.$time;
                Broadcast(new updateCards(Auth::user(),$mess,$id));
             }else{
                echo "Tải tập tin thất bại";
             }
           }else{
            echo 'file upload phải nhỏ hơn 10MB';
           } 
        }  
    }
    function getAllFile($id)
    {
        $card = cards::find($id);
        $attachment = $card->attachment;
        echo json_encode($attachment);
    }
        public function getDownload($file_name,$name)
        {
            //PDF file is stored under project/public/download/info.pdf
                $file="./public/upload/user/".$file_name;
                return Response::download($file,$name);
        }
    function deleteFile(Request $request,$id)
    {
        $file="./public/upload/user/".$request->attachment['file_save'];
        File::delete($file);
        cards::find($id)->pull('attachment',[$request->attachment]);
        $time = Carbon::now();
        $mess = 'đã xóa file '. $request->attachment['file_save'] .' vào lúc: '.$time;
        Broadcast(new updateCards(Auth::user(),$mess,$id));
    }
    function removeCard($id){
        $C = cards::find($id);
        $time = Carbon::now();
        $mess = 'đã xóa thẻ'. $C->card_name .' vào lúc: '.$time;
        Broadcast(new updateCards(Auth::user(),$mess,$id));
        $card = cards::where('_id',$id)->get();
        foreach ($card as $key => $value) {
             checkList::where('card_id',$value['_id'])->delete();
        };
        $C->delete();
        
    }
    // ---------------------------------------------------------------------------------
    function getNofication($id)
      {
          $board = boards::find($id);
          return response()->json($board->activity);
      }
    function pushNofication(Request $request,$id)
      {
        $user_name = $request->user['user_name'];
        $user_id = $request->user['_id'];
        $avatar = $request->user['avatar'];
        $content =  $user_name.' '.$request->content;
        if($request->content != ''){
             $nofication = array('content' => $content, 'user_name' => $user_name, 'user_id' => $user_id ,   'avatar' => $avatar);
            cards::find($id)->push('activity',[$nofication]);  
        }

      }
}
