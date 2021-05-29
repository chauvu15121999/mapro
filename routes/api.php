<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
// Team
Route::post('getAllTeam','TeamController@getAll');
// Boards --------------------------------------------------------
//background 
// lấy backgroud:
Route::post('getOneBackgroud','BoardController@getOneBackgroud');
Route::get('getBackgrouds','BoardController@getBackgrouds');
Route::get('getAllBackgrouds','BoardController@getAllBackgrouds');
Route::post("updateBackground/{id}",'BoardController@updateBackground');
// board
Route::post('AddBoard','BoardController@AddBoards');
Route::post('getAllBoards','BoardController@getAllBoards');
Route::get('getInfoBoard/{id}',"BoardController@getInfoBoard");
Route::post('stogareBoards/{id}','BoardController@stogareBoards');
Route::get('removeBoards/{id}','BoardController@removeBoards');
// Lấy thành viên trong bảng:
Route::get('getMemberBoard/{id}','BoardController@getMemBer');
Route::post('addMemberBoard/{id}','BoardController@InviteMember');
Route::post('changePermissions/{id}','BoardController@changePermissions');
Route::post('removeMemberBoard/{id}','BoardController@removeMember');
// List ---------------------------------------------------------------
Route::post('addList','listController@addList');
Route::get('getAllList/{id_broad}','listController@getAll');
Route::post('chanNameList/{id}','listController@chanNameList');
Route::post('stogareList/{id}','listController@stogareList');
Route::post('updatePositionList/{id_broad}','listController@updatePosition');
// Card--------------------------------------------------------------------
Route::get('getAllCard/{id_list}','cardController@getAllCard');
Route::get('card/{id_card}','cardController@getCard');
Route::post('addCard/{id_booars}','cardController@addCard');
Route::post('changeListOfCard/{id_cart}/{id_booars}','cardController@changeListOfCard');
Route::post('updatePositionCard/{id_booars}','cardController@updatePositionCard');
Route::post('changeNameCard/{id}/{id_booars}','cardController@changeNameCard');
Route::post('joinCard/{id}','cardController@joinCard');
Route::get('getMemberCard/{id}','cardController@getMember');
Route::post('removeCard/{id}','cardController@removeCard');
Route::post('stogareCard/{id}','cardController@stogareCard');
Route::post('addMemberToCard/{id}','cardController@addMember');
Route::post("changeDescription/{id}",'cardController@changeDescription');
Route::post("updateCard/{id_board}",'cardController@updateCard');
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
//----------------------------------------------------------------------
Route::get('getComment/{id_card}','cardController@getALlCommnet');
Route::post('addCommnet/{id_card}','cardController@addComment');
Route::post('editComment/{id_card}','cardController@editComment');
Route::post('deleteComment/{id_card}','cardController@deleteComment');
// ChatRealTime -----------------------------------------------------------
Route::get('getMess/{idBoard}','BoardController@getMess');
Route::post('sendMess/{idBoard}','BoardController@sendMess');
// Nofication -----------------------------------------------------------------
Route::get('getNofications/{userID}','UserController@getNofications');