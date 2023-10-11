<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Procedure extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'date',
        'description',
        'procedure_type_id',
        'tree_id',
        'responsible_id',
        'user_id'
    ];
}
