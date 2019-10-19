<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model {

    protected $fillable = ['order_id', 'product_id', 'quantity', 'price'];

    public function Order() {
        return $this->belongsTo('App\Order', 'order_id');
    }

    public function Product() {
        return $this->belongsTo('App\Product', 'product_id');
    }

}
