<?php

namespace App\Models\admin;

use App\Models\NotifyToken;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $guarded = [];

    protected $table = 'users';

    public function notifyTokens()
    {
        return $this->hasMany(NotifyToken::class);
    }
    public function routeNotificationForFcm($notification)
    {
        return $this->notifyTokens()->where('is_active', 1)->pluck('token')->toArray();
    }
    public function hasActiveNotificationTokens()
    {
        return $this->notifyTokens()->where('is_active', 1)->exists();
    }
}
