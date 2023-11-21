<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\AndroidConfig;
use NotificationChannels\Fcm\Resources\AndroidMessagePriority;
use NotificationChannels\Fcm\Resources\AndroidNotification;

// php artisan make:test NotificationTest
// php artisan test tests/Feature/NotificationTest.php
class TestNotification extends Notification
{
    use Queueable;

    public function via($notifiable)
    {
        return [FcmChannel::class];
    }
    public function toFcm($notifiable)
    {
        return FcmMessage::create()
            ->setData(['test' => 'value'])
            ->setNotification(\NotificationChannels\Fcm\Resources\Notification::create()
                ->setTitle('Test Notification, probando')
                ->setBody('This is a test notification')
                ->setImage('http://www.usat.edu.pe/web/wp-content/uploads/2015/07/logousat.jpg')
            )
            ->setAndroid(
                AndroidConfig::create()
                    // ->setTtl(3600 * 1000) // 1 hour in milliseconds
                    ->setPriority(AndroidMessagePriority::HIGH())
                    ->setNotification(
                        AndroidNotification::create()
                            ->setSound('default')
                            ->setChannelId('test-channel')
                    )
            )

            ;
    }
}
