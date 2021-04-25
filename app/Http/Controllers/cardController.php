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
        $card->comments = [];
    	$card->members = [

            ];
        $card->tasks = null;
        $card->checkList = [];
    	$card->activity = [];
    	$card->save();
        $time = Carbon::now();
        $mess = 'đã thêm thẻ '. $card->card_name .' vào lúc: '.$time;
        Broadcast(new updateBoards(Auth::user(),$mess,$id_boards))->toOthers();
    }
    function updateCard($id_boards)
    {
        Broadcast(new updateBoards(Auth::user(),'',$id_boards))->toOthers();
    }
    // thay đổi id list của thẻ
    public function changeListOfCard(Request $Request,$id,$id_boards)
    {
    	$card = cards::find($id);
    	$card->list_id = $Request->id_list;
    	$card->save();
        $time = Carbon::now();
        $mess = '';
        Broadcast(new updateBoards(Auth::user(),$mess,$id_boards))->toOthers();
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
        $mess = '';
        Broadcast(new updateBoards(Auth::user(),$mess,$id_boards))->toOthers();
    }
    //  thay đổi tên thẻ 
    public function changeNameCard(Request $Request , $id,$id_boards){
    	$card = cards::find($id);
        $name_old = $card->card_name;
    	$card->card_name = $Request->name;
    	$card->save();
        $time = Carbon::now();
        $mess = 'thẻ '. $name_old .' đã thay đổi tên thành '.$card->card_name.' trí lúc '.$time;
        if($name_old != $card->card_name){
            Broadcast(new updateBoards(Auth::user(),$mess,$id_boards))->toOthers();
        }
    }
    //  Lấy thành viên
    public function getMember($id)
    {
    	$card = cards::find($id);
    	$members = $card->members;
    	echo json_encode($members);
    }
    // Tham gia thẻ 
    public function joinCard($id)
    {
    	$card = cards::find($id);
    	$card->members =  [
          array('user_name' => Auth::user()->user_name, 'user_email' => Auth::user()->email, 'role' => 1 , 'avatar' => Auth::user()->avatar )
            ];
        $card->save();
        Broadcast(new updateCards(Auth::user(),'',$id))->toOthers();
    }
    //  thêm và xóa thành viên
    public function addMember(Request $Request, $id)
    {
    	$card = cards::where('_id',$id)->get();
    	$email  = $Request->members['user_email'];
    	$check = 0;
        //  Kiểm tra xem thành viên có trong thẻ chưa 
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
        Broadcast(new updateCards(Auth::user(),' ',$id))->toOthers();
    }
    public function changeDescription(Request $Request , $id){
    	$card = cards::find($id);
    	$card->description = $Request->description;
    	$card->save();
        Broadcast(new updateCards(Auth::user(),' ',$id),);
    }
    // Hàm up file 
    function updateFile(Request $request , $id)
    {
        if(isset($_FILES['files']))
        {
            $card = cards::find($id);
            $dtn = Carbon::now('Asia/Ho_Chi_Minh'); // Lấy thời gian
            $h = $dtn->toDateString(); // Lấy ngày 
            $dt = $h;
            $rand = rand(0,100000); // lấy 1 số random
            $file_name = $_FILES['files']['name'];// tên người dùng đưa lên  
            //tên file save vào database
            $file_save = $id.'-'.$dt.'-'.Auth::user()->_id.'-'.$rand.'-@'.$file_name; 
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
                Broadcast(new updateCards(Auth::user(),$mess,$id))->toOthers();
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
    // Lấy list commnet 
    function getALlCommnet($id){
        $card = cards::find($id);
        $comment = $card->comments;
        echo json_encode($comment);
    }
    // Thêm commnet 
    function addComment(Request $request,$id){
        $card = cards::find($id);
        $comments = $card->comments;
        $time = Carbon::now()->toDateTimeString();
        if(count($comments)){
            $endCommnet = end($comments);
            $id = $endCommnet["id"] + 1;
        }else{
            $id = 1;
        }
        $comment = [
            'id' => $id ,
            'time_spam' => $time,
            'content' => $request->content,
            'user_name' => $request->user,
            'user_avatar' => $request->avarta
        ];
        if($card->push('comments',array($comment))){
            echo "success";
        }else{
            echo "fail";
        }
        $mess = 'đã thêm comment  vào lúc: '.$time;
        Broadcast(new updateCards(Auth::user(),$mess,$id))->toOthers();
        // // echo $time;
    }
    // Chỉnh sửa comment
    function editComment(Request $request,$id){
        $card = cards::find($id);
        $comments = $card->comments;
        $id_comment = $request->id;
        $content = $request->content;
        foreach($comments as $key => $value){
            if($value["id"] == $id_comment){
               $comments[$key]['content'] = $content;
            }
        }
        cards::where('_id','=',$id)->update(['comments'=>$comments]);
    }
    function deleteComment(Request $request,$id){
        $card = cards::find($id);
        $comments = $card->comments;
        $id_comment = $request->id;
        foreach($comments as $key => $value){
            if($value["id"] == $id_comment){
               unset($comments[$key]);
            }
        }
        cards::where('_id','=',$id)->update(['comments'=>$comments]);
    }
    public function getDownload($file_name,$name)
        {
            //PDF file is stored under project/public/download/info.pdf
                $file="./public/upload/user/".$file_name;
                // Download theo tên file 
                return Response::download($file,$name);
        }
        // Xóa file 
    function deleteFile(Request $request,$id)
    {
        //  xóa file khỏi thư mục 
        $file="./public/upload/user/".$request->attachment['file_save'];
        File::delete($file);
        // Xóa file khỏi database
        cards::find($id)->pull('attachment',[$request->attachment]);
        $time = Carbon::now();
        $mess = 'đã xóa file '. $request->attachment['file_save'] .' vào lúc: '.$time;
        Broadcast(new updateCards(Auth::user(),$mess,$id))->toOthers();
    }
    //  Xóa thẻ 
    function removeCard($id){
        $C = cards::find($id);
        $time = Carbon::now();
        $mess = 'đã xóa thẻ'. $C->card_name .' vào lúc: '.$time;
        Broadcast(new updateCards(Auth::user(),$mess,$id))->toOthers();
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
        $content =  $request->content;
        if($request->content != ''){
             $nofication = array('content' => $content, 'user_name' => $user_name, 'user_id' => $user_id ,   'avatar' => $avatar);
            cards::find($id)->push('activity',[$nofication]);  
        }

      }
}
