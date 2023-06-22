<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specie extends Model
{
    use HasFactory;

    protected $fillable=['name','description','family_id'];

    public function family(){
        return $this->belongsTo(Family::class);
    }
}
