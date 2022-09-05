<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Libraries\EnjoymintUtils;

class Cart extends BaseController
{
  public function __construct() 
	{
		helper(['jwt']);

		$this->data = [];
		$this->role = session()->get('role');
    $this->isLoggedIn = session()->get('isLoggedIn');
		$this->guid = session()->get('guid');
    $this->product_model = model('ProductModel');
    $this->image_model = model('ImageModel');
    $this->cart_model = model('CartModel');
    $this->checkout_model = model('CheckoutModel');
    $this->user_model = model('UserModel');

		$this->data['user_jwt'] = ($this->guid != '') ? getSignedJWTForUser($this->guid) : '';		
    $this->data['tax_rate'] = 1.35;  // 35%
	}

  private function _cookie_to_session($data)
  {

  }

  public function index()
  {
    $user_cart = '';
    $cart_raw = [];
    $cookie_cart = [];
    $cart_products = [];

    // Check first if cookie cart is set
    if(isset($_COOKIE['cart_data'])) {
      $user_cart = $_COOKIE['cart_data'];

      $cookie_cart = json_decode($user_cart);
    }

    // Check if user is logged in
    if($this->isLoggedIn == 1) {
      
      $db_cart = $this->_fetch_cart_items();
      // print_r($db_cart);die();

      // If there are products from db, they should show up on the page
      if(!empty($db_cart)) {
        $cart_raw = $db_cart;
      }
		}
    else {
      $cart_raw = $cookie_cart;
    }

    // Loop through all the products and fetch all the product info
    foreach($cart_raw as $product) {

      // print_r($product);

      // Get products from the database using pid
      $product_data = $this->product_model->getProductData($product->pid);
      
      // initialize images
      $images = [];
      $imageIds = [];

      // Fetch product images
      if($product_data->images) {
        $imageIds = explode(',',$product_data->images);
        $images = $this->image_model->whereIn('id', $imageIds)->get()->getResult();
      }

      // For new cookie array
      // $new_cookie_cart[] = [
      //   'pid' => $product->pid,
      //   'qty' => $product->qty,
      // ];

      // Output array
      $cart_products[] = [
        'pid' => $product->pid,
        'qty' => $product->qty,
        'product_data' => $product_data,
        'images' => $images,
      ];
    }

    // create a new cookie
    // setrawcookie('cart_data', '[{"pid":"85","qty":"8"},{"pid":"88","qty":"2"},{"pid":"87","qty":"1"}]', 1);

    // print_r(json_encode($new_cookie_cart));die();

    $this->data['cart_products'] = $cart_products;
    $this->data['guid'] = $this->guid;

    return view('cart/cart_page', $this->data);
  }

  public function checkout()
  {
    // If not logged-in redirect back to cart
    if($this->isLoggedIn != 1) {
      return redirect()->to('/cart');
		}

    $db_cart = $this->_fetch_cart_items();

    if(!empty($db_cart)) {
      $cart_raw = $db_cart;
    }

    // Loop through all the products and fetch all the product info
    foreach($cart_raw as $product) {

      // print_r($product);

      // Get products from the database using pid
      $product_data = $this->product_model->getProductData($product->pid);
      
      // initialize images
      $images = [];
      $imageIds = [];

      // Fetch product images
      if($product_data->images) {
        $imageIds = explode(',',$product_data->images);
        $images = $this->image_model->whereIn('id', $imageIds)->get()->getResult();
      }

      // For new cookie array
      // $new_cookie_cart[] = [
      //   'pid' => $product->pid,
      //   'qty' => $product->qty,
      // ];

      // Output array
      $cart_products[] = [
        'pid' => $product->pid,
        'qty' => $product->qty,
        'product_data' => $product_data,
        'images' => $images,
      ];
    }

    $this->data['cart_products'] = $cart_products;
    $this->data['guid'] = $this->guid;

    $enjoymint_utils = new EnjoymintUtils();

    $this->data['checkout_token'] = $enjoymint_utils->generateRandomString(20);

    return view('cart/checkout', $this->data);
  }

  public function place_order()
  {
    $data = $this->request->getPost();

    // print_r($data); die();

    $user = $this->user_model->getUserByGuid($data['guid']);
    $token = $data['cart_key'];

    $db_cart = $this->_fetch_cart_items();

    $subtotal = 0;

    // Loop through all the products and fetch all the product info
    foreach($db_cart as $product) {

      // Get products from the database using pid
      $product_data = $this->product_model->getProductData($product->pid);

      $subtotal += $product_data->price * $product->qty;

      // Order products array
      $cart_products[] = [
        'order_id' => 0, // initialized only here.  This will be updated below after creating the order.
        'product_id' => $product->pid,
        'product_name' => $product_data->name,
        'qty' => $product->qty,
        'unit_price' => $product_data->price,  // The price that the user bought the product.
        'is_sale' => 0,  // For now explicitly set to 0 because we don't have sale function as of the moment.  Will update this soon.
        'regular_price' => $product_data->price,  // The regular (normal) price of the product.
        'total' => $product_data->price * $product->qty,
      ];
    }

    // print_r($cart_products); die();

    $tax_cost = $subtotal * ($this->data['tax_rate'] - 1);
    $total_cost = $subtotal * $this->data['tax_rate'];

    $order_data = [
      'order_key' => $data['cart_key'],
      'customer_id' => $user['id'],
      'first_name' => $user['first_name'],
      'last_name' => $user['last_name'],
      'address' => $data['apt_no'] ." ". $data['street'] .", ". $data['city'] .", ". $data['state'] ." ". $data['zipcode'],
      'subtotal' => $subtotal,
      'tax' => $tax_cost,
      'total' => $total_cost,
      'payment_method' => $data['payment_method'],
    ];

    $order = $this->checkout_model->insert($order_data);

    print_r($order); die();
  }

  private function _fetch_cart_items()
  {
    // Fetch user data using guid
    $user_data = $this->user_model->getUserByGuid($this->guid);

    // print_r($user_data);die();

    // Fetch cart items from db
    return $this->cart_model->where('uid', $user_data['id'])->get()->getResult();
  }
}