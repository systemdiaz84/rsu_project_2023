<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Familyphoto extends Model
{
    use HasFactory;

    protected $fillable=['url','family_id'];

    public function family(){
        return $this->belongsTo(Family::class);
    }
}
