<?php
use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\AuthController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// backend : 
Route::get('admin','UserController@getAllUser');
Route::get('logoutAdmin','AuthController@logoutAdmin');
Route::post('login_admin','AuthController@postLoginAdmin');
Route::get('login_admin',function(){
	return view("frontend.pages.login_admin");
});

Route::get('/test', function () {
	    return view('Mail.Tmail');
	});
Auth::routes(['verify' => true]);
Route::get('/', function () {
    return view('frontend.layout.welcome');
});
// Đăng ký và xử lý đăng ký:
Route::get('register.html','UserController@getRegister');
Route::post('register.html','UserController@postRegister');
// Đăng nhập và xử lý đăng nhập: 
Route::get('login.html','AuthController@getLogin')->name('login.html');
Route::post('login.html','AuthController@postLogin');
// Đăng nhập bằng gg
Route::get('auth/google','AuthController@redirectToGoogle');
Route::get('callback/google','AuthController@handleCallback');
// Đăng xuất 
Route::get('logout.html','AuthController@getLogoutUser')->name('logout.html');



// backend
Auth::routes();
// xác thực email và gửi mail 
Route::get('login_sucess.html', 'UserController@verified')->middleware('verified');
Route::get('home/{any}', 'HomeController@index')->name('home')->middleware('verified')->where('any', '.*');;
// quản lý profile cá nhân và cập nhật ---------------------------------------------------
Route::get('profile/{user_name}','UserController@getProfile')->middleware('auth','verified')->name('profile');
Route::get('getInfoUser/{id}','UserController@getInfo')->middleware('auth','verified')->name('profile');
Route::post('getInfoUser/{id}','UserController@postInfo')->middleware('auth','verified')->name('profile');
// GỬI MAIL ---------------------------------------------------------------------------------
Route::get('sendhtmlemail','MailController@html_email');
// Nhóm ----------------------------------------------------------------------------------------
Route::post('addteam','TeamController@addTeam');
Route::get('getAllTeam','TeamController@getAll');
Route::get('team/{team_name}/{id}','TeamController@getTeamView')->middleware('auth','verified')->name('team');
Route::get('getTeam/{id}','TeamController@getTeam')->middleware('auth','verified');
// Cập nhật thông tin team
Route::put('editTeam/{id}','TeamController@postEditTeam')->middleware('auth','verified');
//thành viên  nhóm
Route::get('getMemberTeam/{id}','TeamController@getMemOfTeam')->middleware('auth','verified');; // lấy 
Route::post('addMemberTeam/{id}','TeamController@addMemberTeam')->middleware('auth','verified');; // thêm
Route::get('deleteMember/{id}/{email}','TeamController@deleteMember')->middleware('auth','verified');; // thêm

// Bảng--------------------------------------------------------------------------------
// lấy backgroud:
Route::post('getOneBackgroud','BoardController@getOneBackgroud')->middleware('auth','verified');
Route::get('getBackgrouds','BoardController@getBackgrouds')->middleware('auth','verified');
Route::get('getAllBackgrouds','BoardController@getAllBackgrouds')->middleware('auth','verified');
Route::post("updateBackground/{id}",'BoardController@updateBackground');
// Thêm bảng mới:
Route::post('AddBoard','BoardController@AddBoards')->middleware('auth','verified');
// Lấy tất cả các bảng 
Route::get('getAllBoards','BoardController@getAllBoards')->middleware('auth','verified');
Route::get('b/{id}/{name}',"BoardController@showBoard")->middleware('auth','verified');
// lấy thông tin 1 bảng 
Route::get('getInfoBoard/{id}',"BoardController@getInfoBoard")->middleware('auth','verified');
// Sửa thông tin: 
Route::post('chanNameBoards/{id}','BoardController@chanNameBoards');
// Lấy thành viên trong bảng:
Route::get('getMemberBoard/{id}','BoardController@getMemBer');
Route::post('addMemberBoard/{id}','BoardController@InviteMember')->middleware('auth','verified');
Route::post('changePermissions/{id}','BoardController@changePermissions');
Route::post('removeMemberBoard/{id}','BoardController@removeMember');
Route::get('removeBoards/{id}','BoardController@removeBoards');

// List ---------------------------------------------------------------
Route::post('addList','listController@addList');
Route::get('getAllList/{id_broad}','listController@getAll');
Route::post('chanNameList/{id}','listController@chanNameList');
Route::get('removeList/{id}','listController@remove');
Route::post('updatePositionList/{id_broad}','listController@updatePosition');
// card ------------------------------------------------------------------
Route::post('addCard/{id_booars}','cardController@addCard');
Route::get('getAllCard/{id_list}','cardController@getAllCard');
Route::post('changeListOfCard/{id_cart}/{id_booars}','cardController@changeListOfCard');
Route::post('updatePositionCard/{id_booars}','cardController@updatePositionCard');
Route::post('changeNameCard/{id}/{id_booars}','cardController@changeNameCard');
Route::post('joinCard/{id}','cardController@joinCard');
Route::get('getMemberCard/{id}','cardController@getMember');
Route::get('removeCard/{id}','cardController@removeCard');
Route::post('addMemberToCard/{id}','cardController@addMember');
Route::post("changeDescription/{id}",'cardController@changeDescription');
Route::get("updateCard/{id_board}",'cardController@updateCard');

// CheckList -------------------------------------------------------------
Route::get('getCheckList/{id_card}','checkListController@getCheckList');
Route::post('addCheckList/{id_card}','checkListController@addCheckList');
Route::post('deleteCheckList/{id}/{id_card}','checkListController@deleteCheckList');
Route::post('addItem/{id_checklist}/{id_card}','checkListController@addItem');
Route::get('getItem/{id_checklist}','checkListController@getItem');
Route::post('changeActived/{id_checklist}/{id_card}','checkListController@changeActived');
Route::post('deleteItem/{id_checklist}/{id_card}','checkListController@deleteItem');
// File ------------------------------------------------------------------
Route::get('getAllFile/{id_card}','cardController@getAllFile');
Route::post('uploadFiles/{id_card}','cardController@updateFile');
Route::get('getDownload/{file_name}/{name}','cardController@getDownload');
Route::post('deleteFile/{id_card}','cardController@deleteFile');
// Task --------------------------------------------------------------------
Route::post('addTask/{id_card}','taskController@addTask');
Route::get('getTask/{id}','taskController@getTask');
Route::post('changeActivedTask/{id}','taskController@changeActived');
Route::post('revokeTask/{id}','taskController@revokeTask');
// ChatRealTime -----------------------------------------------------------
Route::get('getMess/{idBoard}','BoardController@getMess');
Route::post('sendMess/{idBoard}','BoardController@sendMess');
// Thông báo realtime -------------------------------------------------------
Route::get('getNoficationBoard/{id}','BoardController@getNofication');
Route::post('pushNoficationBoard/{id}','BoardController@pushNofication');
Route::get('getNoficationCard/{id}','cardController@getNofication');
Route::post('pushNoficationCard/{id}','cardController@pushNofication');