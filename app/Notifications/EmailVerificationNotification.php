<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;

use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class EmailVerificationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        //使用 laravel 内置的str 类生成随机字符串的函数
        $token= Str::random(16);

        //往缓存中写入这个随机字符串，有效时间为1800秒
        Cache::set('email_verification_'.$notifiable->email,$token,1800);
        $url=route('emailVerification.verify',['email' => $notifiable->email,'token'=>$token]);

        return (new MailMessage)
                    ->greeting($notifiable->name.'你好：')
                    ->subject('注册成功，请验证你的邮箱')
                    ->action('验证', $url)
                    ->line('请点击下方链接验证你的邮箱');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
