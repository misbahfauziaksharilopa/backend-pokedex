<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    protected $guarded = [
        'id'
    ];
    public function type1()
    {
        return $this->belongsTo(Type::class, 'type_1_id');
    }
    public function type2()
    {
        return $this->belongsTo(Type::class, 'type_2_id');
    }
}
