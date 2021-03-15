<?php

use Illuminate\Support\Facades\Route;

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
Route::get('user_manager','CMS\UserManagerCMSController@index');
Route::get('agenda','CMS\AgendaCMSController@index');
Route::get('album','CMS\AlbumCMSController@index');
Route::get('banner','CMS\BannerCMSController@index');
Route::get('berita_alumni','CMS\BeritaCMSController@index');
Route::get('category','CMS\CategoryCMSController@index');
Route::get('cdc','CMS\CDCCMSController@index');
Route::get('gallery','CMS\GalleryCMSController@index');
Route::get('jurusan','CMS\JurusanCMSController@index');
Route::get('pengumuman','CMS\PengumumanCMSController@index');
Route::get('prodi','CMS\ProdiCMSController@index');
Route::get('user_alumni','CMS\UserAlumniCMSController@index');
Route::get('user_alumni_conf','CMS\UserAlumniCMSController@confirmation');
Route::get('struktur','CMS\StrukturCMSController@index');
Route::get('strukturLevel','CMS\StrukturLevelCMSController@index');

Route::get('login','CMS\LoginController@index');
Route::post('login_check','CMS\LoginController@login');

Route::get('sign_out','CMS\LoginController@logout');
Route::get('/', function () {
    return redirect('/user_manager');
});
