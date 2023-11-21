<?php

namespace Tests\Feature;

use App\Models\admin\User;
use App\Notifications\TestNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class NotificationTest extends TestCase
{

    public function testNotificationIsSent()
    {
        $user =  User::find(1);

        $user->notify(new TestNotification);
        

        $this->assertTrue(true);
    }
}
