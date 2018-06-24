<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidRequestException;
use App\Notifications\EmailVerificationNotification;
use App\User;

use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;


class EmailVerificationController extends Controller
{
    //
    public function verify(Request $request)
    {
        // 从 url 中获取 'email'  'token' 两个参数
        $email=$request->input('email');
        $token=$request->input('token');

        //如果有一个为空则说明不是一个合法链接，抛出异常。
        if(!$email||!$token){
            throw new InvalidRequestException('验证连接不正确');
        }

        //从缓存中读取数据，我们把从url中获取的‘token’与缓存中的数值作对比
        //如果缓存中不存在或者返回的值与url中的‘token’ 不一致，就抛出异常
        if($token !=Cache::get('email_verification_'.$email)){
            throw new InvalidRequestException('验证连接不正确或者已过期');
        }

        //根据邮箱从数据库中获取对应的用户
        if(!$user =User::where('email',$email)->first()){
            throw new InvalidRequestException('用户不存在');
        }

        //将指定的key从缓存中删除，由于已经完成了验证，这个缓存就无用了
        Cache::forget('email_verification_'.$email);

        //最关键的，要把对应用户的‘email_verified'改成’true‘
        DB::table('users')
            ->where('email',$email)
            ->update(['email_verified'=>true]);

        //最后告诉用户邮箱验证成功
        return view('pages.success',['msg'=> '邮箱验证成功']);
    }

    public function send(Request $request){

        $user=$request->user();
        //判断用户已激活
        if($user->email_verified){
            throw new InvalidRequestException('你已经验证过邮箱了');
        }

        //调用notify()方法来发送我们定义好的通知类
        $user->notify(new EmailVerificationNotification());

        return view('pages.success',['msg' => '邮件发送成功']);

    }
}
