<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model {

    protected $fillable = ['title', 'category_id', 'description', 'image', 'price', 'status'];

    public function OrderProduct() {
        return $this->hasMany('App\OrderProduct');
    }

    public function Category() {
        return $this->belongsTo('App\Category', 'category_id');
    }

}
