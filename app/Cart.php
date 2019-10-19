<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model {

    protected $fillable = ['user_id', 'product_id', 'quantity'];

    public function User() {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function Product() {
        return $this->belongsTo('App\Product', 'product_id');
    }

}
