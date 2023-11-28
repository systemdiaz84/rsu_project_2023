<?php

namespace App\Models\admin;

use App\Models\admin\Familyphoto;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Family extends Model
{
    use HasFactory;

    protected $guarded=[];

    /*public function species(){
        return $this->hasMany(Specie::class);
    }

    public function photos(){
        return $this->hasMany(Familyphoto::class);
    }*/

}
