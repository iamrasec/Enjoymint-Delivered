<?php

namespace App\Controllers\API;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use Exception;
use ReflectionException;

class Orders extends ResourceController
{
  use ResponseTrait;
    public function __construct() 
    {
      helper(['jwt', 'edimage', 'date']);

      $this->data = [];
      $this->role = session()->get('role');
      $this->isLoggedIn = session()->get('isLoggedIn');
      $this->guid = session()->get('guid');
      $this->drivers_model = model('Drivers');
      $this->order_model = model('CheckoutModel');
      $this->order_products = model('OrderProductsModel');
      $this->customerverification_model = model('VerificationModel');
      $this->image_model = model('ImageModel');
      $this->product_model = model('ProductModel');
      $this->user_model = model('UserModel');
      $this->promo_model = model('PromoModel');

      $this->tax_rate = 1.35;  // 35%

      

      helper(['form', 'functions']); // load helpers
      addJSONResponseHeader(); // set response header to json
      $this->sender_email = getenv('SMTP_EMAIL_USER');
       // $this->user_email = 'linal1991@rhyta.com';
       $this->user_email = 'cesaryamutajr+111@gmail.com';
      
    }


  /**
   * This function will save a drivers and update order into the server
   * 
   * @return object a success indicator and the message
  */
  public function add()
  {
    
    if($this->request->getPost()) {
      
        $drivers = [
            'name' => $this->request->getPost('driver'),
          ];
          $this->drivers_model->save($drivers);
    }
    
  }

  public function add_product()
  {
    // getProductData
    $post = $this->request->getPost();

    $order_pids = (isset($post['order_pids'])) ? $post['order_pids'] : [];
    // $order_pids[] = $post['pid'];

    // print_r($order_pids);die();

    // Check if selected product id is not already added in edit order but not yet committed to database.
    if(in_array($post['pid'], $order_pids)) {
      die(json_encode(array("success" => FALSE,"message" => 'Product already in cart.')));
    }

    // Check if product is already in cart
    $check_product = $this->order_products->where('order_id', $post['oid'])->where('product_id', $post['pid'])->get()->getRow();

    if(empty($check_product)) {
      $db = \Config\Database::connect();
      $builder = $db->table('v_all_products');
      $data = $builder->where('id', $post['pid'])->get()->getRow();

      $imageIds = [];
      if($data->images) {
          $imageIds = explode(',',$data->images);
          $data->images = $this->image_model->whereIn('id', $imageIds)->get()->getResult();
      }

      // print_r($data);die();

      $return = '<tr class="pid-'.$data->id.' border"><td><div class="row product-wrap d-flex py-3">';
      $return .= '<div class="col-12 col-md-2 col-xs-12 product-img"><img src="'. base_url('/products/images/'. $data->images[0]->filename) .'" style="width: 100px;"></div>';
      $return .= '<div class="col-12 col-md-8 col-xs-12 product-details"><h6 class="product-title"><a href="'. base_url('/products/'. $data->url) .'">'. $data->name .'</a></h6>';
      $return .= '<div class="text-sm mb-3">';
      if($data->strain_name != "") {
        $return .= '<span class="badge text-bg-warning me-3">'. $data->strain_name .'</span>';
      }
      if($data->thc_value != 0 || $data->thc_value != "") {
        $return .= '<span class="badge text-bg-dark ms-3">THC '. $data->thc_value . (($data->thc_unit == 'pct') ? '%' : $data->thc_unit) .'</span>';
      }
      $return .= '</div>';
      $return .= '<div class="product-qty"><span>QTY: </span>';
      $return .= '<input type="number" name="cart['.$data->id.'][qty]" class="product-'.$data->id.'-qty" min="1" max="100" value="1" data-pid="'.$data->id.'" data-unit-price="'.$data->price.'"></div></div>';
      $return .= '<div class="col-12 col-md-2 col-xs-12 price text-right pe-4">';
      $return .= '<input type="hidden" class="product-total-price product-'.$data->id.'-total-price" value="'.$data->price.'">';
      $return .= '<strong class="total-price-display">$'.$data->price.'</strong>';
      $return .= '<div class="mt-3 d-flex align-items-end align-content-end"><a href="#" class="remove-item ms-auto" data-pid="'.$data->id.'"><i class="fas fa-trash" aria-hidden="true"></i></a></div>';
      $return .= '</div>';

      $return .= '</div></td></tr>';

      die(json_encode(array("success" => TRUE,"message" => 'Product Added to Order.  Please click on Save to commit changes.', "data" => $data, "append_data" => $return)));
    }
    else {
      die(json_encode(array("success" => FALSE,"message" => 'Product already in cart.')));
    }
  }

  public function save_edit()
  {
    $session = session();
    $priceTotal = $session->get('totalSub');
    $sender_email = $this->sender_email;
    $user_email = session()->get('email');
    //$user = $this->user_model->where('guid',$this->guid)->select('email')->first();
    $post = $this->request->getPost();

    $cart_key = $post['order_key'];
    $cart_id = $post['oid'];
    $order_pids = $post['order_pids'];
    $to_save = $post['order_data'];

    // echo "<pre>".print_r($post, 1)."</pre>";die();
    // echo "<pre>".print_r($to_save, 1)."</pre>";die();
    // $product_data = $this->product_model->getProductData($product->pid);

    $get_order_data = $this->order_model->where('id', $post['oid'])->first();

    // echo "<pre>".print_r($get_order_data, 1)."</pre>";die();

    $get_saved_cart = $this->order_products->select('product_id, qty')->where('order_id', $post['oid'])->get()->getResult();

    // echo "<pre>".print_r($get_saved_cart, 1)."</pre>";die();

    $new_subtotal = 0;
    $new_pricesub = 0;

    foreach($to_save as $to_save_data) {
      $existing_product = false; 
      foreach($get_saved_cart as $saved_cart) {
        if($to_save_data['id'] == $saved_cart->product_id) {
          $existing_product = true;

          if($to_save_data['qty'] != $saved_cart->qty) {
            $product_cost_total = number_format($to_save_data['qty'] * $to_save_data['price'], 2, '.', '');

            if(!empty($priceTotal)){
              $new_pricesub += $product_cost_total;
              $new_subtotal = $new_pricesub - $priceTotal;
            }else{
              $new_subtotal += $product_cost_total;
            }

            $update_cart_value = [
              'qty' => $to_save_data['qty'],
              'total' => $product_cost_total,
            ];
  
            $this->order_products->where('product_id', $saved_cart->product_id)->set($update_cart_value)->update();
          }
        }
      }

      if($existing_product == false) {
        $new_product_cost_total = number_format($to_save_data['price'] * $to_save_data['qty'], 2, '.', '');
     
        if(!empty($priceTotal)){
          $new_pricesub += $new_product_cost_total;
          $new_subtotal = $new_pricesub - $priceTotal;
        }else{
          $new_subtotal += $new_product_cost_total;
        }

        $to_save_new = [
          'order_id' => $post['oid'],
          'product_id' => $to_save_data['id'],
          'product_name' => $to_save_data['name'],
          'qty' => $to_save_data['qty'],
          'unit_price' => $to_save_data['price'],
          'is_sale' => 0,
          'regular_price' => $to_save_data['price'],
          'total' => $new_product_cost_total
        ];

        $this->order_products->save($to_save_new);
        // $this->send_order_notification($sender_email, $user_email);
      }
    }

    $tax_cost = $new_subtotal * ($this->tax_rate - 1);
    $total_cost = $new_subtotal * $this->tax_rate;

    $update_order = [
      'address' => $post['del_address'],
      'payment_method' => $post['pay_method'],
      'order_notes' => $post['notes'],
      'delivery_type' => $post['del_type'],
      'delivery_schedule' => $post['del_date'],
      'delivery_time' => $post['del_time'],
      'promo_code' => $post['promo_code'],
    ];

    if($new_subtotal > 0) {
      $update_order['subtotal'] = $new_subtotal;
      $update_order['tax'] = $tax_cost;
      $update_order['total'] = $total_cost;
    }

    $update_promocode = $update_order['promo_code'];
   // $session->promo_edit = $order_products;
    
    $this->order_model->where('id', $post['oid'])->set($update_order)->update();

    $this->send_order_notification($post['oid'], $update_order, $to_save);

    $this->data['pricesubtotal'] = $priceTotal;
    
    die(json_encode(array("success" => TRUE,"message" => 'Order Updated Successfully')));
  }
  

  public function send_order_notification($oid, $order, $products)
  {
    // d($_SERVER);
    
    $get_order_data = $this->order_model->where('id', $oid)->first();

    // echo "<pre>".print_r($get_order_data, 1)."</pre>";die();

    $sender_email = $this->sender_email;
    // $user_email = $this->user_email;
    $user_email = $get_order_data['email'];

    $email = \Config\Services::email();
		$email->setFrom($sender_email);
		$email->setTo($user_email);
		$email->setSubject('Order has been Edited');

		// echo "<pre>".print_r($products, 1)."</pre>";die();

    for($i = 0; $i < count($products); $i++) {
      // $products[$i]['images'] = getProductImage($products[$i]['product_id']);
      $products[$i]['images'] = getProductImage($products[$i]['id']);
    }

    $order_data = ["order_data" => $get_order_data, "order_products" => $products, "site_logo" => 'http://fuegonetworxservices.com/assets/img/Enjoymint-Logo-Landscape-White-2.png'];

    // echo "<pre>".print_r($sender_email, 1)."</pre>";
    // echo "<pre>".print_r($user_email, 1)."</pre>";
    // echo "<pre>".print_r($order_data, 1)."</pre>"; die();

		$template = view('email/edited_order_notification', $order_data);

    $email->setMessage($template);
    $email->setNewline("\r\n");

		if($email->send()) {
      return true;
		}
    trace();
  }

  public function complete() 
  {
    $post = $this->request->getPost();

    // print_r($post['pid']);die();

    $this->order_model->where('id', $post['pid'])->set('status', 2)->update();

    die(json_encode(array("success" => TRUE,"message" => 'Order Completed', "id" => $post['pid'])));
  }

  public function sendDeliveredNotification(){

  }

  public function list_all()
  {
    $post = $this->request->getPost();

    // 1st query for counting data
    $this->order_model->select("id");

    if(isset($post['search']['value']) && !empty($post['search']['value'])) {
      $search_value = strtolower($post['search']['value']);
      $this->order_model->like("LOWER(CONCAT(first_name, ' ', last_name))", $search_value);
      $this->order_model->orLike("LOWER(address)", $search_value);
    }

    // $this->order_model->whereIn("status", [0,1]);
    $count_all = $this->order_model->countAllResults();

    // 2nd Query that gets all the data
    $this->order_model->select("id, customer_id, CONCAT(first_name, ' ', last_name) AS customer_name, address, (SELECT COUNT(id) FROM order_products WHERE order_id = orders.id) AS product_count, total, created, status, delivery_time, delivery_schedule, delivery_type, order_notes");  // <-- working query

    // $this->order_model->select("orders.id, CONCAT(orders.first_name, ' ', orders.last_name) AS customer_name, orders.address, (SELECT COUNT(id) FROM order_products WHERE order_id = orders.id) AS product_count, orders.total, orders.created, orders.status, orders.delivery_schedule, customer_verification.image_validID, customer_verification.image_profile, customer_verification.image_MMIC, customer_verification.status");

    // $this->order_model->join('customer_verification', 'orders.customer_id = customer_verification.user_id', 'left');

    if(isset($post['search']['value']) && !empty($post['search']['value'])) {
      $search_value = strtolower($post['search']['value']);
      $this->order_model->like("LOWER(CONCAT(first_name, ' ', last_name))", $search_value);
      $this->order_model->orLike("LOWER(address)", $search_value);
    }

    // $this->order_model->whereIn('customer_verification.status', [0,1]);

    // $this->order_model->whereIn("status", [0,1]);

    $this->order_model->orderBy("created DESC");

    if(isset($post['start']) && isset($post['length'])) {
      $orders = $this->order_model->get($post['length'], $post['start'])->getResult();
    }
    else {
      $orders = $this->order_model->get()->getResult();
    }

    for($i = 0; $i < count($orders); $i++) {
      // Get all products from order
      $products = $this->order_products->where('order_id', $orders[$i]->id)->get()->getResult();
      $orders[$i]->products = $products;

      // Get customer verification
      $customer_ids = $this->customerverification_model->where('user_id', $orders[$i]->customer_id)->whereIn('status', [0,1])->get()->getResult();

      if(!empty($customer_ids)) {
        foreach($customer_ids as $customer_id) {
          if(!empty($customer_id->image_validID)) {
            $valid_id = $this->image_model->where('id', $customer_id->image_validID)->get()->getResult();
          }
          if(!empty($customer_id->image_profile)) {
            $proile_img = $this->image_model->where('id', $customer_id->image_profile)->get()->getResult();
          }
          if(!empty($customer_id->image_MMIC)) {
            $mmic = $this->image_model->where('id', $customer_id->image_MMIC)->get()->getResult();
          }
        }
        
        // $orders[$i]->customer_ids = $customer_ids;

        // <img class="id_verification_image" src="'.base_url('users/verification/'.$product_arr['validID'][0]->filename).'" style="width:120px; width: 90px;">
        $orders[$i]->customer_ids = [
          'status' => $customer_id->status,
          'user_id' => $customer_id->user_id,
          'valid_id' => (isset($valid_id)) ? '<img class="customer-valid-id" src="'.base_url('users/verification/'.$valid_id[0]->filename).'"style="width:120px; width: 90px;">' : '',
          'profile_img' => (isset($proile_img)) ? '<img class="customer-valid-id" src="'.base_url('users/verification/'.$proile_img[0]->filename).'"style="width:120px; width: 90px;">' : '',
          'mmic' => (isset($mmic)) ? '<img class="customer-valid-id" src="'.base_url('users/verification/'.$mmic[0]->filename).'"style="width:120px; width: 90px;">' : '',
        ];
      }
      else {
        $orders[$i]->customer_ids = [];
      }
    }

    $output = array(
      "draw" => $post['draw'],
      "recordsTotal" => $count_all,
      "recordsFiltered" => $count_all,
      "data" => $orders,
    );

    echo json_encode($output);
    exit();
  }

  public function list_pending()
  {
    $post = $this->request->getPost();

    // 1st query for counting data
    $this->order_model->select("id");

    if(isset($post['search']['value']) && !empty($post['search']['value'])) {
      $search_value = strtolower($post['search']['value']);
      $this->order_model->like("LOWER(CONCAT(first_name, ' ', last_name))", $search_value);
      $this->order_model->orLike("LOWER(address)", $search_value);
    }

    $this->order_model->whereIn("status", [0,1]);
    $count_all = $this->order_model->countAllResults();

    // 2nd Query that gets all the data
    $this->order_model->select("id, customer_id, CONCAT(first_name, ' ', last_name) AS customer_name, address, (SELECT COUNT(id) FROM order_products WHERE order_id = orders.id) AS product_count, total, created, status, delivery_time, delivery_schedule, delivery_type, order_notes");

    if(isset($post['search']['value']) && !empty($post['search']['value'])) {
      $search_value = strtolower($post['search']['value']);
      $this->order_model->like("LOWER(CONCAT(first_name, ' ', last_name))", $search_value);
      $this->order_model->orLike("LOWER(address)", $search_value);
    }

    $this->order_model->whereIn("status", [0,1]);
    $this->order_model->orderBy("created DESC");

    if(isset($post['start']) && isset($post['length'])) {
      $orders = $this->order_model->get($post['length'], $post['start'])->getResult();
    }
    else {
      $orders = $this->order_model->get()->getResult();
    }

    for($i = 0; $i < count($orders); $i++) {
      $products = $this->order_products->where('order_id', $orders[$i]->id)->get()->getResult();
      $orders[$i]->products = $products;
    }

    $output = array(
      "draw" => $post['draw'],
      "recordsTotal" => $count_all,
      "recordsFiltered" => count($orders),
      "data" => $orders,
    );

    echo json_encode($output);
    exit();
  }

  public function list_completed()
  {
    $post = $this->request->getPost();

    // 1st query for counting data
    $this->order_model->select("id");

    if(isset($post['search']['value']) && !empty($post['search']['value'])) {
      $search_value = strtolower($post['search']['value']);
      $this->order_model->like("LOWER(CONCAT(first_name, ' ', last_name))", $search_value);
      $this->order_model->orLike("LOWER(address)", $search_value);
    }

    $this->order_model->where("status", 2);
    $count_all = $this->order_model->countAllResults();

    // 2nd Query that gets all the data
    $this->order_model->select("id, customer_id, CONCAT(first_name, ' ', last_name) AS customer_name, address, (SELECT COUNT(id) FROM order_products WHERE order_id = orders.id) AS product_count, total, created, status, delivery_time, delivery_schedule, delivery_type, order_notes");

    if(isset($post['search']['value']) && !empty($post['search']['value'])) {
      $search_value = strtolower($post['search']['value']);
      $this->order_model->like("LOWER(CONCAT(first_name, ' ', last_name))", $search_value);
      $this->order_model->orLike("LOWER(address)", $search_value);
    }

    $this->order_model->where("status", 2);
    $this->order_model->orderBy("created DESC");

    if(isset($post['start']) && isset($post['length'])) {
      $orders = $this->order_model->get($post['length'], $post['start'])->getResult();
    }
    else {
      $orders = $this->order_model->get()->getResult();
    }

    for($i = 0; $i < count($orders); $i++) {
      $products = $this->order_products->where('order_id', $orders[$i]->id)->get()->getResult();
      $orders[$i]->products = $products;
    }

    $output = array(
      "draw" => $post['draw'],
      "recordsTotal" => $count_all,
      "recordsFiltered" => count($orders),
      "data" => $orders,
    );

    echo json_encode($output);
    exit();
  }

  public function list_cancelled()
  {
    $post = $this->request->getPost();

    // 1st query for counting data
    $this->order_model->select("id");

    if(isset($post['search']['value']) && !empty($post['search']['value'])) {
      $search_value = strtolower($post['search']['value']);
      $this->order_model->like("LOWER(CONCAT(first_name, ' ', last_name))", $search_value);
      $this->order_model->orLike("LOWER(address)", $search_value);
    }

    $this->order_model->where("status", 3);
    $count_all = $this->order_model->countAllResults();

    // 2nd Query that gets all the data
    $this->order_model->select("id, customer_id, CONCAT(first_name, ' ', last_name) AS customer_name, address, (SELECT COUNT(id) FROM order_products WHERE order_id = orders.id) AS product_count, total, created, status, delivery_time, delivery_schedule, delivery_type, order_notes");

    if(isset($post['search']['value']) && !empty($post['search']['value'])) {
      $search_value = strtolower($post['search']['value']);
      $this->order_model->like("LOWER(CONCAT(first_name, ' ', last_name))", $search_value);
      $this->order_model->orLike("LOWER(address)", $search_value);
    }

    $this->order_model->where("status", 3);
    $this->order_model->orderBy("created DESC");

    if(isset($post['start']) && isset($post['length'])) {
      $orders = $this->order_model->get($post['length'], $post['start'])->getResult();
    }
    else {
      $orders = $this->order_model->get()->getResult();
    }

    for($i = 0; $i < count($orders); $i++) {
      $products = $this->order_products->where('order_id', $orders[$i]->id)->get()->getResult();
      $orders[$i]->products = $products;
    }

    $output = array(
      "draw" => $post['draw'],
      "recordsTotal" => $count_all,
      "recordsFiltered" => count($orders),
      "data" => $orders,
    );

    echo json_encode($output);
    exit();
  }
  public function cancelled_product($pid){
    $this->order_model->update($pid, ['status' => 3]);
    die(json_encode(array("success" => TRUE,"message" => 'Product Delete!')));
  }

  public function promo_update(){
    $session = session();
    $update_code = $session->get('order_products');
    // $update_data = json_decode($update_code->product_data);

    foreach($update_code as $update){
      // initialize images

      $update_data [] = [
      'order_id' => $update->order_id,
      'product_id' => $update->product_id,
      'qty' => $update->qty,
      'unit_price' => $update->unit_price,
      'regular_price' => $update->regular_price,
      'product_data' => $update->product_data,
      ];
    }

    if($this->request->getPost()) {
      $validation =  \Config\Services::validation();

    $promo = $this->request->getVar('promo_code');
    $session->test_promo =  $promo;
    $this->data['prom_code'] = $promo;
    //$this->data['location_keyword'] = $this->location;    
      
    $promo_data = $this->promo_model->getPromo($promo);

    $product_descount = [];
   
    // $this->data['prom_data'] = $promo_data;         
    // $test = json_encode($promo_data);
    
    if(!empty($promo_data)) {
      switch($promo_data[0]->promo_type) {
        case "percent_off":
          //promo spec prod
          if($promo_data[0]->promo_product == "promo_products_specific"){
            $totalVal = 0;
            $distotal = 0;
            $promo_prod = explode(',' , $promo_data[0]->discounted_specific_product);
              // echo  $promo_data[0]->discounted_specific_product.'promo_prod';
            foreach($update_data as $value){
              // echo $value['product_data']->category.'category';
              // echo $value->product_id.'productPrice';
              // echo $promo_data[0]->discount_value.'discount';
              if($promo_data[0]->require_purchase == 1){
                $req_prod = explode(',' , $promo_data[0]->required_product_id);
                $req_cat = explode(',' , $promo_data[0]->required_category_id);
                $discount = 0;
                switch(true) {
                  case in_array($value['product_data']->id, $promo_prod):
                   
                    $productPrice = $value['product_data']->price;
                    $discount = $productPrice * ($promo_data[0]->discount_value / 100);
                    $netTotal = $productPrice -  $discount;
       
                    $value['product_data']->price = $netTotal;
                    $disProduct = [
                      'id' => $value['product_data']->id,
                      'discounted_price' => $discount,
                      'total_cost' => $netTotal
                  ];
                    array_push($product_descount, $disProduct);
                    break;
                  case in_array($value['product_data']->id, $req_prod):
                    
                    $productPrice = $value['product_data']->price;
                    $discount = $productPrice * ($promo_data[0]->discount_value / 100);
                    $netTotal = $productPrice -  $discount;
                 
                    $value['product_data']->price = $netTotal;
                    $disProduct = [
                      'id' => $value['product_data']->id,
                      'discounted_price' => $discount,
                      'total_cost' => $netTotal
                  ];
                    array_push($product_descount, $disProduct);
                    break;
                  case in_array($value['product_data']->category, $req_cat):
                    // echo '2nd array condition for req_purchase cat<br>';
                    $productPrice = $value['product_data']->price;
                    $discount = $productPrice * ($promo_data[0]->discount_value / 100);
                    $netTotal = $productPrice - $discount;
            
                    $value['product_data']->price = $netTotal;
                    $disProduct = [
                      'id' => $value['product_data']->id,
                      'discounted_price' => $discount,
                      'total_cost' => $netTotal
                  ];
                    array_push($product_descount, $disProduct);
                    break;
                }
              } elseif($promo_data[0]->require_purchase == "none"){
                $discount = 0;
                if(in_array($value['product_data']->id, $promo_prod)){            

                  $productPrice = $value['product_data']->price;
                  $discount = $productPrice * ($promo_data[0]->discount_value / 100);
                  $netTotal = $productPrice -  $discount;

                  // echo $productPrice.'productPrice';
                  // echo  $discount.'discount';
                  // echo $netTotal.'net_total';
                  $value['product_data']->price = $netTotal;         
                  
                  $disProduct = [
                    'id' => $value['product_data']->id,
                    'discounted_price' => $discount,
                    'total_cost' => $netTotal
                ];

                array_push($product_descount, $disProduct);
                }             
              }
              // echo' tama ni<br> ------------ <br>';
              $distotal += $discount;
              $totalVal += $value['product_data']->price;
            } 
         //     echo $distotal.'distotal';
            //  echo $totalVal.'test';
          }
            //promo cat prod
          if ($promo_data[0]->promo_product == 'promo_products_cat') {
            $promo_cat = explode(',', $promo_data[0]->discounted_category_id);
            $totalVal = 0;
            $distotal = 0;
            // print_r($promo_cat);
            foreach ($update_data as $value) {
                // echo $value['product_data']->price . '<br>';
                // echo $value['product_data']->category . '<br>---';
                if ($promo_data[0]->require_purchase == 1) {
                    $req_prod = explode(',', $promo_data[0]->required_product_id);
                    $req_cat = explode(',', $promo_data[0]->required_category_id);
                    $discount = 0;
                    switch (true) {
                        case in_array($value['product_data']->category, $promo_cat):
                            // echo 'in array <br>';
                            $productPrice = $value['product_data']->price;
                            $discount = $productPrice * ($promo_data[0]->discount_value / 100);
                            $netTotal = $productPrice  - $discount;
                            // echo $discount . ' <br>';
                            // echo $netTotal . ' <br> ------------ <br>';
                            $value['product_data']->price = $netTotal;
                            $disProduct = [
                              'id' => $value['product_data']->id,
                              'discounted_price' => $discount,
                              'total_cost' => $netTotal
                          ];
                            array_push($product_descount, $disProduct);
                            break;
                        case in_array($value['product_data']->id, $req_prod):
                            // echo '2nd array condition for req_purchase prod in categ<br>';
                            $productPrice = $value['product_data']->price;
                            $discount = $productPrice * ($promo_data[0]->discount_value / 100);
                            $netTotal = $productPrice -  $discount;
                            // echo $discount . ' <br>';
                            // echo $netTotal . ' <br> ------------ <br>';
                            $value['product_data']->price = $netTotal;
                            $disProduct = [
                              'id' => $value['product_data']->id,
                              'discounted_price' => $discount,
                              'total_cost' => $netTotal
                          ];
                            array_push($product_descount, $disProduct);
                            break;
                        case in_array($value['product_data']->category, $req_cat):
                            // echo '2nd array condition for req_purchase cat in categ<br>';
                            $productPrice = $value['product_data']->price;
                            $discount = $productPrice * ($promo_data[0]->discount_value / 100);
                            $netTotal = $productPrice - $discount;
                            // echo $discount . ' <br>';
                            // echo $netTotal . ' <br> ------------ <br>';
                            $value['product_data']->price = $netTotal;
                            $disProduct = [
                              'id' => $value['product_data']->id,
                              'discounted_price' => $discount,
                              'total_cost' => $netTotal
                          ];
                            array_push($product_descount, $disProduct);
                            break;
                        default:
                            break;
                    }
                    // echo ' tama ni<br> ------------ <br>';
                    $totalVal += $value['product_data']->price;
                } elseif ($promo_data[0]->require_purchase == "none") {
                  $discount = 0;
                  if (in_array($value['product_data']->category, $promo_cat)) {
                        // echo 'in array <br>';
                        $productPrice = $value['product_data']->price;
                        $discount = $productPrice * ($promo_data[0]->discount_value / 100);
                        $netTotal = $productPrice  - $discount;
                        // echo $discount . ' <br>';
                        // echo $netTotal . ' <br>------------ <br>';
                        $value['product_data']->price = $netTotal;
                        $disProduct = [
                          'id' => $value['product_data']->id,
                          'discounted_price' => $discount,
                          'total_cost' => $netTotal
                      ];
                      // echo  'mao ni'.$disProduct;
                      array_push($product_descount, $disProduct);
                    }            
                }
                    $distotal += $discount;
                    $totalVal += $value['product_data']->price;
            }
          //  echo 'Total Value: ' . $distotal;
        }
            //promo all
            if ($promo_data[0]->promo_product == 'promo_products_all') {
              $totalVal = 0;
              $distotal = 0;
              foreach ($update_data as $value) {
                  // echo $value['product_data']->price . '<br>';
                  // echo $value['product_data']->id . '<br>---';
                  if (isset($value['product_data'])) {
                      // echo 'in array prod_all<br>';
                      $discount = 0;
                      $productPrice = $value['product_data']->price;
                      $discount = $productPrice * ($promo_data[0]->discount_value / 100);
                      $netTotal = $productPrice  - $discount;
                      // echo $discount . ' <br>';
                      // echo $netTotal . ' <br>------------ <br>';
                      $value['product_data']->price = $netTotal;
                      $disProduct = [
                        'id' => $value['product_data']->id,
                        'discounted_price' => $discount,
                        'total_cost' => $netTotal
                    ];
                    // echo  'mao ni'.$disProduct;
                    array_push($product_descount, $disProduct);
                  }
                  $distotal += $discount;
                  $totalVal += $value['product_data']->price;
              }
              //  echo 'Total Value: ' . $distotal;
          }
          break;
        case "fixed":
           //promo spec prod fix off
           if($promo_data[0]->promo_product == "promo_products_specific"){
            $totalVal = 0;
            $distotal = 0;
            $promo_prod = explode(',' , $promo_data[0]->discounted_specific_product);
            
            foreach($update_data as $value){
              // echo $value['product_data']->price.'---price<br>';
              // echo $value['product_data']->id.'---id<br>';
              
              if($promo_data[0]->require_purchase == 1){
                $req_prod = explode(',' , $promo_data[0]->required_product_id);
                $req_cat = explode(',' , $promo_data[0]->required_category_id);
                $discount = 0;
                switch(true) {
                  case in_array($value['product_data']->id, $promo_prod):
                    // echo '1st array <br>';
                    $productPrice = $value['product_data']->price;
                    $discount = $promo_data[0]->discount_value;
                    $netTotal = $productPrice -  $promo_data[0]->discount_value;
                    // echo $netTotal.'-----net_total <br> ------------ <br>';
                    $value['product_data']->price = $netTotal;
                    $disProduct = [
                      'id' => $value['product_data']->id,
                      'discounted_price' => $discount,
                      'total_cost' => $netTotal
                  ];
                    array_push($product_descount, $disProduct);
                    break; 
                  case in_array($value['product_data']->id, $req_prod):
                    // echo '2nd array condition for req_purchase prod<br>';
                    $productPrice = $value['product_data']->price;
                    $discount = $promo_data[0]->discount_value;
                    $netTotal = $productPrice -  $promo_data[0]->discount_value;
                    // echo $netTotal.'----net_total <br> ------------ <br>';
                    $value['product_data']->price = $netTotal;
                    $disProduct = [
                      'id' => $value['product_data']->id,
                      'discounted_price' => $discount,
                      'total_cost' => $netTotal
                  ];
                    array_push($product_descount, $disProduct);
                    break;
                  case in_array($value['product_data']->category, $req_cat):
                    // echo '2nd array condition for req_purchase cat<br>';
                    $productPrice = $value['product_data']->price;
                    $discount = $promo_data[0]->discount_value;
                    $netTotal = $productPrice - $promo_data[0]->discount_value;
                    // echo $netTotal.'-----net_total <br> ------------ <br>';
                    $value['product_data']->price = $netTotal;
                    $disProduct = [
                      'id' => $value['product_data']->id,
                      'discounted_price' => $discount,
                      'total_cost' => $netTotal
                  ];
                    array_push($product_descount, $disProduct);
                    break;
                }
              } elseif($promo_data[0]->require_purchase == "none"){
                $discount = 0;
                if(in_array($value['product_data']->id, $promo_prod)){
                  // echo '1st array for none<br>';
                  $productPrice = $value['product_data']->price;
                  $discount = $promo_data[0]->discount_value;
                  $netTotal = $productPrice -  $promo_data[0]->discount_value;
                  //  echo $discount.'-----net_total <br> ------------ <br>';
                  $value['product_data']->price = $netTotal;
                  $disProduct = [
                    'id' => $value['product_data']->id,
                    'discounted_price' => $discount,
                    'total_cost' => $netTotal
                ];
                // echo  'mao ni'.$disProduct;
                array_push($product_descount, $disProduct);
                }
              }
              // echo' tama ni<br> ------------ <br>';
               $distotal += $discount;
              // $distotal += $value['product_data']->price;
            }
            // echo 'Total Valuess: '.$distotal;
          }
            //promo cat prod fix off
            if ($promo_data[0]->promo_product == 'promo_products_cat') {
              $promo_cat = explode(',', $promo_data[0]->discounted_category_id);
              $totalVal = 0;
              $distotal = 0;
              // print_r($promo_cat);
              foreach ($update_data as $value) {
                  // echo $value['product_data']->price . '<br>';
                  // echo $value['product_data']->category . '<br>---';
                  if ($promo_data[0]->require_purchase == 1) {
                      $req_prod = explode(',', $promo_data[0]->required_product_id);
                      $req_cat = explode(',', $promo_data[0]->required_category_id);
                      $discount = 0;
                      switch (true) {
                          case in_array($value['product_data']->category, $promo_cat):
                              // echo 'in array <br>';
                              $productPrice = $value['product_data']->price;
                              $discount = $promo_data[0]->discount_value;
                              $netTotal = $productPrice  - $promo_data[0]->discount_value;
                              // echo $netTotal . '-----net_total <br> ------------ <br>';
                              $value['product_data']->price = $netTotal;
                              $disProduct = [
                                'id' => $value['product_data']->id,
                                'discounted_price' => $discount,
                                'total_cost' => $netTotal
                            ];
                              array_push($product_descount, $disProduct);
                              break;
                          case in_array($value['product_data']->id, $req_prod):
                              // echo '2nd array condition for req_purchase prod in categ<br>';
                              $productPrice = $value['product_data']->price;
                              $discount = $promo_data[0]->discount_value;
                              $netTotal = $productPrice -  $promo_data[0]->discount_value;
                              // echo $netTotal . '-----net_total <br> ------------ <br>';
                              $value['product_data']->price = $netTotal;
                              $disProduct = [
                                'id' => $value['product_data']->id,
                                'discounted_price' => $discount,
                                'total_cost' => $netTotal
                            ];
                              array_push($product_descount, $disProduct);
                              break;
                          case in_array($value['product_data']->category, $req_cat):
                              // echo '2nd array condition for req_purchase cat in categ<br>';
                              $productPrice = $value['product_data']->price;
                              $discount = $promo_data[0]->discount_value;
                              $netTotal = $productPrice - $promo_data[0]->discount_value;
                              // echo $netTotal . '-----net_total <br> ------------ <br>';
                              $value['product_data']->price = $netTotal;
                              $disProduct = [
                                'id' => $value['product_data']->id,
                                'discounted_price' => $discount,
                                'total_cost' => $netTotal
                            ];
                              array_push($product_descount, $disProduct);
                              break;
                          default:
                              break;
                      }
                      // echo ' tama ni<br> ------------ <br>';
                      $totalVal += $value['product_data']->price;
                  } elseif ($promo_data[0]->require_purchase == "none") {
                    $discount = 0;  
                    if (in_array($value['product_data']->category, $promo_cat)) {
                          // echo 'in array <br>';
                          $productPrice = $value['product_data']->price;
                          $discount = $promo_data[0]->discount_value;
                          $netTotal = $productPrice  - $promo_data[0]->discount_value;
                          // echo $netTotal . '----net_total <br>------------ <br>';
                          $value['product_data']->price = $netTotal;
                          $disProduct = [
                            'id' => $value['product_data']->id,
                            'discounted_price' => $discount,
                            'total_cost' => $netTotal
                        ];
                        // echo  'mao ni'.$disProduct;
                        array_push($product_descount, $disProduct);
                      }
                      
                      // $totalVal += $value['product_data']->price;
                  }
                  $distotal += $discount;
              }
              // echo 'Total Value: ' . $distotal;
          }
           //promo all fix off
           if ($promo_data[0]->promo_product == 'promo_products_all') {
            $totalVal = 0;
            $distotal = 0;
            foreach ($update_data as $value) {
                // echo $value['product_data']->price . '<br>';
                // echo $value['product_data']->id . '<br>---';
                if (isset($value['product_data'])) {
                    // echo 'in array prod_all<br>';
                    $discount = 0;
                    $productPrice = $value['product_data']->price;
                    $discount = $promo_data[0]->discount_value;
                    $netTotal = $productPrice  - $promo_data[0]->discount_value;
                    // echo $netTotal . '----net_total <br>------------ <br>';
                    $value['product_data']->price = $netTotal;
                    $disProduct = [
                      'id' => $value['product_data']->id,
                      'discounted_price' => $discount,
                      'total_cost' => $netTotal
                  ];
                  // echo  'mao ni'.$disProduct;
                  array_push($product_descount, $disProduct);
                }
                $distotal += $discount;
                // $totalVal += $value['product_data']->price;
            }
             //echo 'Total Value: ' . $distotal;
        }
          break;
        case "sale_price":
          //promo spec prod sale price
          if($promo_data[0]->promo_product == "promo_products_specific"){
            $totalVal = 0;
            $distotal = 0;
            $promo_prod = explode(',' , $promo_data[0]->discounted_specific_product);
    
            foreach($update_data as $value){
              // echo $value['product_data']->price.'---price<br>';
              // echo $value['product_data']->id.'---id<br>';
              
              if($promo_data[0]->require_purchase == 1){
                $req_prod = explode(',' , $promo_data[0]->required_product_id);
                $req_cat = explode(',' , $promo_data[0]->required_category_id);
                $discount = 0;
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
                      'total_cost' => $netTotal
                  ];
                    array_push($product_descount, $disProduct);
                    break;
                }
              } elseif($promo_data[0]->require_purchase == "none"){
                $discount = 0;
                if(in_array($value['product_data']->id, $promo_prod)){
                  // echo '1st array for none<br>';
                  $productPrice = $promo_data[0]->discount_value;
                  $netTotal = $promo_data[0]->discount_value;
                  // echo $netTotal.'-----net_total <br> ------------ <br>';
                  $value['product_data']->price = $netTotal;
                  $disProduct = [
                    'id' => $value['product_data']->id,
                    'discounted_price' => $discount,  
                    'total_cost' => $netTotal
                ];
                // echo  'mao ni'.$disProduct;
                array_push($product_descount, $disProduct);
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
              foreach ($update_data as $value) {
                  // echo $value['product_data']->price . '<br>';
                  // echo $value['product_data']->category . '--cat_id<br>---';
                  if ($promo_data[0]->require_purchase == 1) {
                      $req_prod = explode(',', $promo_data[0]->required_product_id);
                      $req_cat = explode(',', $promo_data[0]->required_category_id);
                      $discount = 0;
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
                                'total_cost' => $netTotal
                            ];
                              array_push($product_descount, $disProduct);
                              break;
                          default:
                              break;
                      }
                      // echo ' tama ni<br> ------------ <br>';
                      $totalVal += $value['product_data']->price;
                  } elseif ($promo_data[0]->require_purchase == "none") {
                    $discount = 0; 
                    if (in_array($value['product_data']->category, $promo_cat)) {
                          // echo 'in array <br>';
                          $productPrice = $promo_data[0]->discount_value;
                          $netTotal = $promo_data[0]->discount_value;
                          // echo $netTotal . '----net_total <br>------------ <br>';
                          $value['product_data']->price = $netTotal;
                          $disProduct = [
                            'id' => $value['product_data']->id,
                            'discounted_price' => $discount,
                            'total_cost' => $netTotal
                        ];
                        // echo  'mao ni'.$disProduct;
                        array_push($product_descount, $disProduct);
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
            foreach ($update_data as $value) {
                // echo $value['product_data']->price . '<br>';
                // echo $value['product_data']->id . '<br>---';
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
        case "bxgx":
          //promo spec prod buy 1 get 1
               if ($promo_data[0]->promo_product == 'promo_products_specific') {
                $totalVal = 0;
                foreach ($update_data as $key => $value) {
                    // echo $value['product_data']->price . '<br>';
                    // echo $value['product_data']->id . '<br>---';
                    if ($key === 1) {
                        // echo ' 2nd in array prod_all<br>';
                        $netTotal = 0; 
                        // echo $netTotal . '----net_total <br>------------ <br>';
                        $value['product_data']->price = $netTotal; 
                    } 
                    $totalVal += $value['product_data']->price;
                }
                // echo 'Total Value: ' . $totalVal;
                // print_r($cart_products);
            }
             //promo cat prod buy 1 get 1
             if ($promo_data[0]->promo_product == 'promo_products_cat') {
              $totalVal = 0;
              foreach ($update_data as $key => $value) {
                  // echo $value['product_data']->price . '<br>';
                  // echo $value['product_data']->id . '<br>---';
                  if ($key === 1) {
                      // echo ' 2nd in array prod_all<br>';
                      $netTotal = 0; 
                      // echo $netTotal . '----net_total <br>------------ <br>';
                      $value['product_data']->price = $netTotal; 
                  } 
                  $totalVal += $value['product_data']->price;
              }
              // echo 'Total Value: ' . $totalVal;
              // print_r($cart_products);
          }
          //promo all buy 1 get 1
          if ($promo_data[0]->promo_product == 'promo_products_all') {
            $totalVal = 0;
            foreach ($update_data as $key => $value) {
                // echo $value['product_data']->price . '<br>';
                // echo $value['product_data']->id . '<br>---';
                if ($key === 1) {
                    // echo ' 2nd in array prod_all<br>';
                    $netTotal = 0; 
                    // echo $netTotal . '----net_total <br>------------ <br>';
                    $value['product_data']->price = $netTotal; 
                } 
                $totalVal += $value['product_data']->price;
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
           
            foreach($update_data as $value){
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
        foreach ($update_data as $value) {
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
            foreach ($update_data as $value) {
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
      $this->data['distotal'] = $distotal;
      $this->data['product_descount'] = $product_descount;
      $session->discountSub = $this->data['product_descount'];
      $session->distotal = $this->data['distotal'];
      
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
