<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Effectiveness extends Model
{
    protected $guarded = [
        'id'
    ];
    public function attackingType() { 
        return $this->belongsTo(Type::class, 'attacking_type_id'); 
    }
    public function defendingType() { 
        return $this->belongsTo(Type::class, 'defending_type_id'); 
    }
}
