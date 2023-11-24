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
class NotificationRequestAccessHome extends Notification
{
    use Queueable;

    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function via($notifiable)
    {
        return [FcmChannel::class];
    }
    public function toFcm($notifiable)
    {
        return FcmMessage::create()
            ->setData([
                'title' => 'Acceso a hogar', 
                'message' => 'El ciudadano '.$this->data->username.' ha solicitado acceso al hogar "'.$this->data->homename.'" de código '.$this->data->codehome.'.', 
                'deep_link' => 'http://arborizacion.rsu.usat.edu.pe/home/request/'.$this->data->codehome,
                'timestamp' => now()->toDateTimeString(),
                'data' => json_encode($this->data->data),
            ])
            ->setAndroid(
                AndroidConfig::create()
                    ->setPriority(AndroidMessagePriority::HIGH())
            );
    }
}
