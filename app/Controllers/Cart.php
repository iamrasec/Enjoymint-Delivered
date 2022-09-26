<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Libraries\EnjoymintUtils;

class Cart extends BaseController
{
  public function __construct() 
	{
		helper(['jwt', 'cookie']);

		$this->data = [];
		$this->role = session()->get('role');
    $this->isLoggedIn = session()->get('isLoggedIn');
		$this->guid = session()->get('guid');
    $this->uid = session()->get('id');
    $this->product_model = model('ProductModel');
    $this->image_model = model('ImageModel');
    $this->cart_model = model('CartModel');
    $this->checkout_model = model('CheckoutModel');
    $this->order_products_model = model('OrderProductsModel');
    $this->user_model = model('UserModel');

		$this->data['user_jwt'] = ($this->guid != '') ? getSignedJWTForUser($this->guid) : '';		
    $this->data['tax_rate'] = 1.35;  // 35%
	}

  private function _cookie_to_db($cookie_cart)
  {
    foreach($cookie_cart as $cart_product) {
      $toSave = [
        'uid' => $this->uid,
        'pid' => $cart_product->pid,
        'qty' => $cart_product->qty,
      ];

      $this->cart_model->insert($toSave);
    }

    return true;
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
      else {
        if(!empty($cookie_cart)) {
          $this->_cookie_to_db($cookie_cart);
          $cart_raw = $this->_fetch_cart_items();
        }
      }
		}
    else {
      $cart_raw = $cookie_cart;
    }

    // Loop through all the products and fetch all the product info
    foreach($cart_raw as $product) {
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

    // echo "<pre>".print_r($data)."</pre>"; die();

    $user = $this->user_model->getUserByGuid($data['guid']);
    // $token = $data['cart_key'];

    // Initialize order record to be saved in the database
    $order_data = [
      'order_key' => $data['cart_key'],
      'customer_id' => $user['id'],
      'first_name' => $user['first_name'],
      'last_name' => $user['last_name'],
      'address' => $data['apt_no'] ." ". $data['street'] .", ". $data['city'] .", ". $data['state'] ." ". $data['zipcode'],
      'payment_method' => $data['payment_method'],
      'order_notes' => $data['order_notes'],
    ];

    // Insert initial order record and grab the order id
    $order_id = $this->checkout_model->insert($order_data);

    // Fetch products in cart
    $db_cart = $this->_fetch_cart_items();

    // Initialize subtotal cost
    $subtotal = 0;

    // Loop through all the products and fetch all the product info
    foreach($db_cart as $product) {

      // Get products from the database using pid
      $product_data = $this->product_model->getProductData($product->pid);

      $new_stock_qty = array('stocks' => '(stocks - '.$product->qty.')');

      $update_stocks = $this->product_model->where('id', $product->pid)->set('stocks', '(stocks - '.$product->qty.')', false)->update();

      // Compute for subtotal cost
      $subtotal += $product_data->price * $product->qty;

      // Order products array
      $cart_products[] = [
        'order_id' => $order_id,
        'product_id' => $product->pid,
        'product_name' => $product_data->name,
        'qty' => $product->qty,
        'unit_price' => $product_data->price,  // The price that the user bought the product.
        'is_sale' => 0,  // For now explicitly set to 0 because we don't have sale function as of the moment.  Will update this soon.
        'regular_price' => $product_data->price,  // The regular (normal) price of the product.
        'total' => $product_data->price * $product->qty,
      ];
    }

    $save_products = $this->order_products_model->insertBatch($cart_products);

    $tax_cost = $subtotal * ($this->data['tax_rate'] - 1);
    $total_cost = $subtotal * $this->data['tax_rate'];

    $order_costs = [
      'subtotal' => $subtotal,
      'tax' => $tax_cost,
      'total' => $total_cost,
    ];

    // $update_order = $this->checkout_model->where('id', $order_id)->update($order_costs);
    $update_order = $this->checkout_model->update($order_id, $order_costs);

    if($update_order > 0) {
      // Delete user's cart items
      $this->_clear_cart($user['id']);
      session()->setFlashdata('order_completed', $data['cart_key']);
      return redirect()->to('/cart/success');
    }
    else {
      return redirect()->to('/cart');
    }
  }

  public function success()
  {
    $success = session()->getFlashdata('order_completed');
    if($success) {
      // Fetch Order Details
      $order = $this->checkout_model->where('order_key', $success)->get()->getResult();

      // Fetch Order Products
      $order_products = $this->checkout_model->fetchOrderDetails($success);

      foreach($order_products as $product) {
        // initialize images
        $images = [];
        $imageIds = [];

        // Fetch product images
        if($product->images) {
          $imageIds = explode(',',$product->images);
          $images = $this->image_model->whereIn('id', $imageIds)->get()->getResult();
        }

        // Output array
        $cart_products[] = [
          'pid' => $product->product_id,
          'qty' => $product->qty,
          'product_data' => $product,
          'images' => $images,
        ];
      }

      $this->data['order_data'] = $order;
      $this->data['order_products'] = $cart_products;
      $this->data['order_completed'] = 1;
      return view('cart/success_page', $this->data);
    }
    else {
      return redirect()->to('/cart');
    }
    
  }

  public function update_cart()
  {
    $data = $this->request->getPost();

    $toUpdate = [];

    foreach($data['cart'] as $cart_pid => $cart_product) {

      $this->cart_model->set('qty', $cart_product['qty'])->where('uid', $this->uid)->where('pid', $cart_pid)->update();
    }

    return redirect()->to('/cart');
  }

  private function _fetch_cart_items()
  {
    // Fetch user data using guid
    $user_data = $this->user_model->getUserByGuid($this->guid);

    // print_r($user_data);die();

    // Fetch cart items from db
    return $this->cart_model->where('uid', $user_data['id'])->get()->getResult();
  }

  private function _clear_cart($user_id)
  {
    if(!delete_cookie('cart_data')) {
			unset($_COOKIE['cart_data']);
      setcookie('cart_data', '', time() - 3600, "/");
		}
    
    $this->cart_model->where('uid', $user_id)->delete();

    return true;
  }
}