<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Validator;
use App\User;
use App\Cart;
use App\Order;
use App\OrderProduct;
use Response;

//use Illuminate\Foundation\Auth\AuthenticatesUsers;

class UserController extends Controller {

    //use AuthenticatesUsers;

    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
                    'name' => 'required|string|min:2|max:45',
                    'email' => 'required|string|email|max:255|unique:users',
                    'password' => 'required|min:6|required_with:confirmPassword',
                    'confirmPassword' => 'required| min:6|same:password',
                    'mobile' => 'required|numeric|digits_between:8,20|',
                    'address' => 'required|string|max:255|',
        ]);
        if ($validator->fails()) {
            $result['status'] = 'Error';

            $errors = json_decode($validator->errors(), true);
            foreach ($errors as $value) {
                $result['messages'][] = $value[0];
            }
            return $result;
        }

        try {
            //$token = hash('sha256', Str::random(60));
            User::create([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'password' => bcrypt($request->get('password')),
                //'password' => $token,
                'address' => $request->get('address'),
                'mobile' => $request->get('mobile'),
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => 'Not able to register.'], 500);
        }
        return response()->json(['status' => 'Success'], 200);
    }

    public function login(Request $request) {
        $validator = Validator::make($request->all(), [
                    'email' => 'required|string|email|max:255',
                    'password' => 'required'
        ]);

        if ($validator->fails()) {
            $result['status'] = 'Error';

            $errors = json_decode($validator->errors(), true);
            foreach ($errors as $value) {
                $result['messages'][] = $value[0];
            }
            return $result;
        }

        $credentials = $request->only('email', 'password');
        $credentials = array_add($credentials, 'role', 'User');

        try {
            if (!$token = Auth::attempt($credentials)) {
                return response()->json(['error' => 'Invalid credentials.'], 200);
            }
        } catch (Exception $e) {
            return response()->json(['error' => 'Not able to login.'], 500);
        }

        $token = hash('sha256', Str::random(60));
        $user = User::where('email', $request->input('email'))->first();
        $user->api_token = $token;
        $user->save();

        // return response()->json(compact('token'));
        return response()->json(['status' => 'Success', 'token' => $token], 200);
    }

    public function logout(Request $request) {
        $data = $request->user();
        $user = User::where('email', $request->user()->email)->first();
        $user->api_token = '';
        $user->save();

        return response()->json(['status' => 'Success', 'message' => 'Logout Success'], 200);
    }

    public function getUserInfo(Request $request) {
        $data = $request->user();
        //$data = $request->user()->email;
        return response()->json(['status' => 'Success', 'data' => $data], 200);
    }

    public function getProducts(Request $request) {
        $query = "SELECT p.id, p.title, p.description, p.image, ROUND(p.price, 2) as price, c.name "
                . "FROM products as p join categories as c on (p.category_id = c.id) "
                . "where p.status = 'Active'";

        $result = DB::select(DB::raw($query));

        return response()->json(['status' => 'Success', 'data' => $result], 200);
    }

    public function saveCart(Request $request) {
        $quantity = Cart::where('user_id', '=', $request->user()->id)
                ->where('product_id', '=', $request->get('product_id'))
                ->get(array('id', 'quantity'));

        $quantity = $quantity->toArray();

        $quantityData = array();
        $error = 'false';
        if (!empty($quantity)) {
            $quantityData['id'] = $quantity[0]['id'];
            $quantityData['quantity'] = $quantity[0]['quantity'] + $request->get('quantity');
            $error = $quantityData['quantity'] > 5 ? 'true' : 'false';
            $quantityData['quantity'] = $quantityData['quantity'] > 5 ? 5 : $quantityData['quantity'];
        } else {
            $quantityData['quantity'] = $request->get('quantity');
        }

        $cart = new Cart;
        if (isset($quantityData['id'])) {
            $cart->exists = true;
            $cart->id = $quantityData['id'];
        }
        $cart->user_id = $request->user()->id;
        $cart->product_id = $request->get('product_id');
        $cart->quantity = $quantityData['quantity'];
        $cart->save();

        return response()->json(['status' => 'Success', 'message' => $error], 200);
    }

    public function getCart(Request $request) {

        $query = "SELECT c.id, c.product_id, c.quantity, p.title, p.description, p.image, ROUND(p.price, 2) as price, ct.name "
                . "FROM carts as c join products as p on (c.product_id = p.id) "
                . "join categories as ct on (p.category_id = ct.id) "
                . "where c.user_id = " . $request->user()->id . " order by c.created_at";

        $result = DB::select(DB::raw($query));

        return response()->json(['status' => 'Success', 'data' => $result], 200);
    }

    public function updateCart(Request $request) {

        if ($request->get('delete') == 'true') {
            $cart = Cart::find($request->get('cart_id'));
            $cart->delete();
        } else {
            $cart = new Cart;
            $cart->exists = true;
            $cart->id = $request->get('cart_id');
            $cart->quantity = $request->get('quantity');
            $cart->save();
        }

        return response()->json(['status' => 'Success'], 200);
    }

    public function placeOrder(Request $request) {

        $query = "SELECT c.id, c.product_id, c.quantity, ROUND(p.price, 2) as price "
                . "FROM carts as c join products as p on (c.product_id = p.id) "
                . "where c.user_id = " . $request->user()->id;

        $result = DB::select(DB::raw($query));

        $order = new Order;
        $order->user_id = $request->user()->id;
        $order->save();
        $orderId = $order->id;
        
        foreach ($result as $cartDetails) {

            OrderProduct::create([
                'order_id' => $orderId,
                'product_id' => $cartDetails->product_id,
                'quantity' => $cartDetails->quantity,
                'price' => $cartDetails->price,
            ]);

            $cart = Cart::find($cartDetails->id);
            $cart->delete();
        }
        return response()->json(['status' => 'Success'], 200);
    }

}
