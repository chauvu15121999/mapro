<?php

namespace App\Http\Controllers;
use App\Models\listCart;
use App\Models\User;
use App\Models\checkList;
use App\Models\cards;
use App\Models\nofications;
use Carbon\Carbon; // dùng sử lý thời gian 
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Response; 
use Illuminate\Support\Facades\File; // dùng để chỉnh sửa file 
use App\Events\updateCards;
use App\Events\updateBoards; // realtime
use Hamcrest\Arrays\IsArray;

class cardController extends Controller
{
    //
    public function getAllCard($id_list)
    {
    	$cards = cards::where('list_id',$id_list)->orderBy('order')->get();
    	return response()->json($cards);
    }
    function getCard($id){
        return response()->json(cards::find($id));
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
        $card->storage = false;
    	if($Request->position == 0){
			$card->order = 1;
    	}else{
    		$card->order = '';
    	}
    	$card->attachment = [];
        $card->comments = [];
    	$card->assign = null;
        $card->tasks = null;
        $card->checkList = [];
    	$card->activity = [];
    	$card->save();
        $time = Carbon::now();
        $mess = 'đã thêm thẻ '. $card->card_name .' vào lúc: '.$time;
        Broadcast(new updateBoards(User::find($Request->user['_id']),$mess,$id_boards))->toOthers();
    }
    function updateCard(Request $Request,$id_boards)
    {
        Broadcast(new updateBoards(User::find($Request->user['_id']),'',$id_boards))->toOthers();
    }
    // thay đổi id list của thẻ
    public function changeListOfCard(Request $Request,$id,$id_boards)
    {
    	$card = cards::find($id);
    	$card->list_id = $Request->id_list;
    	$card->save();
        $time = Carbon::now();
        $mess = '';
        Broadcast(new updateBoards(User::find($Request->user['_id']),$mess,$id_boards))->toOthers();
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
        $user  = User::find($Request->user['_id']);
        Broadcast(new updateBoards($user,$mess,$id_boards))->toOthers();
    }
    //  thay đổi tên thẻ 
    public function changeNameCard(Request $Request , $id,$id_boards){
    	$card = cards::find($id);
        $name_old = $card->card_name;
    	$card->card_name = $Request->name;
    	$card->save();
        $time = Carbon::now();
        $mess = 'thẻ '. $name_old .' đã thay đổi tên thành '.$card->card_name.' trí lúc '.$time;
        $user  = User::find($Request->user['_id']);
        if($name_old != $card->card_name){
            Broadcast(new updateBoards($user,$mess,$id_boards))->toOthers();
        }
    }
    public function addMember(Request $Request, $id)
    {
        $time = Carbon::now();
        $card = cards::find($id);
        $card->assign = $Request->members;
        $finduser = User::where('email', $Request->members['user_email'])->first();
        if($finduser == true){
          $nofication = ['user_send' => $Request->user 
          ,'content' => 'assigned you to card ' . $card->card_name ,'time' => $time->toDayDateTimeString(), 
          'id_board' => $Request->id_board ];
          nofications::where('user_id',$finduser->_id)->push('content',[$nofication]);
        }
        $card->save();
        Broadcast(new updateCards(User::find($Request->user['_id']),' ',$id))->toOthers();
        
    }
    public function changeDescription(Request $Request , $id){
    	$card = cards::find($id);
    	$card->description = $Request->description;
        $finduser = User::where('email', $Request->userReceived)->first();
        $time = Carbon::now();
        if($finduser == true){
          $nofication = ['user_send' => $Request->user
            ,'content' => $Request->nofication,'time' => $time->toDayDateTimeString(),
            'id_board' => $Request->id_board ];
          nofications::where('user_id',$finduser->_id)->push('content',[$nofication]);
        }
    	$card->save();
        Broadcast(new updateCards(User::find($Request->user['_id']),' ',$id))->toOthers();
        
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
           $file_save = $id.'-'.$dt.'-'.$request->user.'-'.$rand.'-@'.$file_name; 
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
                $time = Carbon::now('Asia/Ho_Chi_Minh');
                $user = User::where('_id',$request->user)->first();
                $mess = 'đã cập nhật file '. $file_name .' vào lúc: '.$time;
                $finduser = User::where('email', $request->userReceived)->first();
                if($finduser == true){
                  $nofication = ['user_send' => (object)$user->toArray()
                    ,'content' => $request->nofication,'time' => $time->toDayDateTimeString() , 
                    'id_board' => $request->id_board ];
                  nofications::where('user_id',$finduser->_id)->push('content',[$nofication]);
                }
                Broadcast(new updateCards(User::find($request->user),$mess,$id))->toOthers();
                
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
            'user_name' => $request->user['user_name'],
            'user_avatar' => $request->avarta
        ];
        $card->push('comments',array($comment));
        $time = Carbon::now('Asia/Ho_Chi_Minh');
        $mess = 'đã thêm comment  vào lúc: '.$time;
        $finduser = User::where('email', $request->userReceived)->first();
        if($finduser == true){
          $nofication = ['user_send' => $request->user
            ,'content' => $request->nofication,'time' => $time->toDayDateTimeString(),
            'id_board' => $request->id_board ];
          nofications::where('user_id',$finduser->_id)->push('content',[$nofication]);
        }
        Broadcast(new updateCards(User::find($request->user['_id']),'',$id))->toOthers();
        
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
        $time = Carbon::now('Asia/Ho_Chi_Minh');
        $finduser = User::where('email', $request->userReceived)->first();
        if($finduser == true){
            $nofication = ['user_send' => $request->user
            ,'content' => $request->nofication,'time' => $time->toDayDateTimeString(),
            'id_board' => $request->id_board ];
          nofications::where('user_id',$finduser->_id)->push('content',[$nofication]);
        }
        cards::where('_id','=',$id)->update(['comments'=>$comments]);
        Broadcast(new updateCards(User::find($request->user['_id']),'',$id))->toOthers();
        
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
        $time = Carbon::now('Asia/Ho_Chi_Minh');
        $finduser = User::where('email', $request->userReceived)->first();
        if($finduser == true){
            $nofication = ['user_send' => $request->user
            ,'content' => $request->nofication,'time' => $time->toDayDateTimeString(),
            'id_board' => $request->id_board ];
          nofications::where('user_id',$finduser->_id)->push('content',[$nofication]);
        }
        cards::where('_id','=',$id)->update(['comments'=>$comments]);
        Broadcast(new updateCards(User::find($request->user['_id']),'',$id))->toOthers();
        
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
        $time = Carbon::now('Asia/Ho_Chi_Minh');
        $finduser = User::where('email', $request->userReceived)->first();
        if($finduser == true){
            $nofication = ['user_send' => $request->user
            ,'content' => $request->nofication,'time' => $time->toDayDateTimeString(),
            'id_board' => $request->id_board ];
          nofications::where('user_id',$finduser->_id)->push('content',[$nofication]);
        }
        Broadcast(new updateCards(User::find($request->user['_id']),$mess,$id))->toOthers();
        
    }
    //  Xóa thẻ 
    function removeCard(Request $Request , $id){
        $C = cards::find($id);
        $time = Carbon::now();
        $mess = 'đã xóa thẻ'. $C->card_name .' vào lúc: '.$time;
        Broadcast(new updateCards(User::find($Request->user['_id']),$mess,$id))->toOthers();
        $card = cards::where('_id',$id)->get();
        foreach ($card as $key => $value) {
             checkList::where('card_id',$value['_id'])->delete();
        };
        $C->delete();  
    }
    public function stogareCard(Request $Request, $id){
        $card = cards::find($id);
        $card->storage == true ? $card->storage = false : $card->storage = true;
        $card->save();
        Broadcast(new updateBoards(User::find($Request->user['_id']),'',$id))->toOthers();
      }
    // ---------------------------------------------------------------------------------
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
