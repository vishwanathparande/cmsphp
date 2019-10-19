<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model {
    
    protected $fillable = ['user_id'];

    public function User() {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function OrderProduct() {
        return $this->hasMany('App\OrderProduct');
    }

}
