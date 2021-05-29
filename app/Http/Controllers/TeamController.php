<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Team;
use App\Models\User;
use App\Models\typeteam;
use App\Models\boards;
use Illuminate\Support\Facades\Auth; // dùng auth để đăng nhập
use Illuminate\Support\Str;
use DB;
use Avatar;
use App\Http\Controllers\MailController;
use Illuminate\Validation\Rule;
class TeamController extends Controller
{
    // thêm 1 team mới 
    public function addTeam(Request $Request)
    {
    	# code...
    	 $Request->validate(
              [
                'name' => 'required|min:3|max:300',
                'typeteam' => 'required',
                'desciption'  => 'required'
              ],
              [
                'name.required' => 'Bạn chưa nhập tên nhóm',
                'name.min' => 'Tên nhóm dùng phải có ít nhất 3 ký tự',
                'name.max' => 'Tên nhóm chỉ được 300 ký tự',
                'typeteam.required' => 'Bạn chưa nhập loại team',
                'desciption.required' => ' Bạn chưa nhập mô tả',
              ]
          );
    	$team = new Team();
    	$team->team_name = $Request->name;
    	$team->type_team = $Request->typeteam;
    	$team->desciption = $Request->desciption;
    	$team->by_user = Auth::user()->id;
      // Nếu mà user chưa có avarta thì tạo avarta cho user 
      $avatar = Auth::user()->avatar;
    	$team->members = [
          array('user_email' => Auth::user()->email, 'role' => 1 , 'avatar' => $avatar )
            ];
    	$team->status = 0;
    	$team->save();
      // return redirect('login.html');
      return redirect('team/'.$team->team_name.'/'.$team->_id);
    }
    // Lấy tất cả team của user 
    public function getAll(Request $Request)
    {
      # code...
      $team = Team::where('by_user',$Request->user['_id'])->orWhere('members.user_email',$Request->user['email'])->get();
        echo json_encode($team);
    }
    public function getTeamView($name , $id)
    {      # code...
      $team = Team::find($id);
      $teamtype = typeteam::all();
      // echo $teamtype[0]['_id'];
      return view('backend.pages.team',compact('team','teamtype'));
    }
    public function getTeam($id)
    {
      # code...
      $team = Team::find($id);
      return json_encode($team);
    }
    // Cập nhật thông tin team
    public function postEditTeam(Request $Request,$id)
    {
      # code...\
      $team = Team::find($id);
      $Request->validate(
              [
                'name' => 'required|min:3|max:300',
                'typeteam' => 'required',
                'desciption'  => 'required'
              ],
              [
                'name.required' => 'Bạn chưa nhập tên nhóm',
                'name.min' => 'Tên nhóm dùng phải có ít nhất 3 ký tự',
                'name.max' => 'Tên nhóm chỉ được 300 ký tự',
                'typeteam.required' => 'Bạn chưa nhập loại team',
                'desciption.required' => ' Bạn chưa nhập mô tả',
              ]
          );
      $team->team_name = $Request->name;
      $team->type_team = $Request->typeteam;
      $team->desciption = $Request->desciption;
      $team->save();
      return json_encode($team);
    }
    // Mời thêm thành viên vào team
    public function addMemberTeam(Request $Request, $id)
    {
      # code...
        $Request->validate(
              ['members' => 'required|email'],
              [
                'members.required' => 'Bạn chưa nhập email',
                'members.email' => 'Vui lòng nhập đúng định dạng mail examl@gmail.com',
              ] 
           );
        $Team = Team::find($id);
        $team = Team::where('_id',$id)->get();
        $member = '';
        // Kiểm tra người dùng đã được mời hay chưa ?

        foreach($team[0]->members as $value){
          if($value['user_email'] == trim($Request->members)){
            $member = '';
          }
        }
        if($member != ''){
            $finduser = User::where('email', $Request->members)->first();
                if($finduser == true){
                  $members = array('user_email' => $Request->members, 'role' => 0 , 'avatar' => $finduser->avatar);
                }else{
                  $avatar = Avatar::create($Request->members)->toBase64();
                  $members = array('user_email' => $Request->members, 'role' => 0 , 'avatar' => $avatar);
                }
            $team = Team::find($id)->push('members',[$members]);
            // thêm member vào csdl
            $user = Auth::user()->email; // lấy user hiện tại 
            $link = url()->previous(); // lấy url của team
            $result = new MailController(); // gọi tới hàm gửi  lấy địa chỉ url hiện tại mail
            $result = $result->team_mail($Request->members,$link,$user);
            return $this->getMemOfTeam($id);
        }else{
          return response()->json([
              'error' => "Người này đã được mời"],
             422); 
        }
    }
    // Lấy thành viên trong team
    public function getMemOfTeam($id)
    {
        $team = Team::where('_id',$id)->get();
        $members = $team[0]->members;
        return json_encode($team[0]->members);
    }
    // Xóa thành viên
     public function deleteMember($id,$email)
    {
      $Team = Team::find($id);
      $team = Team::where('_id',$id)->get();
      $members = $team[0]->members;
      foreach ($members as $key => $value) {
      //   # code...
          if($value['user_email'] ==  $email )
          {
              unset($members[$key]);    
          }
      }
      $Team->members = $members;
      $Team->save();
    }
}
