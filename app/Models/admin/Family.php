<?php

namespace App\Models\Admin;

use App\Models\Admin\Familyphoto;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Family extends Model
{
    use HasFactory;

    protected $fillable=['name','scientific','description'];

    /*public function species(){
        return $this->hasMany(Specie::class);
    }

    public function photos(){
        return $this->hasMany(Familyphoto::class);
    }*/

}
