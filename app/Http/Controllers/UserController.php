<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\nofications;
use Illuminate\Support\Facades\Auth; // dùng auth để đăng nhập
use Illuminate\Support\Str;
use DB;
use Avatar;
session_start();
class UserController extends Controller
{
    // Đăng ký
    public function getRegister(Request $request)
    {
    	# code...
      $email = $request->Email;
    	return view('frontend.pages.register',compact('email'));
    }
    public function postRegister(Request $request)
    {	# code...

      $user = User::where('email',$request->Email)->first();

      //Kiểm tra nếu đã tồn tại email rồi thì sẽ cập nhật thêm password
      if($user){
        $email = $user->email;
        $password = $user->password;
        // Cập nhật thêm password.
        if($email == $request->Email && $password == '')
        {DB::table('users')->where('email', $email)->
                update(['password' => bcrypt($request->password)]);
                return redirect('login.html')->with('thongbao','Đăng ký thành công mời bạn đăng nhập');}
      }else{ // Nếu chưa tồn tại thì sẽ thêm mới vào database
        $request->validate(
              [
                'user_name' => 'required|min:3',
                'Email' => 'required|email|unique:users,email',
                'password' => 'required|min:3|max:32',
                'passwordAgain'  => 'required|same:password'
              ],
              [
                'user_name.required' => 'Bạn chưa nhập tên người dùng',
                'user_name.min' => 'Tên người dùng phải có ít nhất 3 ký tự',
                'Email.required' => 'Bạn chưa nhập email',
                'Email.email' => 'Bạn chưa nhập đúng định dạng email',
                'Email.unique' => 'email đã tồn tại',
                'password.required' => ' Bạn chưa nhập mật khẩu',
                'password.min' => 'Mật khẩu phải có ít nhất 3 ký tự và nhiều nhất 32 ký tự',
                'password.max' => 'Mật khẩu phải có ít nhất 3 ký tự và nhiều nhất 32 ký tự',
                'passwordAgain.required' => 'Bạn chưa xác nhận lại mật khẩu',
                'passwordAgain.same' => 'Không chùng khớp với mật khẩu'
              ]
          );
          $user = new User;
          $user->user_name = $request->user_name;
          $user->email = $request->Email;
          $user->email_verified_at = null;
          // Tạo avatar cho user
          $user->avatar = Avatar::create($request->user_name)->toBase64();
          $user->password = bcrypt($request->password);
          $user->role = 0;
          $user->save();
          $nofi = new nofications;
          $nofi->user_id = $user->_id;
          $nofi->content = [];
          $nofi->save();
          return redirect('login.html')->with('thongbao','Đăng ký thành công mời bạn đăng nhập');
      }
    	
         
    }
     public function verified() // Kiểm tra đã xác thục email
    {      # code...
       return redirect('/');
    }
    public function getProfile($id_user)
    {
       return view('backend.pages.user');
    }
    public function getInfo($id)
    {
      # code...
      $user = User::find($id);
      return json_encode($user);
    }
    // Cập nhật hồ sơ cá nhân 
    public function postInfo(Request $Request,$id)
    {
      # code...
      $Request->validate(
        ['name' => 'required|min:1|max:100'],
        [
          'name.required' => 'Tên không được để trống ',
          'name.min' => 'Tên phải trên 1 ký tự',
          'name.max' => 'Tên không được quá 100 ký tự'
        ]
      );
      $user = User::find($id);
      $user->user_name = $Request->name;
      $user->avatar =  Avatar::create($Request->name)->toBase64();
      $user->desciption = $Request->discription;
      $user->save();
      return json_encode($user);
      // return redirect('profile/'.$user->user_name)->with('thongbao','Cập nhật thành công');
    }
    function getAllUser(){
      if(isset($_SESSION['user_name'])){
        $user = User::all();
      return view('backend.pages.admin',compact('user'));
    }else{
      return redirect('login_admin');
    }
  }
  function getNofications(Request $request, $id)
  {
      $nofication = nofications::where('user_id',$id)->get();
      return response()->json($nofication[0]->content);
  }
}
