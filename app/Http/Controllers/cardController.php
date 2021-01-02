<?php

namespace App\Http\Controllers;
use App\Models\listCart;
use App\Models\User;
use App\Models\checkList;
use App\Models\cards;
use Carbon\Carbon; // dùng auth để đăng nhập
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Response; 
use Illuminate\Support\Facades\File; 
class cardController extends Controller
{
    //
    public function getAllCard($id_list)
    {
    	$cards = cards::where('list_id',$id_list)->orderBy('order')->get();
    	return response()->json($cards);
    }
    public function addCard(Request $Request)
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
    }
    public function changeListOfCard(Request $Request,$id)
    {
    	$card = cards::find($id);
    	$card->list_id = $Request->id_list;
    	$card->save();
    }
    public function updatePositionCard(Request $Request)
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
    }
    public function changeNameCard(Request $Request , $id){
    	$card = cards::find($id);
    	$card->card_name = $Request->name;
    	$card->save();
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
    }
    public function changeDescription(Request $Request , $id){
    	$card = cards::find($id);
    	$card->description = $Request->description;
    	$card->save();
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
        
    }
    function removeCard($id){
        $C = cards::find($id);
        $card = cards::where('_id',$id)->get();
        foreach ($card as $key => $value) {
             checkList::where('card_id',$value['_id'])->delete();
        };
        $C->delete();
    }
}
