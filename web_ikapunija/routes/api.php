<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

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
//Main Web
Route::get('agenda','API\AgendaController@getList');
Route::get('agenda/{id}','API\AgendaController@getDetail');

Route::get('album','API\AlbumController@getList');
Route::get('albumDetail/{id}','API\AlbumController@getDetailAlbum');

Route::get('album/{id}','API\GalleryController@getListGallery');
Route::get('albumImageDetail/{id}','API\GalleryController@getDetailImage');

Route::get('banner','API\BannerController@getList');
Route::get('banner/{id}','API\BannerController@getDetail');

Route::get('berita','API\BeritaController@getList');
Route::get('berita/{id}','API\BeritaController@getDetail');

Route::get('category','API\CategoryController@getList');
Route::get('category/{id}','API\CategoryController@getDetail');

Route::get('jurusan','API\JurusanController@getList');
Route::get('jurusan/{id}','API\JurusanController@getDetail');

Route::get('loker','API\CDCController@getList');
Route::get('loker/{id}','API\CDCController@getDetail');

Route::get('prodi/{jurusan}','API\ProdiController@getList');
Route::get('prodi/{jurusan}/{id}','API\ProdiController@getDetail');

Route::get('pengumuman','API\PengumumanController@getList');
Route::get('pengumuman/{id}','API\PengumumanController@getDetail');

Route::get('profesi','API\ProfesiController@getList');

Route::get('profile','API\UserAlumniController@profile');
Route::put('updateProfile','API\UserAlumniController@update_user');
Route::post('register','API\UserAlumniController@register');
Route::post('contact','API\KontakController@sendKontak');

Route::get('struktur','API\StrukturController@getList');
Route::get('struktur/{id}','API\StrukturController@getDetail');

//Verify & Login
Route::post('login','API\UserController@login');
Route::put('logout','API\UserController@logout');
Route::get('verifikasi_akun/{email}/{token}','API\UserController@verify_account');

//Forgot Password
Route::post('forgotPassword','API\PasswordController@forgotPassword');
Route::post('verifyForgotPassword','API\PasswordController@verifyForgotPassword');
Route::post('updatePassword','API\PasswordController@updatePassword');

//Admin Album Gallery
Route::post('addAlbum','API\AlbumController@addAlbum');
Route::put('editAlbum','API\AlbumController@editAlbum');
Route::delete('delAlbum','API\AlbumController@delAlbum');

//Admin Agenda
Route::post('addAgenda','API\AgendaController@addAgenda');
Route::put('editAgenda','API\AgendaController@editAgenda');
Route::delete('delAgenda','API\AgendaController@delAgenda');

//Admin Banner
Route::post('addBanner','API\BannerController@addBanner');
Route::put('editBanner','API\BannerController@editBanner');
Route::delete('delBanner','API\BannerController@delBanner');

//Admin Berita
Route::post('addBerita','API\BeritaController@addBerita');
Route::put('editBerita','API\BeritaController@editBerita');
Route::delete('delBerita','API\BeritaController@delBerita');

//Admin Category
Route::post('addCategory','API\CategoryController@addCategory');
Route::put('editCategory','API\CategoryController@editCategory');
Route::delete('delCategory','API\CategoryController@delCategory');

//Admin CDC
Route::post('addCDC','API\CDCController@addCDC');
Route::put('editCDC','API\CDCController@editCDC');
Route::delete('delCDC','API\CDCController@delCDC');

//Admin Gallery
Route::post('addGallery','API\GalleryController@addGallery');
Route::put('editGallery','API\GalleryController@editGallery');
Route::delete('delGallery','API\GalleryController@delGallery');

//Admin Jurusan
Route::post('addJurusan','API\JurusanController@addJurusan');
Route::put('editJurusan','API\JurusanController@editJurusan');
Route::delete('delJurusan','API\JurusanController@delJurusan');

//Admin Prodi
Route::post('addProdi','API\ProdiController@addProdi');
Route::put('editProdi','API\ProdiController@editProdi');
Route::delete('delProdi','API\ProdiController@delProdi');

//Admin Pengumuman
Route::post('addPengumuman','API\PengumumanController@addPengumuman');
Route::put('editPengumuman','API\PengumumanController@editPengumuman');
Route::delete('delPengumuman','API\PengumumanController@delPengumuman');

//Admin Struktur
Route::post('addStruktur','API\StrukturController@addStruktur');
Route::put('editStruktur','API\StrukturController@editStruktur');
Route::delete('delStruktur','API\StrukturController@delStruktur');

//Admin Struktur Level
Route::get('strukturLevel','API\StrukturLevelController@getList');
Route::get('strukturLevel/{id}','API\StrukturLevelController@getDetail');
Route::post('addStrukturLevel','API\StrukturLevelController@addStrukturLevel');
Route::put('editStrukturLevel','API\StrukturLevelController@editStrukturLevel');
Route::delete('delStrukturLevel','API\StrukturLevelController@delStrukturLevel');

//Admin UserManager
Route::post('addUserManager','API\UserManagerController@addUserAdmin');
Route::get('getUserManager/{id}','API\UserManagerController@getDetail');
Route::put('editUserManager','API\UserManagerController@editUserAdmin');
Route::delete('delUserManager','API\UserManagerController@delUserAdmin');

//Admin User Alumni
Route::get('userAlumni','API\UserAlumniController@getList');
Route::get('userAlumni/{id}','API\UserAlumniController@getDetail');
Route::put('approveAlumni','API\UserAlumniController@approveAlumni');
Route::delete('deleteDeclineAlumni','API\UserAlumniController@declineAlumni');

// apps API //

Route::group(['prefix' => 'mobile'], function () {

    Route::group(['prefix' => 'auth'], function () {

        Route::post('register', 'API\Mobile\AuthController@register')->name('mobile.auth.register');
        Route::post('login', 'API\Mobile\AuthController@login')->name('mobile.auth.login');
        Route::post('register', 'API\Mobile\AuthController@register')->name('mobile.auth.register');
        Route::post('forgotPassword','API\Mobile\AuthController@forgotPassword')->name('mobile.auth.forgot');
        Route::post('verifyForgotPassword','API\Mobile\AuthController@verifyForgotPassword')->name('mobile.auth.verify-forgot');
        Route::post('updatePassword','API\Mobile\AuthController@updatePassword')->name('mobile.auth.forgot-update-password');
    });

    Route::group(['middleware' => 'jwt.verify'], function () {
        Route::group(['prefix' => 'profile'], function () {
            Route::get('detail','API\Mobile\ProfileController@detail');
        });
        Route::group(['prefix' => 'alumniPost'], function () {
            Route::post('list','API\Mobile\AlumniPostController@list');
            Route::post('save','API\Mobile\AlumniPostController@save');
            Route::get('detail/{id}','API\Mobile\AlumniPostController@detail');
            Route::delete('delete','API\Mobile\AlumniPostController@delete');
            Route::post('like','API\Mobile\AlumniPostController@like');
            Route::post('comment','API\Mobile\AlumniPostController@comment');
            Route::post('reaction','API\Mobile\AlumniPostController@reaction');
        });
    });
});

// public API

Route::group(['prefix' => 'public'], function () {

    Route::post('getInterest','API\PublicController@interestList')->name('public.interest.list');
    Route::post('getProfession','API\PublicController@professionList')->name('public.profession.list');
    Route::post('getFaculty','API\PublicController@facultyList')->name('public.faculty.list');
    Route::post('getProgramStudy','API\PublicController@programStudyList')->name('public.program-study.list');
    Route::post('getLastEducation','API\PublicController@lastEducationList')->name('public.last-education.list');

    Route::post('getCountry','API\PublicController@countryList')->name('public.country.list');
    Route::post('getProvince','API\PublicController@provinceList')->name('public.province.list');
    Route::post('getCity','API\PublicController@cityList')->name('public.city.list');

});

