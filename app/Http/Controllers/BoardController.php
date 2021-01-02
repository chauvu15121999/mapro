<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Team;
use App\Models\Boards;
use App\Models\listCart;
use App\Models\cards;
use App\Models\User;
use App\Models\background;
use App\Models\checkList;
use Illuminate\Support\Facades\Auth; // dùng auth để đăng nhập
use Illuminate\Support\Str;
use DB;
use Avatar;
use App\Http\Controllers\MailController;
use Carbon\Carbon;
use App\Events\MessageSent;
class BoardController extends Controller
{
       public function showBoard($id)
        {
          # code...
          $board =  Boards::find($id);
          return view('backend.pages.board',compact('board'));
        }
//Background ---------------------------------------------------------
      public function getOneBackgroud(Request $Request)
      {
        # code...
        // lấy tên hình từ csdl
          $backgroud = background::where("_id",$Request->id_background)->get();
        // Tạo url cho hình 
          $backgroud = './public/backend/assets/images/backgroud-boards/'.$backgroud[0]->background_name;
        // url về 
          return response()->json($backgroud);  
      }
	   public function getBackgrouds(Request $Request)
    	{
    		# code...
          $backgroud = background::offset(0)->limit(8)->get();
          return response()->json($backgroud);	
    	}
      public function getAllBackgrouds($value='')
      {
        # code...
        $backgroud = background::all();
        return response()->json($backgroud);  
      }
      public function updateBackground(Request $Request,$id)
      {
        # code...
        // lấy hình từ database 
          $backgroud = background::where("_id",$Request->id_background)->get();
        // Tạo đường dẫn từ tên hình 
          $url = './public/backend/assets/images/backgroud-boards/'.$backgroud[0]->background_name;
        // Cập nhật lại vào csdl
          boards::where("_id",$id)->update(
              array('background.id' => $Request->id_background ,
                    'background.url' => $url,
                    )
          );
      }
// Thêm bảng ------------------------------------------------------------------------------
      public function AddBoards(Request $Request)
      {
      	# code...
      	$board = new Boards();
      	$board->board_name = $Request->name_boards;
      	$board->team = $Request->team;
        $board->background = [
            'id' => $Request->id_background,
            'url' => $Request->url
        ];
      	$board->activity = [
          Auth::user()->user_name.' đã tạo bảng vào lúc '.Carbon::now()
        ];
      	$board->by_user = Auth::user()->id;
        if(Auth::user()->avatar != null){
          $avatar = Auth::user()->avatar;
        }else{
          $avatar = '';
        }
      	$board->members = [
          array('user_name' => Auth::user()->user_name, 'user_email' => Auth::user()->email, 'role' => 1 , 'avatar' => $avatar )
            ];
      	$board->status = $Request->status;
        $board->message = [];
      	$board->save();
        return redirect('b/'.$board->id.'/'.$board->board_name);
      }
      public function getAllBoards()
      {
        # code...
        $user = Auth::user()->id;
        $boards = Boards::where('by_user',$user)->orWhere('members.user_email',Auth::user()->email)->get();
        return response()->json($boards);
      }
      public function getInfoBoard($id)
      {
        $board =  Boards::where('_id',$id)->get();
        return response()->json($board[0]);
      }
      public function chanNameBoards(Request $Request,$id)
      {
        # code...
         $board = Boards::find($id);
         $board->board_name = $Request->name;
         $board->save();
      }
      public function InviteMember(Request $Request,$id)
      {
        # code...
        $Request->validate(
              ['members' => 'required|email'],
              [
                'members.required' => 'Bạn chưa nhập email',
                'members.email' => 'Vui lòng nhập đúng định dạng mail examl@gmail.com',
              ] 
           );
        $board = Boards::where('_id',$id)->get();
        $member = 0;
        // Kiểm tra xem thành viên đã được mời hay chưa 
        for($i = 0 ; $i < count($board[0]->members) ; $i++ ){
          // nếu đã dc mời 
          if($Request->members == $board[0]->members[$i]["user_email"] ){
              $member = 1;
          }
        }
        // nếu chưa .... 
        if($member == 0){
          $finduser = User::where('email', $Request->members)->first();
                if($finduser == true){
                  $members = array('user_name' => $finduser->user_name ,'user_email' => $Request->members, 'role' => 0 , 'avatar' => $finduser->avatar);
                }else{
                  $avatar = Avatar::create($Request->members)->toBase64();
                  $members = array('user_name' => '' , 'user_email' => $Request->members, 'role' => 0 , 'avatar' => $avatar);
                }
            $board = Boards::find($id)->push('members',[$members]);
            // thêm member vào csdl
            $user = Auth::user()->email; // lấy user hiện tại 
            $link = url()->previous(); //
            $result = new MailController(); // gọi tới hàm gửi  lấy địa chỉ url hiện tại mail
            $result = $result->board_mail($Request->members,$link,$user);
            return response()->json($this->getMemBer($id));
          }else{
            $err = array('errors' => array("người này đã tồn tại"));
               return response()->json($err,
             500);
          }
      }
      // lấy thông tin thành viên 
      public function getMemBer($id)
      {
        # code...
        $board =  Boards::where('_id',$id)->get();
        $dataUser = array();   
        foreach ($board[0]->members as $key =>   $value) {
            array_push($dataUser,$board[0]->members[$key]);
        }
        return response()->json($dataUser); 
      }
      // đổi quyền thành viên: 
      public function changePermissions(Request $Request,$id)
      {
        # code...
        $permission = $Request->permission;
        $email = $Request->email;
        $board =  Boards::where('_id',$id)->get();
        foreach ($board[0]->members as $key => $value) {
          # code...
            if($value['user_email'] == $email){
              if($permission == 0){
                Boards::where('_id', $id)->update(array('members.'.$key.'.role' => $permission));
              }else{
                Boards::where('_id', $id)->update(array('members.'.$key.'.role' => $permission));
              }   
            }
        }
      }
      // removeMember
      public function removeMember(Request $Request,$id)
      {
        # code...
        $members = $Request->Infomember;
        $b = Boards::find($id);
        $board =  Boards::where('_id',$id)->get();
        $member = $board[0]->members;
        foreach ($member as $key => $value) {
          # code...
          if($value['user_email'] == $members['user_email']){
                unset($member[$key]);
          }
        }
        $b->members = $member;
        $b->save();
        // Xóa member trong bảng
        $listCart = listCart::where('board',$id)->get();
          foreach($listCart as $key => $value){
            $card = cards::where('list_id',$value['_id'])->get();
            foreach ($card as $keyc => $valuec) {
             cards::find($valuec['_id'])->pull('members',[$members]);
            }
          }
      }
      public function removeBoards(Request $Request,$id){
          $board = boards::find($id);     
          $listCart = listCart::where('board',$id)->get();
          foreach($listCart as $key => $value){
            $card = cards::where('list_id',$value['_id'])->get();
            foreach ($card as $keyc => $valuec) {
             checkList::where('card_id',$valuec['_id'])->delete();
            }
            cards::where('list_id',$value['_id'])->delete();
          }
          listCart::where('board',$id)->delete();
          $board->delete();
          return redirect('home/'.Auth::user()->user_name.'/dashboard.html');

      }
      // -----------------------------------------------------------------------
      public function getMess($id)
      {
          $board = boards::find($id);
          return response()->json($board->message);
      }
      function sendMess(Request $request, $id)
      {
        if($request->message != '')
        {
          $user = Auth::user();
          $mess = $request->message;
          $user_name = $request->user['user_name'];
          $user_id = $request->user['_id'];
          $avatar = $request->user['avatar'];
          $time = Carbon::now('Asia/Ho_Chi_Minh');
          $chat = array('message' => $mess, 'user_name' => $user_name, 'user_id' => $user_id ,   'avatar' => $avatar, 'time' => (string)$time);
          Boards::find($id)->push('message',[$chat]);
          Broadcast(new MessageSent($user,$mess))->toOthers();
        }
      }
}
