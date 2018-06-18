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

Route::get('/','PagesController@root')->name('root');

Auth::routes();

Route::group(['middleware'=> 'auth'],function (){
    Route::get('/emailVerifyNotice','PagesController@emailVerifyNotice')->name('emailVerifyNotice');

    Route::get('/email_verification/verify','EmailVerificationController@verify')->name('emailVerification.verify');

    Route::get('/email_verification/send','EmailVerificationController@send')->name('emailVerification.send');

    //email verify
    Route::group(['middleware' => 'email_verified'],function (){
        Route::get('/test',function (){
            return 'Your email is verified';
        });
    });
});
