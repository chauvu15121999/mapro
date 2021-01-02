<?php

namespace App\Http\Controllers;   
use App\Http\Controllers\Controller;
use Socialite;
use Auth;
use DB;
use Avatar;
use Exception;
use App\Models\User;
use App\Models\admin;
use Carbon\Carbon;
use Illuminate\Http\Request;
session_start();
class AuthController extends Controller
{
    //
    // Đăng nhập 
    public function getLogin()
    {
        # code...
        return view('frontend.pages.login');
    }
    // Xử lí login 
    public function postLogin(Request $request)
    {
        # code...
        $request->validate(
              [
                'Email' => 'required|email',
                'password' => 'required',
              ],
              [
                'Email.required' => 'Bạn chưa nhập email',
                'Email.email' => 'Bạn chưa nhập đúng định dạng email',
                'password.required' => ' Bạn chưa nhập mật khẩu',
              ]
          );
        if(Auth::attempt(['email'=>$request->Email,'password'=>$request->password]))
          {
            return redirect('home/'.Auth::user()->user_name.'/dashboard.html');
          }
          else
          {
            return redirect('login.html')->with('thongbao','Đăng nhập không thành công');
        }
    }
    // login  bằng tài khoản gg 
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }
       
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // kiểm tra 
    public function handleCallback()
    {	
        try {
            $user = Socialite::driver('google')->user();
            $finduser = User::where('google_id', $user->id)->first(); // kiểm tra xem có đăng nhập gg lần nào chưa
            $findemail = User::where('email',$user->email)->first(); // lấy email trong databse 
            if($finduser == true && $findemail == true){ // nếu như đã có tài khoản       
                Auth::login($finduser);
                return redirect('login_sucess.html');
      
            }elseif($findemail == true && $finduser == null) { // có csdl rồi nhưng chưa có gg_id
            	# code...
             DB::table('users')->where('email', $user->email)->
					   update(['google_id' => $user->id]);
				    return redirect('login_sucess.html');
            } // chưa có tài khoản thì sẽ thêm vào database
            else{
                $newUser = User::create([
                    'user_name' => $user->name,
                    'email' => $user->email,
                    'email_verified_at' => Carbon::now('Asia/Ho_Chi_Minh')->toDateTimeString(),
                    'google_id'=> $user->id,
                    'Activity' =>  [],
                    'avatar' => Avatar::create($user->name)->toBase64(),
                    'social_type'=> 'google',
                    'password_gg' => encrypt('my-google'),
                ]);  
                Auth::login($newUser);
                return redirect('login_sucess.html');
            }
     
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
    public function getLogoutUser()
     {
         Auth::logout();
         return redirect('login.html')->with('thongbao','bạn đã đăng xuất');;
     }
     function postLoginAdmin(Request $request)
     {
        $request->validate(
              [
                'user_name' => 'required',
                'password' => 'required',
              ],
              [
                'user_name.required' => 'Bạn chưa nhập email',
                'password.required' => ' Bạn chưa nhập mật khẩu',
              ]
          );
        $user = admin::where('user_name',$request->user_name)->where('password',$request->password)->first();
        if($user != ''){
          $_SESSION['user_name'] = $user['user_name'];
         return redirect('admin');
        }else{
         return redirect('login_admin');
        }
     }
     function logoutAdmin(){
        session_destroy();
        return redirect('login_admin');
     }
}

