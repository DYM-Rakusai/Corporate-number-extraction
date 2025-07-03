<?php

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

Route::group(['middleware' => 'auth.very_basic'], function () {});

// 応募者アンケート表示
Route::get('/worksheet', 'View\WorksheetController@index')->name('worksheet');
// 応募者アンケート合否判定
Route::post('/check-worksheet-answer', 'Api\CheckWorksheetController@store')->name('checkWorksheetAnswer');
// 応募者アンケート取得
Route::post('/get-worksheet-answer', 'Api\GetWorksheetController@store')->name('getWorksheetAnswer');

// 会社ページ
Route::get('/company-status-page', 'Auth\View\CompanyStatusPageController@index')->name('worksheet');
// 会社アンケート回答取得
Route::post('/get-company-answer', 'Auth\Api\Company\GetCompanyAnswerController@store')->name('get-company-answer');

// 応募者ページ
Route::get('/cs-status-page', 'View\CsStatusPageController@index')->name('worksheet');


Auth::routes();
Route::get('/', function () {
    return view('auth/login');
});
Route::get('/home', 'HomeController@index')->name('home');


// 管理画面
// アカウント一覧
Route::get('user-list-page', 'Auth\View\UserListPageController@index')->name('userListPage');
// アカウント登録
Route::get('/manual-add-user-page', 'Auth\View\ManualAddUserPageController@index')->name('manualAddUserPage');
Route::post('/manual-add-user', 'Auth\Api\User\ManualAddUserController@store')->name('manualAddUser');
// アカウント編集
Route::get('/edit-user-page', 'Auth\View\EditUserPageController@index')->name('editUserPage');
Route::post('/edit-user-data', 'Auth\Api\User\EditUserController@store')->name('editUserData');


// 応募者一覧
Route::get('/cs-list-page', 'Auth\View\CsListPageController@index')->name('csListPage');
Route::get('/cs-detail-page', 'Auth\View\CsDetailPageController@index')->name('csDetailPage');
// 面接日時変更
Route::post('/change-schedule', 'Auth\Api\Schedule\ChangeScheduleController@store')->name('changeSchedule');

// 応募者手動登録
Route::get('/manual-add-cs-page', 'Auth\View\ManualAddCsPageController@index')->name('manualAddCsPage');
Route::post('/manual-add-cs', 'Auth\Api\Consumer\ManualAddCsController@store')->name('manualAddCs');
// 応募者情報編集
Route::get('/edit-cs-page', 'Auth\View\EditCsPageController@index')->name('editCsPage');
Route::post('/edit-cs-data', 'Auth\Api\Consumer\EditConsumerController@store')->name('editCsData');


Route::get('/set-schedule-page', 'Auth\View\SetSchedulePageController@index')->name('setSchedulePage');
Route::post('/update-schedule', 'Auth\Api\Schedule\UpdateScheduleController@store')->name('updateSchedule');

Route::post('/get-schedule-data', 'Auth\Api\Schedule\GetScheduleDataController@store')->name('getScheduleData');

// job-mapping-page
Route::get('/job-mapping-page', 'Auth\View\JobMappingPageController@index')->name('JobMappingPage');
Route::post('/update-job-keywords', 'Auth\Api\Job\EditJobKeywordController@store')->name('UpdateJobKeywords');



#ブラックリスト
Route::get('/black-list-page', 'Auth\View\BlackListPageController@index')->name('blackListPage');
Route::post('/add-black-list', 'Auth\Api\BlackList\AddBlackListController@store')->name('addBlackList');
Route::post('/delete-black-list', 'Auth\Api\BlackList\DeleteBlackListController@store')->name('deleteBlackList');

#ブラックスト（応募者ページ）
Route::post('/add-black-list-for-csDetail', 'Auth\Api\BlackList\CsDetailForAddBlackListController@store')->name('addBlackListforCsDetail');
Route::post('/delete-black-list-for-csDetail', 'Auth\Api\BlackList\CsDetailForDeleteBlackListController@store')->name('deleteBlackListforCsDetail');


// 応募者情報スプシダウンロード
Route::post('/download-cs-data', 'Auth\Api\Download\csDataDownloadController@store')->name('download-cs-data');
