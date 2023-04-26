<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Libraries\EnjoymintUtils;
use CodeIgniter\I18n\Time;
use PhpCsFixer\Fixer\StringNotation\HeredocToNowdocFixer;

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
    $this->location_model = model('LocationModel');
    $this->promo_model = model('PromoModel');
    $this->promoProducts_model = model('PromoProductsModel');
    $this->discount_model = model('DiscountModel');
    $this->product_variant_model = model('ProductVariantModel');

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
    $priceTotal = $session->get('totalSub');
    $discount_product = $session->get('discountSub');
    $testtest = $session->get('promo_edit');
    //  print_r($priceTotal);
    $discount_data = [];
    if (!empty($discount_product)) {
    foreach($discount_product as $discount_prod){
      $discount_data[] = [
        'id' => $discount_prod['id'],
        'discounted_price' => $discount_prod['discounted_price'],
        'promo_type' => $discount_prod['promo_type'],
        'total_cost' => $discount_prod['total_cost'],
      ];
    }
  } 

     //print_r($priceTotal);
    $location = $session->get('search1');
    $this->data['location_keyword'] = $this->location;
    $cart_products = [];

    // Fetch items in cart
    $cart_raw = $this->get_cart_data();

    // echo "<pre>".print_r($cart_raw, 1)."</pre>"; die();
//print_r($cart_raw);
    // Loop through all the products and fetch all the product info
    foreach($cart_raw as $product) {
      
      
      // Get products from the database using pid
      if($product->vid != 0){
        //$this->data['variant'] = true;
      $product_data = $this->product_model->getProductDataVariant($product->pid);
      }else{
      $product_data = $this->product_model->getProductData($product->pid);
      }

      if($product_data->on_sale == 1){

      }
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
        'vid' => $product->vid,
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

    //sale price
   // $all_products = $this->product_model->where('on_sale', 1)->get()->getResult();
    $sale_price = $this->discount_model->get()->getResult();
    
    
    $sale_discount = [];
    $sale_total = 0;
    
      
      foreach ($cart_products as $cart) {
        if ($cart['product_data']->on_sale == 1) { 
          $cart_item_id = $cart['product_data']->id;
          
          $discount = 0;
          $sale_price_query = $this->discount_model
              ->where('pid', $cart_item_id)
              ->get()
              ->getResult();
          
          if (!empty($sale_price_query)) {
            $productPrice = $cart['product_data']->price;

            if($sale_price_query[0]->discount_attribute == "percent") {
             
              $dis_sale = $productPrice * ($sale_price_query[0]->discount_value / 100);
              $netTotal = $productPrice - $dis_sale;
             
            }
            if($sale_price_query[0]->discount_attribute == "fixed"){ 
              $dis_sale = $sale_price_query[0]->discount_value;
              $netTotal = $productPrice - $dis_sale;  
            
            }
            if($sale_price_query[0]->discount_attribute == "sale_price"){
             
              $dis_sale = $sale_price_query[0]->discount_value;
              $netTotal = $dis_sale;
             
            }
          
          }
           // print_r($dis_sale);
            $cart['product_data']->price = $netTotal;
            $disProduct = [
                'id' => $cart_item_id,
                'discount_attribute' => $sale_price_query[0]->discount_attribute,
                'dis_sale' => $dis_sale,
                'saleTotal' => $netTotal,
                'origPrice' => $productPrice,
                'discount_value' => $dis_sale // add this line to retrieve the discount value
            ];
            // if($disProduct['id'] != $prev_id){
            array_push($sale_discount, $disProduct);
            $sale_total += $dis_sale;
    
      }
       //echo '<br>Total sale: ' . $sale_total . '<br>';
  }
  
    $this->data['sale_total'] = $sale_total;
    $this->data['sale_discount'] = $sale_discount;
    $this->data['cart_products'] = $cart_products;
    //print_r($this->data['sale_discount']);
    $this->data['discount_data'] = $discount_data;
    $session->sale_total = $sale_total;

    $promo_sale =  $priceTotal + $sale_total;
    $session->promo_sale = $promo_sale;
     //print_r($promo_sale);

    // print_r($this->data['sale_discount']);
    //echo 'Sale Discount------' . print_r($this->data['sale_discount'], true) . '<br>';
    //echo 'Promo Discount------' . print_r($this->data['discount_data'], true);
    $discount_id = [];
    if(!empty($discount_product)){ 
    foreach ($discount_product as $dd) {
      $discount_id[] = $dd["id"]; // add the age value to the new array
    }
  }
  $dissale_id = [];
  if(!empty($sale_discount)){
  foreach ($sale_discount as $ds) {
    $dissale_id[] = $ds['id'];
  }
}
    $this->data['dissale_id'] = $dissale_id;
    //print_r($this->data['dissale_id']);
    $session->sale_id =  $dissale_id;
    $this->data['discount_id'] = $discount_id;
    //print_r($this->data['discount_id']);
    $this->data['guid'] = ($this->guid > 0) ? $this->guid : 0;
    // $this->data['guid'] = $this->guid;

    // Generate current date/time (PDT/PST)
    $currDate = new Time("tomorrow", "America/Los_Angeles", "en_EN");

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
    $user_id = $this->uid;
    if($user_id == null){
      $session->setFlashdata('message', 'Please login first');
    }
    $this->data['uid'] = $user_id;
    $this->data['location_keyword'] = $this->location_model->where('user_id', $user_id )->select('address')->first();
    $this->data['location_delivery'] = $this->location_model->where('user_id', $user_id )->select('delivery_schedule')->first();
    $this->data['fscurrDay'] = $currDate->toDateString();
    $this->data['fsDelTime'] = $fsDelTime;
    $this->data['pricesubtotal'] = $promo_sale;
    $this->data['pricesub'] = $discount_product;
    //print_r($this->data['pricesubtotal']);

    return view('cart/cart_page', $this->data);
  }

  public function checkout()
  {
    $session = session();
    $location = $session->get('search1');
    $postData = $this->request->getPost();
    $priceTotal = $session->get('totalSub');
    $sale_total = $session->get('sale_total');
    $promo_sales = $session->get('promo_sale');
    //print_r($promo_sales);
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

    // echo "<pre>".print_r($db_cart, 1)."</pre>";die();

    if(!empty($db_cart)) {
      $cart_raw = $db_cart;
    }

    // Loop through all the products and fetch all the product info
    foreach($cart_raw as $product) {

      // print_r($product);

      // Get products from the database using pid
      $product_data = $this->product_model->getProductData($product->pid);
      $session->product_cart = $product_data;

      // initialize images
      $images = [];
      $imageIds = [];

      // Fetch product images
      if($product_data->images) {
        $imageIds = explode(',',$product_data->images);
        $images = $this->image_model->whereIn('id', $imageIds)->get()->getResult();
      }

      // Check if Variant ID is not 0
      if($product->vid != 0) {
        $variant_data = $this->product_variant_model->where('id', $product->vid)->get()->getResult();
        
        // echo "<pre>".print_r($variant_data, 1)."</pre>";

        $product_data->vid = $variant_data[0]->id;
        $product_data->price = $variant_data[0]->price;
        $product_data->unit_measure = $variant_data[0]->unit;
        $product_data->unit_value = $variant_data[0]->unit_value;
        $product_data->stocks = $variant_data[0]->stock;
      }

      // Output array
      $cart_products[] = [
        'pid' => $product->pid,
        'vid' => $product->vid,
        'qty' => $product->qty,
        'product_data' => $product_data,
        'images' => $images,
        'priceTotal' => $promo_sales,
      ]; 
    }

    // }
    $this->data['cart_products'] = $cart_products;
    $this->data['guid'] = $this->guid;
    $enjoymint_utils = new EnjoymintUtils();

    // Generate unique order token
    $this->data['checkout_token'] = $enjoymint_utils->generateRandomString(20);

    $currDate = new Time("tomorrow", "America/Los_Angeles", "en_EN");


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
    
    $user_id = $this->uid;
    if($user_id == null){
      $session->setFlashdata('message', 'Please login first');
    }
    $this->data['uid'] = $user_id;
    $this->data['location_keyword'] = $this->location_model->where('user_id', $user_id )->select('address')->first();
    $this->data['location_delivery'] = $this->location_model->where('user_id', $user_id )->select('delivery_schedule')->first();
    $this->data['fscurrDay'] = $currDate->toDateString();
    $this->data['fsDelTime'] = $fsDelTime;

    return view('cart/checkout', $this->data);
  }

  public function place_order()
  {
    $session = session();

    $priceTotal = $session->get('totalSub');
    $promo_codes = $session->get('promo_codes');
    if(!empty($promo_codes)){
      $promo = $promo_codes;
    }else{
      $promo = "";
    }

    $data = $this->request->getPost();

    // echo "<pre>".print_r($data, 1)."</pre>"; die();

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
      'promo_code' => $promo,
    ];
  
    // echo "<pre>".print_r($data, 1)."</pre>";die();
   
    // Insert initial order record and grab the order id
    $order_id = $this->checkout_model->insert($order_data);

    // Fetch products in cart
    $db_cart = $this->_fetch_cart_items();

    // echo "<pre>".print_r($db_cart, 1)."</pre>";die();

    // Initialize subtotal cost
    $subtotal = 0;

    // Loop through all the products and fetch all the product info
    foreach($db_cart as $product) {
    
      // Get products from the database using pid
      $product_data = $this->product_model->getProductData($product->pid);

      $new_stock_qty = array('stocks' => '(stocks - '.$product->qty.')');

        $update_stocks = $this->product_model->where('id', $product->pid)->set('stocks', '(stocks - '.$product->qty.')', false)->update();
      }

      // Compute for subtotal cost
      $subtotal += $product_data->price * $product->qty;

      // if(!empty($priceTotal)){
      //   $subtotalprice = $product_data->price * $product->qty;
      //   $subtotal = $product['priceTotal'] + $subtotalprice;
      // }
      // else{
      //   $subtotal += $product_data->price * $product->qty;
      // }

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
        'vid' => $product->vid,
      ];
    

    // echo "<pre>".print_r($cart_products, 1)."</pre>";die();

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

      // echo "<pre>".print_r($order_products, 1)."</pre>";die();

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
      $user_id = $this->uid;
      if($user_id == null){
        $session->setFlashdata('message', 'Please login first');
      }
      $this->data['uid'] = $user_id;
      $this->data['location_keyword'] = $this->location_model->where('user_id', $user_id )->select('address')->first();
      $this->data['location_delivery'] = $this->location_model->where('user_id', $user_id )->select('delivery_schedule')->first();
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
      if(!empty($data['cart'])){
      foreach($data['cart'] as $cart_pid => $cart_product) {
        $this->cart_model->set('qty', $cart_product['qty'])->where('uid', $this->uid)->where('pid', $cart_pid)->update();
      }
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
    //print_r($data);
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
   //print_r($cookie_cart);
    // if(isset($this->session->flashdata('updated_cart_qty'))) {
    //   $cookie_cart = $this->session->flashdata('in');
    // }

    // Check if user is logged in
    if($this->isLoggedIn == 1) {
      
      $db_cart = $this->_fetch_cart_items();
      // print_r($db_cart);

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
        'vid' => $cart_product->vid,
      ];

      $this->cart_model->insert($toSave);
    }

    return true;
  }
  
  public function promo_add(){
    $session = session();
    $sale_id = $session->get('sale_id');
    if($this->request->getPost()) {
      $validation =  \Config\Services::validation();

    $promo = $this->request->getVar('promo_code');
    $session->promo_codes =  $promo;
    $this->data['prom_code'] = $promo;
    //$this->data['location_keyword'] = $this->location;

    // $db_cart = $this->_fetch_cart_items();

    // if(!empty($db_cart)) {
    //   $cart_raw = $db_cart;
    // }
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
      
    $promo_data = $this->promo_model->getPromo($promo);

    $product_descount = [];
     
    // $this->data['prom_data'] = $promo_data;         
    // $test = json_encode($promo_data);
    // print_r($promo_data);
   
    if(!empty($promo_data)) {
      switch($promo_data[0]->promo_type) {
        case "percent_off":
          //promo spec prod
          if($promo_data[0]->promo_product == "promo_products_specific"){
            $totalVal = 0;
            $distotal = 0;
            $promo_prod = explode(',' , $promo_data[0]->discounted_specific_product);

            foreach($cart_products as $value){
              
              if($promo_data[0]->require_purchase == 1){
                $req_prod = explode(',' , $promo_data[0]->required_product_id);
                $req_cat = explode(',' , $promo_data[0]->required_category_id);
                $discount = 0;
                if(!in_array($value['product_data']->id, $sale_id)){
                switch(true) {
                  case in_array($value['product_data']->id, $promo_prod):
                   
                    $productPrice = $value['product_data']->price;
                    $discount = $productPrice * ($promo_data[0]->discount_value / 100);
                    $per_price = $discount * ($productPrice / 100);
                    $netTotal = $productPrice -  $discount;
                    
                    $value['product_data']->price = $netTotal;
                    $disProduct = [
                      'id' => $value['product_data']->id,
                      'discounted_price' => $discount,
                      'percent_price' => $per_price,
                      'promo_type' => $promo_data[0]->promo_type,
                      'total_cost' => $netTotal
                  ];
                    array_push($product_descount, $disProduct);
                    break;
                  case in_array($value['product_data']->id, $req_prod):
                    
                    $productPrice = $value['product_data']->price;
                    $discount = $productPrice * ($promo_data[0]->discount_value / 100);
                    $per_price = $discount * ($productPrice / 100);
                    $netTotal = $productPrice -  $discount;
                 
                    $value['product_data']->price = $netTotal;
                    $disProduct = [
                      'id' => $value['product_data']->id,
                      'discounted_price' => $discount,
                      'percent_price' => $per_price,
                      'promo_type' => $promo_data[0]->promo_type,
                      'total_cost' => $netTotal
                  ];
                    array_push($product_descount, $disProduct);
                    break;
                  case in_array($value['product_data']->category, $req_cat):
                    // echo '2nd array condition for req_purchase cat<br>';
                    $productPrice = $value['product_data']->price;
                    $discount = $productPrice * ($promo_data[0]->discount_value / 100);
                    $per_price = $discount * ($productPrice / 100);
                    $netTotal = $productPrice - $discount;
            
                    $value['product_data']->price = $netTotal;
                    $disProduct = [
                      'id' => $value['product_data']->id,
                      'discounted_price' => $discount,
                      'percent_price' => $per_price,
                      'promo_type' => $promo_data[0]->promo_type,
                      'total_cost' => $netTotal
                  ];
                    array_push($product_descount, $disProduct);
                    break;
                }
              }
              } elseif($promo_data[0]->require_purchase == "none"){
                
                if(!in_array($value['product_data']->id, $sale_id)){
                
                  $discount = 0;
                  if(in_array($value['product_data']->id, $promo_prod)){            
                    
                    $productPrice = $value['product_data']->price;
                    $discount = $productPrice * ($promo_data[0]->discount_value / 100);
                    $per_price = $discount * ($productPrice / 100);
                    $netTotal = $productPrice -  $discount;
                      
                    $value['product_data']->price = $netTotal;         
                    
                    $disProduct = [
                      'id' => $value['product_data']->id,
                      'discounted_price' => $discount,
                      'percent_price' => $per_price,
                      'promo_type' => $promo_data[0]->promo_type,
                      'total_cost' => $netTotal,
                  ];
                  //echo 'Total Value: ' . $per_price;
                  array_push($product_descount, $disProduct);
                  }             
                }
                
              }
              // echo' tama ni<br> ------------ <br>';
              $distotal += $discount;
              $totalVal += $value['product_data']->price;
            } 
              // echo 'Total Value: ' . $distotal;
          } 
            //promo cat prod
          if ($promo_data[0]->promo_product == 'promo_products_cat') {
            $promo_cat = explode(',', $promo_data[0]->discounted_category_id);
            $totalVal = 0;
            $distotal = 0;
            // print_r($promo_cat);
            foreach ($cart_products as $value) {
                // echo $value['product_data']->price . '<br>';
                // echo $value['product_data']->category . '<br>---';
                if ($promo_data[0]->require_purchase == 1) {
                    $req_prod = explode(',', $promo_data[0]->required_product_id);
                    $req_cat = explode(',', $promo_data[0]->required_category_id);
                    $discount = 0;
                    if(!in_array($value['product_data']->id, $sale_id)){
                    switch (true) {
                        case in_array($value['product_data']->category, $promo_cat):
                            // echo 'in array <br>';
                            $productPrice = $value['product_data']->price;
                            $discount = $productPrice * ($promo_data[0]->discount_value / 100);
                            $per_price = $discount * ($productPrice / 100);
                            $netTotal = $productPrice  - $discount;
                            // echo $discount . ' <br>';
                            // echo $netTotal . ' <br> ------------ <br>';
                            $value['product_data']->price = $netTotal;
                            $disProduct = [
                              'id' => $value['product_data']->id,
                              'discounted_price' => $discount,
                              'percent_price' => $per_price,
                              'promo_type' => $promo_data[0]->promo_type,
                              'total_cost' => $netTotal
                          ];
                            array_push($product_descount, $disProduct);
                            break;
                        case in_array($value['product_data']->id, $req_prod):
                            // echo '2nd array condition for req_purchase prod in categ<br>';
                            $productPrice = $value['product_data']->price;
                            $discount = $productPrice * ($promo_data[0]->discount_value / 100);
                            $per_price = $discount * ($productPrice / 100);
                            $netTotal = $productPrice -  $discount;
                            // echo $discount . ' <br>';
                            // echo $netTotal . ' <br> ------------ <br>';
                            $value['product_data']->price = $netTotal;
                            $disProduct = [
                              'id' => $value['product_data']->id,
                              'discounted_price' => $discount,
                              'percent_price' => $per_price,
                              'promo_type' => $promo_data[0]->promo_type,
                              'total_cost' => $netTotal
                          ];
                            array_push($product_descount, $disProduct);
                            break;
                        case in_array($value['product_data']->category, $req_cat):
                            // echo '2nd array condition for req_purchase cat in categ<br>';
                            $productPrice = $value['product_data']->price;
                            $discount = $productPrice * ($promo_data[0]->discount_value / 100);
                            $per_price = $discount * ($productPrice / 100);
                            $netTotal = $productPrice - $discount;
                            // echo $discount . ' <br>';
                            // echo $netTotal . ' <br> ------------ <br>';
                            $value['product_data']->price = $netTotal;
                            $disProduct = [
                              'id' => $value['product_data']->id,
                              'discounted_price' => $discount,
                              'percent_price' => $per_price,
                              'promo_type' => $promo_data[0]->promo_type,
                              'total_cost' => $netTotal
                          ];
                            array_push($product_descount, $disProduct);
                            break;
                    }
                  }
                    // echo ' tama ni<br> ------------ <br>';
                    // $totalVal += $value['product_data']->price;
                } elseif ($promo_data[0]->require_purchase == "none") {
                  $discount = 0;
                  if(!in_array($value['product_data']->id, $sale_id)){
                  if (in_array($value['product_data']->category, $promo_cat)) {
                        // echo 'in array <br>';
                        $productPrice = $value['product_data']->price;
                        $discount = $productPrice * ($promo_data[0]->discount_value / 100);
                        $per_price = $discount * ($productPrice / 100);
                        $netTotal = $productPrice  - $discount;
                        // echo $discount . ' <br>';
                        // echo $netTotal . ' <br>------------ <br>';
                        $value['product_data']->price = $netTotal;
                        $disProduct = [
                          'id' => $value['product_data']->id,
                          'discounted_price' => $discount,
                          'percent_price' => $per_price,
                          'promo_type' => $promo_data[0]->promo_type,
                          'total_cost' => $netTotal
                      ];
                      // echo  'mao ni'.$disProduct;
                      array_push($product_descount, $disProduct);
                    }
                  }
                }
                $distotal += $discount;
                $totalVal += $value['product_data']->price;
            }
            // echo 'Total Value: ' . $totalVal;
        }
            //promo all
            if ($promo_data[0]->promo_product == 'promo_products_all') {
              $totalVal = 0;
              $distotal = 0;
              foreach ($cart_products as $value) {
                  // echo $value['product_data']->price . '<br>';
                  // echo $value['product_data']->id . '<br>---';
                  if (isset($value['product_data'])) {
                      // echo 'in array prod_all<br>';
                      $discount = 0;
                      $productPrice = $value['product_data']->price;
                      $discount = $productPrice * ($promo_data[0]->discount_value / 100);
                      $per_price = $discount * ($productPrice / 100);
                      $netTotal = $productPrice  - $discount;
                      // echo $discount . ' <br>';
                      // echo $netTotal . ' <br>------------ <br>';
                      $value['product_data']->price = $netTotal;
                      $disProduct = [
                        'id' => $value['product_data']->id,
                        'discounted_price' => $discount,
                        'percent_price' => $per_price,
                        'promo_type' => $promo_data[0]->promo_type,
                        'total_cost' => $netTotal
                    ];
                    // echo  'mao ni'.$disProduct;
                    array_push($product_descount, $disProduct);
                  }
                  $distotal += $discount;
                  $totalVal += $value['product_data']->price;
              }
              // echo 'Total Value: ' . $totalVal;
          }
          break;
        case "fixed":
           //promo spec prod fix off
           if($promo_data[0]->promo_product == "promo_products_specific"){
            $totalVal = 0;
            $distotal = 0;
            $promo_prod = explode(',' , $promo_data[0]->discounted_specific_product);
    
            foreach($cart_products as $value){
              // echo $value['product_data']->price.'---price<br>';
              // echo $value['product_data']->id.'---id<br>';
              
              if($promo_data[0]->require_purchase == 1){
                $req_prod = explode(',' , $promo_data[0]->required_product_id);
                $req_cat = explode(',' , $promo_data[0]->required_category_id);
                $discount = 0;
                if(!in_array($value['product_data']->id, $sale_id)){
                switch(true) {
                  case in_array($value['product_data']->id, $promo_prod):
                    // echo '1st array <br>';
                    $productPrice = $value['product_data']->price;
                    $discount = $promo_data[0]->discount_value;
                    $per_price = $discount * ($productPrice / 100);
                    $netTotal = $productPrice -  $promo_data[0]->discount_value;
                    // echo $netTotal.'-----net_total <br> ------------ <br>';
                    $value['product_data']->price = $netTotal;
                    $disProduct = [
                      'id' => $value['product_data']->id,
                      'discounted_price' => $discount,
                      'percent_price' => $per_price,
                      'promo_type' => $promo_data[0]->promo_type,
                      'total_cost' => $netTotal
                  ];
                    array_push($product_descount, $disProduct);
                    break; 
                  case in_array($value['product_data']->id, $req_prod):
                    // echo '2nd array condition for req_purchase prod<br>';
                    $productPrice = $value['product_data']->price;
                    $discount = $promo_data[0]->discount_value;
                    $per_price = $discount * ($productPrice / 100);
                    $netTotal = $productPrice -  $promo_data[0]->discount_value;
                    // echo $netTotal.'----net_total <br> ------------ <br>';
                    $value['product_data']->price = $netTotal;
                    $disProduct = [
                      'id' => $value['product_data']->id,
                      'discounted_price' => $discount,
                      'percent_price' => $per_price,
                      'promo_type' => $promo_data[0]->promo_type,
                      'total_cost' => $netTotal
                  ];
                    array_push($product_descount, $disProduct);
                    break;
                  case in_array($value['product_data']->category, $req_cat):
                    // echo '2nd array condition for req_purchase cat<br>';
                    $productPrice = $value['product_data']->price;
                    $discount = $promo_data[0]->discount_value;
                    $per_price = $discount * ($productPrice / 100);
                    $netTotal = $productPrice - $promo_data[0]->discount_value;
                    // echo $netTotal.'-----net_total <br> ------------ <br>';
                    $value['product_data']->price = $netTotal;
                    $disProduct = [
                      'id' => $value['product_data']->id,
                      'discounted_price' => $discount,
                      'percent_price' => $per_price,
                      'promo_type' => $promo_data[0]->promo_type,
                      'total_cost' => $netTotal
                  ];
                    array_push($product_descount, $disProduct);
                    break;
                }
              }
              } elseif($promo_data[0]->require_purchase == "none"){
                $discount = 0;
                if(!in_array($value['product_data']->id, $sale_id)){
                if(in_array($value['product_data']->id, $promo_prod)){
                  // echo '1st array for none<br>';
                  $productPrice = $value['product_data']->price;
                  $discount = $promo_data[0]->discount_value;
                  $per_price = $discount * ($productPrice / 100);
                  $netTotal = $productPrice -  $promo_data[0]->discount_value;
                  // echo $netTotal.'-----net_total <br> ------------ <br>';
                  $value['product_data']->price = $netTotal;
                  $disProduct = [
                    'id' => $value['product_data']->id,
                    'discounted_price' => $discount,
                    'percent_price' => $per_price,
                    'promo_type' => $promo_data[0]->promo_type,
                    'total_cost' => $netTotal
                ];
                // echo  'mao ni'.$disProduct;
                array_push($product_descount, $disProduct);
                }
              }
              }
              // echo' tama ni<br> ------------ <br>';
              $distotal += $discount;
              $totalVal += $value['product_data']->price;
            }
            // echo 'Total Value: '.$totalVal;
          }
            //promo cat prod fix off
            if ($promo_data[0]->promo_product == 'promo_products_cat') {
              $promo_cat = explode(',', $promo_data[0]->discounted_category_id);
              $totalVal = 0;
              $distotal = 0;
              // print_r($promo_cat);
              foreach ($cart_products as $value) {
                  // echo $value['product_data']->price . '<br>';
                  // echo $value['product_data']->category . '<br>---';
                  if ($promo_data[0]->require_purchase == 1) {
                      $req_prod = explode(',', $promo_data[0]->required_product_id);
                      $req_cat = explode(',', $promo_data[0]->required_category_id);
                      $discount = 0;
                      if(!in_array($value['product_data']->id, $sale_id)){
                      switch (true) {
                          case in_array($value['product_data']->category, $promo_cat):
                              // echo 'in array <br>';
                              $productPrice = $value['product_data']->price;
                              $discount = $promo_data[0]->discount_value;
                              $per_price = $discount * ($productPrice / 100);
                              $netTotal = $productPrice  - $promo_data[0]->discount_value;
                              // echo $netTotal . '-----net_total <br> ------------ <br>';
                              $value['product_data']->price = $netTotal;
                              $disProduct = [
                                'id' => $value['product_data']->id,
                                'discounted_price' => $discount,
                                'percent_price' => $per_price,
                                'promo_type' => $promo_data[0]->promo_type,
                                'total_cost' => $netTotal
                            ];
                              array_push($product_descount, $disProduct);
                              break;
                          case in_array($value['product_data']->id, $req_prod):
                              // echo '2nd array condition for req_purchase prod in categ<br>';
                              $productPrice = $value['product_data']->price;
                              $discount = $promo_data[0]->discount_value;
                              $per_price = $discount * ($productPrice / 100);
                              $netTotal = $productPrice -  $promo_data[0]->discount_value;
                              // echo $netTotal . '-----net_total <br> ------------ <br>';
                              $value['product_data']->price = $netTotal;
                              $disProduct = [
                                'id' => $value['product_data']->id,
                                'discounted_price' => $discount,
                                'percent_price' => $per_price,
                                'promo_type' => $promo_data[0]->promo_type,
                                'total_cost' => $netTotal
                            ];
                              array_push($product_descount, $disProduct);
                              break;
                          case in_array($value['product_data']->category, $req_cat):
                              // echo '2nd array condition for req_purchase cat in categ<br>';
                              $productPrice = $value['product_data']->price;
                              $discount = $promo_data[0]->discount_value;
                              $per_price = $discount * ($productPrice / 100);
                              $netTotal = $productPrice - $promo_data[0]->discount_value;
                              // echo $netTotal . '-----net_total <br> ------------ <br>';
                              $value['product_data']->price = $netTotal;
                              $disProduct = [
                                'id' => $value['product_data']->id,
                                'discounted_price' => $discount,
                                'percent_price' => $per_price,
                                'promo_type' => $promo_data[0]->promo_type,
                                'total_cost' => $netTotal
                            ];
                              array_push($product_descount, $disProduct);
                              break;
                      }
                    }
                      // echo ' tama ni<br> ------------ <br>';
                      // $totalVal += $value['product_data']->price;
                  } elseif ($promo_data[0]->require_purchase == "none") {
                    $discount = 0;  
                    if(!in_array($value['product_data']->id, $sale_id)){
                    if (in_array($value['product_data']->category, $promo_cat)) {
                          // echo 'in array <br>';
                          $productPrice = $value['product_data']->price;
                          $discount = $promo_data[0]->discount_value;
                          $per_price = $discount * ($productPrice / 100);
                          $netTotal = $productPrice  - $promo_data[0]->discount_value;
                          // echo $netTotal . '----net_total <br>------------ <br>';
                          $value['product_data']->price = $netTotal;
                          $disProduct = [
                            'id' => $value['product_data']->id,
                            'discounted_price' => $discount,
                            'percent_price' => $per_price,
                            'promo_type' => $promo_data[0]->promo_type,
                            'total_cost' => $netTotal
                        ];
                        // echo  'mao ni'.$disProduct;
                        array_push($product_descount, $disProduct);
                      }  
                    }      
                  }
                  $distotal += $discount;
                  $totalVal += $value['product_data']->price;
              }
              // echo 'Total Value: ' . $totalVal;
          }
           //promo all fix off
           if ($promo_data[0]->promo_product == 'promo_products_all') {
            $totalVal = 0;
            $distotal = 0;
            foreach ($cart_products as $value) {
                // echo $value['product_data']->price . '<br>';
                // echo $value['product_data']->id . '<br>---';
                if(!in_array($value['product_data']->id, $sale_id)){ 
                if (isset($value['product_data'])) {
                    // echo 'in array prod_all<br>';
                    $discount = 0;
                    $productPrice = $value['product_data']->price;
                    $discount = $promo_data[0]->discount_value;
                    $per_price = $discount * ($productPrice / 100);
                    $netTotal = $productPrice  - $promo_data[0]->discount_value;
                    // echo $netTotal . '----net_total <br>------------ <br>';
                    $value['product_data']->price = $netTotal;
                    $disProduct = [
                      'id' => $value['product_data']->id,
                      'discounted_price' => $discount,
                      'percent_price' => $per_price,
                      'promo_type' => $promo_data[0]->promo_type,
                      'total_cost' => $netTotal
                  ];
                  // echo  'mao ni'.$disProduct;
                  array_push($product_descount, $disProduct);
                }
              }
                $distotal += $discount;
                $totalVal += $value['product_data']->price;
            }
            // echo 'Total Value: ' . $totalVal;
        }
          break;
        case "sale_price":
          //promo spec prod sale price
          if($promo_data[0]->promo_product == "promo_products_specific"){
            $totalVal = 0;
            $distotal = 0;
            $promo_prod = explode(',' , $promo_data[0]->discounted_specific_product);
    
            foreach($cart_products as $value){
              // echo $value['product_data']->price.'---price<br>';
              // echo $value['product_data']->id.'---id<br>';
              
              if($promo_data[0]->require_purchase == 1){
                $req_prod = explode(',' , $promo_data[0]->required_product_id);
                $req_cat = explode(',' , $promo_data[0]->required_category_id);
                $discount = 0;
                if(!in_array($value['product_data']->id, $sale_id)){
                switch(true) {
                  case in_array($value['product_data']->id, $promo_prod):
                    // echo '1st array <br>';
                    $productPrice = $promo_data[0]->discount_value;
                    $netTotal = $promo_data[0]->discount_value;
                    // echo $netTotal.'-----net_total <br> ------------ <br>';
                    $value['product_data']->price = $netTotal;
                    $disProduct = [
                      'id' => $value['product_data']->id,
                      'discounted_price' => $discount,
                      'promo_type' => $promo_data[0]->promo_type,
                      'promo_type' => $promo_data[0]->promo_type,
                      'total_cost' => $netTotal
                  ];
                    array_push($product_descount, $disProduct);
                    break; 
                  case in_array($value['product_data']->id, $req_prod):
                    // echo '2nd array condition for req_purchase prod<br>';
                    $productPrice = $promo_data[0]->discount_value;
                    $netTotal = $promo_data[0]->discount_value;
                    // echo $netTotal.'----net_total <br> ------------ <br>';
                    $value['product_data']->price = $netTotal;
                    $disProduct = [
                      'id' => $value['product_data']->id,
                      'discounted_price' => $discount,
                      'promo_type' => $promo_data[0]->promo_type,
                      'total_cost' => $netTotal
                  ];
                    array_push($product_descount, $disProduct);
                    break;
                  case in_array($value['product_data']->category, $req_cat):
                    // echo '2nd array condition for req_purchase cat<br>';
                    $productPrice = $promo_data[0]->discount_value;
                    $netTotal = $promo_data[0]->discount_value;
                    // echo $netTotal.'-----net_total <br> ------------ <br>';
                    $value['product_data']->price = $netTotal;
                    $disProduct = [
                      'id' => $value['product_data']->id,
                      'discounted_price' => $discount,
                      'promo_type' => $promo_data[0]->promo_type,
                      'total_cost' => $netTotal
                  ];
                    array_push($product_descount, $disProduct);
                    break;
                }
              }
              } elseif($promo_data[0]->require_purchase == "none"){
                $discount = 0;
                if(!in_array($value['product_data']->id, $sale_id)){
                if(in_array($value['product_data']->id, $promo_prod)){
                  // echo '1st array for none<br>';
                  $productPrice = $promo_data[0]->discount_value;
                  $netTotal = $promo_data[0]->discount_value;
                  // echo $netTotal.'-----net_total <br> ------------ <br>';
                  $value['product_data']->price = $netTotal;
                  $disProduct = [
                    'id' => $value['product_data']->id,
                    'discounted_price' => $discount,
                    'promo_type' => $promo_data[0]->promo_type,
                    'total_cost' => $netTotal
                ];
                // echo  'mao ni'.$disProduct;
                array_push($product_descount, $disProduct);
                }
              }
              }
              // echo' tama ni<br> ------------ <br>';
              $distotal += $discount;
              $totalVal += $value['product_data']->price;
            }
            // echo 'Total Value: '.$totalVal;
            // print_r($cart_products);
          }
            //promo cat prod sale price
            if ($promo_data[0]->promo_product == 'promo_products_cat') {
              $promo_cat = explode(',', $promo_data[0]->discounted_category_id);
              $totalVal = 0;
              $distotal = 0;
              // print_r($promo_cat);
              foreach ($cart_products as $value) {
                  // echo $value['product_data']->price . '<br>';
                  // echo $value['product_data']->category . '--cat_id<br>---';
                  if ($promo_data[0]->require_purchase == 1) {
                      $req_prod = explode(',', $promo_data[0]->required_product_id);
                      $req_cat = explode(',', $promo_data[0]->required_category_id);
                      $discount = 0;
                      if(!in_array($value['product_data']->id, $sale_id)){
                      switch (true) {
                          case in_array($value['product_data']->category, $promo_cat):
                              // echo 'in array <br>';
                              $productPrice = $promo_data[0]->discount_value;
                              $netTotal = $promo_data[0]->discount_value;
                              // echo $netTotal . '-----net_total <br> ------------ <br>';
                              $value['product_data']->price = $netTotal;
                              $disProduct = [
                                'id' => $value['product_data']->id,
                                'discounted_price' => $discount,
                                'promo_type' => $promo_data[0]->promo_type,
                                'total_cost' => $netTotal
                            ];
                              array_push($product_descount, $disProduct);
                              break;
                          case in_array($value['product_data']->id, $req_prod):
                              // echo '2nd array condition for req_purchase prod in categ<br>';
                              $productPrice = $promo_data[0]->discount_value;
                              $netTotal = $promo_data[0]->discount_value;
                              // echo $netTotal . '-----net_total <br> ------------ <br>';
                              $value['product_data']->price = $netTotal;
                              $disProduct = [
                                'id' => $value['product_data']->id,
                                'discounted_price' => $discount,
                                'promo_type' => $promo_data[0]->promo_type,
                                'total_cost' => $netTotal
                            ];
                              array_push($product_descount, $disProduct);
                              break;
                          case in_array($value['product_data']->category, $req_cat):
                              // echo '2nd array condition for req_purchase cat in categ<br>';
                              $productPrice = $promo_data[0]->discount_value;
                              $netTotal = $promo_data[0]->discount_value;
                              // echo $netTotal . '-----net_total <br> ------------ <br>';
                              $value['product_data']->price = $netTotal;
                              $disProduct = [
                                'id' => $value['product_data']->id,
                                'discounted_price' => $discount,
                                'promo_type' => $promo_data[0]->promo_type,
                                'total_cost' => $netTotal
                            ];
                              array_push($product_descount, $disProduct);
                              break;
                          default:
                              break;
                      }
                    }
                      // echo ' tama ni<br> ------------ <br>';
                      $totalVal += $value['product_data']->price;
                  } elseif ($promo_data[0]->require_purchase == "none") {
                    $discount = 0; 
                    if(!in_array($value['product_data']->id, $sale_id)){
                    if (in_array($value['product_data']->category, $promo_cat)) {
                          // echo 'in array <br>';
                          $productPrice = $promo_data[0]->discount_value;
                          $netTotal = $promo_data[0]->discount_value;
                          // echo $netTotal . '----net_total <br>------------ <br>';
                          $value['product_data']->price = $netTotal;
                          $disProduct = [
                            'id' => $value['product_data']->id,
                            'discounted_price' => $discount,
                            'promo_type' => $promo_data[0]->promo_type,
                            'total_cost' => $netTotal
                        ];
                        // echo  'mao ni'.$disProduct;
                        array_push($product_descount, $disProduct);
                      }
                    }
                      $distotal += $discount;
                      $totalVal += $value['product_data']->price;
                  }
              }
              // echo 'Total Value: ' . $totalVal;
          }
           //promo all sale price
           if ($promo_data[0]->promo_product == 'promo_products_all') {
            $totalVal = 0;
            $distotal = 0;
            foreach ($cart_products as $value) {
                // echo $value['product_data']->price . '<br>';
                // echo $value['product_data']->id . '<br>---';
                if(!in_array($value['product_data']->id, $sale_id)){
                if (isset($value['product_data'])) {
                    // echo 'in array prod_all<br>'; 
                    $discount = 0;
                    $productPrice = $promo_data[0]->discount_value;
                    $netTotal = $promo_data[0]->discount_value;
                    // echo $netTotal . '----net_total <br>------------ <br>';
                    $value['product_data']->price = $netTotal;
                    $disProduct = [
                      'id' => $value['product_data']->id,
                      'discounted_price' => $discount,
                      'promo_type' => $promo_data[0]->promo_type,
                      'total_cost' => $netTotal
                  ];
                  // echo  'mao ni'.$disProduct;
                  array_push($product_descount, $disProduct);
                }
              }
                $distotal += $discount;
                $totalVal += $value['product_data']->price;
            }
            // echo 'Total Value: ' . $totalVal;
        }
          break;
        case "bxgx":
          //promo spec prod buy 1 get 1
               if ($promo_data[0]->promo_product == 'promo_products_specific') {
                $distotal = 0;
                $promo_prod = explode(',' , $promo_data[0]->discounted_specific_product);
                foreach ($cart_products as $key => $value) {
                    // echo $value['product_data']->price . '<br>';
                    // echo $value['product_data']->id . '<br>---';
                    // if(!in_array($value['product_data']->id, $sale_id)){
                    if ($key === 1) {

                    $discount = 0;    
                    $netTotal = 0; 
                        
                        // echo $netTotal . '----net_total <br>------------ <br>';
                        $value['product_data']->price = $netTotal; 
                        $disProduct = [
                          'id' => $value['product_data']->id,
                          'discounted_price' => $discount,
                          'promo_type' => $promo_data[0]->promo_type,
                          'total_cost' => $netTotal
                      ];
                      // echo  'mao ni'.$disProduct;
                      array_push($product_descount, $disProduct);
                    } 
                    $distotal += 0;
                  } 
                // }
                // echo 'Total Value: ' . $totalVal;
                //print_r($cart_products);
            }
             //promo cat prod buy 1 get 1
             if ($promo_data[0]->promo_product == 'promo_products_cat') {
              $distotal = 0;
              foreach ($cart_products as $key => $value) {
                  // echo $value['product_data']->price . '<br>';
                  // echo $value['product_data']->id . '<br>---';
                  if(!in_array($value['product_data']->id, $sale_id)){
                  if ($key === 1) {
                      // echo ' 2nd in array prod_all<br>';
                      $discount = 0; 
                      $netTotal = 0; 
                      // echo $netTotal . '----net_total <br>------------ <br>';
                      $value['product_data']->price = $netTotal; 
                      $disProduct = [
                        'id' => $value['product_data']->id,
                        'discounted_price' => $discount,
                        'promo_type' => $promo_data[0]->promo_type,
                        'total_cost' => $netTotal
                    ];
                    // echo  'mao ni'.$disProduct;
                    array_push($product_descount, $disProduct);
                  } 
                  $distotal += $value['product_data']->price;
                }
                  // $totalVal += $value['product_data']->price;
              }
              // echo 'Total Value: ' . $totalVal;
              // print_r($cart_products);
          }
          //promo all buy 1 get 1
          if ($promo_data[0]->promo_product == 'promo_products_all') {
            $distotal = 0;
            foreach ($cart_products as $key => $value) {
                // echo $value['product_data']->price . '<br>';
                // echo $value['product_data']->id . '<br>---';
                if(!in_array($value['product_data']->id, $sale_id)){
                if ($key === 1) {
                    // echo ' 2nd in array prod_all<br>';
                    $discount = 0; 
                    $netTotal = 0; 
                    // echo $netTotal . '----net_total <br>------------ <br>';
                    $value['product_data']->price = $netTotal; 
                    $disProduct = [
                      'id' => $value['product_data']->id,
                      'discounted_price' => $discount,
                      'promo_type' => $promo_data[0]->promo_type,
                      'total_cost' => $netTotal
                  ];
                  // echo  'mao ni'.$disProduct;
                  array_push($product_descount, $disProduct);
                } 
                $distotal += $value['product_data']->price;
              }
                // $totalVal += $value['product_data']->price;
            }
            // echo 'Total Value: ' . $totalVal;
            // print_r($cart_products);
        }
          break; 
        case "bundle":
          //promo spec prod bundle
          if($promo_data[0]->promo_product == "promo_products_specific"){
            $totalVal = 0;
           
            $promo_prod = explode(',' , $promo_data[0]->discounted_specific_product);
           
            foreach($cart_products as $value){
              // echo $value['product_data']->price.'---price<br>';
              // echo $value['product_data']->id.'---id<br>';
              
              if($promo_data[0]->require_purchase == 1){
                $req_prod = explode(',' , $promo_data[0]->required_product_id);
                $req_cat = explode(',' , $promo_data[0]->required_category_id);
                $productPrice = 0;
                switch(true) {
                  case in_array($value['product_data']->id, $promo_prod):
                    // echo '1st array <br>';
                    $productPrice += $value['product_data']->price;
                    $netTotal = $productPrice -  $promo_data[0]->discount_value;
                    // echo $netTotal.'-----net_total <br> ------------ <br>';
                    $value['product_data']->price = $netTotal;
                    break; 
                  case in_array($value['product_data']->id, $req_prod):
                    // echo '2nd array condition for req_purchase prod<br>';
                    $productPrice += $value['product_data']->price;
                    $netTotal = $productPrice -  $promo_data[0]->discount_value;
                    // echo $netTotal.'----net_total <br> ------------ <br>';
                    $value['product_data']->price = $netTotal;
                    break;
                  case in_array($value['product_data']->category, $req_cat):
                    // echo '2nd array condition for req_purchase cat<br>';
                    $productPrice += $value['product_data']->price;
                    $netTotal = $productPrice - $promo_data[0]->discount_value;
                    // echo $netTotal.'-----net_total <br> ------------ <br>';
                    $value['product_data']->price = $netTotal;
                    break;
                }
              } elseif($promo_data[0]->require_purchase == "none"){
                $productPrice = 0;
                if(in_array($value['product_data']->id, $promo_prod)){
                  // echo '1st array for none<br>';
                  $productPrice += $value['product_data']->price;
                  $netTotal = $productPrice -  $promo_data[0]->discount_value;
                  // echo $netTotal.'-----net_total <br> ------------ <br>';
                  $value['product_data']->price = $netTotal;
                }
              }
              // echo' tama ni<br> ------------ <br>';
              $totalVal += $value['product_data']->price;
            }
            // echo 'Total Value: '.$totalVal;
          }
      //promo cat prod bundle
      if ($promo_data[0]->promo_product == 'promo_products_cat') {
        $promo_cat = explode(',', $promo_data[0]->discounted_category_id);
        $totalVal = 0;
        // print_r($promo_cat);
        foreach ($cart_products as $value) {
            // echo $value['product_data']->price . '<br>';
            // echo $value['product_data']->category . '<br>---';
            if ($promo_data[0]->require_purchase == 1) {
                $req_prod = explode(',', $promo_data[0]->required_product_id);
                $req_cat = explode(',', $promo_data[0]->required_category_id);
                $productPrice = 0;
                switch (true) {
                    case in_array($value['product_data']->category, $promo_cat):
                        // echo 'in array <br>';
                        $productPrice += $value['product_data']->price;
                        $netTotal = $productPrice  - $promo_data[0]->discount_value;
                        // echo $netTotal . '-----net_total <br> ------------ <br>';
                        $value['product_data']->price = $netTotal;
                        break;
                    case in_array($value['product_data']->id, $req_prod):
                        // echo '2nd array condition for req_purchase prod in categ<br>';
                        $productPrice += $value['product_data']->price;
                        $netTotal = $productPrice -  $promo_data[0]->discount_value;
                        // echo $netTotal . '-----net_total <br> ------------ <br>';
                        $value['product_data']->price = $netTotal;
                        break;
                    case in_array($value['product_data']->category, $req_cat):
                        echo '2nd array condition for req_purchase cat in categ<br>';
                        $productPrice += $value['product_data']->price;
                        $netTotal = $productPrice - $promo_data[0]->discount_value;
                        // echo $netTotal . '-----net_total <br> ------------ <br>';
                        $value['product_data']->price = $netTotal;
                        break;
                    default:
                        break;
                }
                // echo ' tama ni<br> ------------ <br>';
                $totalVal += $value['product_data']->price;
            } elseif ($promo_data[0]->require_purchase == "none") {
                $productPrice = 0;
                if (in_array($value['product_data']->category, $promo_cat)) {
                    // echo 'in array <br>';
                    $productPrice += $value['product_data']->price;
                    $netTotal = $productPrice  - $promo_data[0]->discount_value;
                    // echo $netTotal . '----net_total <br>------------ <br>';
                    $value['product_data']->price = $netTotal;
                }
                $totalVal += $value['product_data']->price;
            }
        }
        // echo 'Total Value: ' . $totalVal;
    }
          //promo all bundle
          if ($promo_data[0]->promo_product == 'promo_products_all') {
            $totalPrice = 0;
            foreach ($cart_products as $value) {
              // echo $value['product_data']->price . '<br>';
              // echo $value['product_data']->id . '<br>---';
              if (isset($value['product_data'])) {
                // echo 'in array prod_all<br>';
                $productPrice = $value['product_data']->price;
                $netTotal = $productPrice  - $promo_data[0]->discount_value;
                // echo $netTotal . '----net_total <br>------------ <br>';
                // $value['product_data']->price = $productPrice;
                
            }   
              $totalPrice += $value['product_data']->price;       
              $totalVal = $totalPrice - $promo_data[0]->discount_value;
          }
            // echo 'Total Value: ' . $totalVal;
            // print_r($cart_products);
        }
          break; 
      }
      $this->data['totalVal'] = $distotal;
      $this->data['product_descount'] = $product_descount;
      $session->discountSub = $this->data['product_descount'];
      $session->totalSub = $this->data['totalVal'];

      // return redirect('/cart/checkout');
      $data_arr = array("success" => TRUE,"message" => 'Promo Code Save!');
    }else {
      // echo 'Invalid promo code. Please try again.';
        //  session()->setFlashdata('error', 'Invalid promo code.');
        $validationError = json_encode($validation->getErrors());
        $data_arr = array("success" => FALSE,"message" => 'Invalid promo code.');
  }
}else{
    $data_arr = array("success" => FALSE,"message" => 'No posted data!');
  }
  die(json_encode($data_arr));
}
}


