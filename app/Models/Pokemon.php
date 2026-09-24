<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pokemon extends Model
{
    protected $guarded = [
        'id'
    ];
    public function type1() { 
        return $this->belongsTo(Type::class, 'type_1_id'); 
    }
    public function type2() { 
        return $this->belongsTo(Type::class, 'type_2_id'); 
    }

    public function form1() { 
        return $this->belongsTo(Form::class, 'form_1_id'); 
    }
    public function form2() { 
        return $this->belongsTo(Form::class, 'form_2_id'); 
    }
    public function form3() { 
        return $this->belongsTo(Form::class, 'form_3_id'); 
    }
}
