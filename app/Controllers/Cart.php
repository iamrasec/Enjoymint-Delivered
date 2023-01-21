<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Libraries\EnjoymintUtils;
use CodeIgniter\I18n\Time;

class Cart extends BaseController
{
  public function __construct() 
	{
		helper(['jwt', 'cookie', 'edimage', 'date']);

		$this->data = [];
		$this->role = session()->get('role');
    $this->location = session()->get('search1');
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
    $this->data['service_charge'] = 5.00;
    $this->data['location'] = $this->location;
    $this->sender_email = getenv('SMTP_EMAIL_USER');

    date_default_timezone_set('America/Los_Angeles');
	}

  public function index()
  {
    $session = session();
   
    $location = $session->get('search1');
    $this->data['location_keyword'] = $this->location;
    $cart_products = [];

    // Fetch items in cart
    $cart_raw = $this->get_cart_data();

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
   if(!empty($product_data->delivery_type)){
      if($product_data->delivery_type == 1){
    $this->data['delivery'] = $product_data->delivery_type;
      }else{
        $this->data['delivery'] = ""; 
      }
   }else{
    $this->data['delivery'] = ""; 
   }
    
    $this->data['cart_products'] = $cart_products;
    $this->data['guid'] = ($this->guid > 0) ? $this->guid : 0;
    // $this->data['guid'] = $this->guid;

    // Generate current date/time (PDT/PST)
    $currDate = new Time("now", "America/Los_Angeles", "en_EN");

    // If current time is more than 6PM, generate tomorrow's date/time (PDT/PST)
    if($currDate->format('H') > '18') {
      $currDate = new Time("tomorrow", "America/Los_Angeles", "en_EN");
    }

    $this->data['currDate'] = $currDate;

    // For use with Fast-tracked checkout
    $fsDelTime = explode(":", $currDate->toTimeString());

    if($fsDelTime[0] < 10) {
      $fsDelTime = 10 . $fsDelTime[1] ." - ". 13 . $fsDelTime[1];
    }
    else {
      $fsDelTime = $fsDelTime[0]. $fsDelTime[1] ." - ". ($fsDelTime[0] + 3) . $fsDelTime[1];
    }

             $this->data['location_keyword'] = $location; 
    $this->data['fscurrDay'] = $currDate->toDateString();
    $this->data['fsDelTime'] = $fsDelTime;
    

    return view('cart/cart_page', $this->data);
  }

  public function checkout()
  {
    $session = session();
    $location = $session->get('search1');
    $postData = $this->request->getPost();

    // echo "<pre>".print_r($postData, 1)."</pre>";die();
    
    if(!empty($postData)){
      $this->data['del_type'] = $postData['del_type'];    // Delivery Type.  fs for Fast-Tracked, nfs for Standard or  Non Fast-Tracked
      $this->data['delivery_schedule'] = $postData['delivery_schedule'];    // Delivery Date
      $this->data['time_window'] = $postData['time_window'];    // Delivery Time range (3 hour window).  24-hour format
    }
    else {
      // Go back to cart page when there are no post data
      return redirect()->to('/cart');
    }

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

    // Generate unique order token
    $this->data['checkout_token'] = $enjoymint_utils->generateRandomString(20);

    $currDate = new Time("now", "America/Los_Angeles", "en_EN");


    if($currDate->format('H') > '18') {
      $currDate = new Time("tomorrow", "America/Los_Angeles", "en_EN");
    }

    $this->data['currDate'] = $currDate;

    // For use with Fast-tracked checkout
    $fsDelTime = explode(":", $currDate->toTimeString());

    if($fsDelTime[0] < 10) {
      $fsDelTime = 10 . $fsDelTime[1] ." - ". 13 . $fsDelTime[1];
    }
    else {
      $fsDelTime = $fsDelTime[0]. $fsDelTime[1] ." - ". ($fsDelTime[0] + 3) . $fsDelTime[1];
    }

    $this->data['location_keyword'] = $location; 
    $this->data['fscurrDay'] = $currDate->toDateString();
    $this->data['fsDelTime'] = $fsDelTime;

    return view('cart/checkout', $this->data);
  }

  public function place_order()
  {
    $session = session();

    $data = $this->request->getPost();

    // echo "<pre>".print_r($data)."</pre>"; die();

    $user = $this->user_model->getUserByGuid($data['guid']);
    // $token = $data['cart_key'];


    $delivery_type = 0; // Defaults to Not Fast-Tracked
    if($data['del_type'] == 'fs') {
      $delivery_type = 1;
    }

    // Initialize order record to be saved in the database
    $order_data = [
      'order_key' => $data['cart_key'],
      'customer_id' => $user['id'],
      'first_name' => $user['first_name'],
      'last_name' => $user['last_name'],
      'email' => $user['email'],
      'phone' => $data['phone'],
      'address' => $data['apt_no'] ." ". $data['street'] .", ". $data['city'] .", ". $data['state'] ." ". $data['zipcode'],
      'payment_method' => $data['payment_method'],
      'order_notes' => $data['order_notes'],
      'delivery_schedule' => $data['delivery_schedule'],
      'delivery_time' => $data['time_window'],
      'delivery_type' => $delivery_type,
    ];
    
    // echo "<pre>".print_r($data, 1)."</pre>";die();

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
      $order_data['id'] = $order_id;
      $order_data['order_costs'] = $order_costs;

      // Send Order Confirmation Email
      $this->send_order_confirmation($order_data, $cart_products);
      $this->send_order_notification($order_data, $cart_products);
      // Delete user's cart items
      $this->_clear_cart($user['id']);
      session()->setFlashdata('order_completed', $data['cart_key']);
      return redirect()->to('/cart/success');
    }
    else {
      return redirect()->to('/cart');
    }
  }

  public function send_order_confirmation($order, $products)
  {
    $sender_email = $this->sender_email;
    $user_email = session()->get('email');

    $email = \Config\Services::email();
		$email->setFrom($sender_email);
		$email->setTo($user_email);
		$email->setSubject('Order Placed Successfully');
		

    for($i = 0; $i < count($products); $i++) {
      $products[$i]['images'] = getProductImage($products[$i]['product_id']);
    }

    $order_data = ["order_data" => $order, "order_products" => $products, "site_logo" => 'http://fuegonetworxservices.com/assets/img/Enjoymint-Logo-Landscape-White-2.png'];

    // echo "<pre>".print_r($sender_email, 1)."</pre>";
    // echo "<pre>".print_r($user_email, 1)."</pre>";
    // echo "<pre>".print_r($order_data, 1)."</pre>"; die();

		$template = view('email/order_confirmation', $order_data);

    $email->setMessage($template);
    $email->setNewline("\r\n");

		if($email->send()) {
      return true;
		}
  }
  
  public function send_order_notification($order, $products)
  {
    $sender_email = $this->sender_email;
    

    $email = \Config\Services::email();
		$email->setFrom($sender_email);
		$email->setTo($this->sender_email);
		$email->setSubject('New Order');
		

    for($i = 0; $i < count($products); $i++) {
      $products[$i]['images'] = getProductImage($products[$i]['product_id']);
    }

    $order_data = ["order_data" => $order, "order_products" => $products, "site_logo" => 'http://fuegonetworxservices.com/assets/img/Enjoymint-Logo-Landscape-White-2.png'];

    // echo "<pre>".print_r($sender_email, 1)."</pre>";
    // echo "<pre>".print_r($user_email, 1)."</pre>";
    // echo "<pre>".print_r($order_data, 1)."</pre>"; die();

		$template = view('email/order_notification', $order_data);

    $email->setMessage($template);
    $email->setNewline("\r\n");

		if($email->send()) {
      return true;
		}
  }

  public function success($oid = false)
  {
    $session = session();
    $location = $session->get('search1');
    if($oid != false) {
      $success = $oid;
    }
    else {
      $success = session()->getFlashdata('order_completed');
    }
    
    if($success) {
      // Fetch Order Details
      $order = $this->checkout_model->where('order_key', $success)->get()->getResult();

      // echo "<pre>".print_r($order, 1)."</pre>";die();

      // Fetch Order Products
      $order_products = $this->checkout_model->fetchOrderDetails($success);

      foreach($order_products as $product) {

        $this->product_model->incrementOrders($product->product_id);

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

      $this->data['location_keyword'] = $location; 
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

    $cart_products = [];

    // Fetch items in cart
    $cart_raw = $this->get_cart_data();

    // echo "<pre>".print_r($data, 1)."</pre>";
    // echo "<pre>".print_r($cart_raw, 1)."</pre>";

    $toUpdate = [];

    $new_cart_data = '';

    if($this->isLoggedIn == 1) {
      foreach($data['cart'] as $cart_pid => $cart_product) {
        $this->cart_model->set('qty', $cart_product['qty'])->where('uid', $this->uid)->where('pid', $cart_pid)->update();
      }
    }
    // else {
    //   for($i = 0; count($cart_raw) > $i; $i++) {
    //     foreach($data['cart'] as $pid => $pqty) {
    //       if($pid == $cart_raw[$i]->pid) {
    //         $cart_raw[$i]->qty = $pqty['qty'];
    //       }
    //     }
    //   }
    //   $new_cart_data = $cart_raw;
    // }

    // echo "<pre>".print_r($cart_raw, 1)."</pre>";die();

    // $this->session->set_flashdata('updated_cart_qty', $new_cart_data);
    return redirect()->to('/cart');
  }

  public function add()
  {
    $data = $this->request->getPost();

    // If user is currently logged in
    if($data['uid'] > 0) {
      $product_in_cart = $this->cart_model->checkProductExists($data['uid'], $data['pid']);
      $new_item_count = 0;

      if(!empty($product_in_cart)) {
        $saveCart = $this->cart_model->updateCartProduct($data['uid'], $data['pid'], $data['qty']);

        echo json_encode(["status" => 'updated', "newItemCount" => $new_item_count, "pid" => $data['pid'], "qty" => $data['qty']]);
        exit;
      }
      else {
        $saveCart = $this->cart_model->insert($data);
        $new_item_count++;

        echo json_encode(["status" => 'added', "newItemCount" => $new_item_count, "pid" => $data['pid'], "qty" => $data['qty']]);
        exit;
      }
    }
    // If user is not logged in (anonymous)
    else {
      if(isset($_COOKIE['cart_data'])) {
        $product_in_cart = $_COOKIE['cart_data'];
      }
      else {
        $product_in_cart = [
          [
            "pid" => $data['pid'],
            "qty" => $data['qty'],
          ]
        ];
      }
      
      echo json_encode(["status" => 'updated', "productInCart" => $product_in_cart, "pid" => $data['pid']]);
      exit;
    }
  }

  private function get_cart_data()
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

    // if(isset($this->session->flashdata('updated_cart_qty'))) {
    //   $cookie_cart = $this->session->flashdata('in');
    // }

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

    return $cart_raw;
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
}