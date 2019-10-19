<?php

namespace App\Http\Controllers;

use App\Order;
use Illuminate\Http\Request;

class OrderController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $orders = Order::with('User', 'OrderProduct', 'OrderProduct.Product')->latest()->paginate(5);
        
        return view('orders.index', compact('orders'))
                        ->with('i', (request()->input('page', 1) - 1) * 5);
    }

}
