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
class NotificationRequestCreateHome extends Notification
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
                'title' => 'Solicitud de creación de hogar', 
                'message' => 'El ciudadano '.$this->data->username.' ha solicitado la creación del hogar "'.$this->data->homename.'" con código "'.$this->data->codehome.'" en la dirección "'.$this->data->direction.'".', 
                'deep_link' => 'frg-request-new-home',
                'timestamp' => now()->toDateTimeString(),
                'data' => json_encode($this->data->data),
            ])
            ->setAndroid(
                AndroidConfig::create()
                    ->setPriority(AndroidMessagePriority::HIGH())
            );
    }
}
