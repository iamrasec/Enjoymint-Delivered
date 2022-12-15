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
      helper(['jwt']);

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

      $this->tax_rate = 1.35;  // 35%

      helper(['form', 'functions']); // load helpers
      addJSONResponseHeader(); // set response header to json
      
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
    $post = $this->request->getPost();

    $cart_key = $post['order_key'];
    $cart_id = $post['oid'];
    $order_pids = $post['order_pids'];
    $to_save = $post['order_data'];

    // echo "<pre>".print_r($post, 1)."</pre>";die();
    // echo "<pre>".print_r($to_save, 1)."</pre>";die();

    $get_saved_cart = $this->order_products->select('product_id, qty')->where('order_id', $post['oid'])->get()->getResult();

    // echo "<pre>".print_r($get_saved_cart, 1)."</pre>";die();

    $new_subtotal = 0;

    foreach($to_save as $to_save_data) {
      $existing_product = false;
      foreach($get_saved_cart as $saved_cart) {
        if($to_save_data['id'] == $saved_cart->product_id) {
          $existing_product = true;

          if($to_save_data['qty'] != $saved_cart->qty) {
            $product_cost_total = number_format($to_save_data['qty'] * $to_save_data['price'], 2, '.', '');
            $new_subtotal += $product_cost_total;

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
        $new_subtotal += $new_product_cost_total;

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
      }
    }

    $tax_cost = $new_subtotal * ($this->tax_rate - 1);
    $total_cost = $new_subtotal * $this->tax_rate;

    $update_order = [
      'address' => $post['del_address'],
      'payment_method' => $post['pay_method'],
      'order_notes' => $post['notes']
    ];

    if($new_subtotal > 0) {
      $update_order['subtotal'] = $new_subtotal;
      $update_order['tax'] = $tax_cost;
      $update_order['total'] = $total_cost;
    }

    $this->order_model->where('id', $post['oid'])->set($update_order)->update();

    // echo "<pre>".print_r($post, 1)."</pre>";
    // echo "<pre>".print_r($get_saved_cart, 1)."</pre>";die();

    die(json_encode(array("success" => TRUE,"message" => 'Order Updated Successfully')));
  }

  public function complete() 
  {
    $post = $this->request->getPost();

    // print_r($post['pid']);die();

    $this->order_model->where('id', $post['pid'])->set('status', 2)->update();

    die(json_encode(array("success" => TRUE,"message" => 'Order Completed', "id" => $post['pid'])));
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
    $this->order_model->select("id, customer_id, CONCAT(first_name, ' ', last_name) AS customer_name, address, (SELECT COUNT(id) FROM order_products WHERE order_id = orders.id) AS product_count, total, created, status, delivery_schedule");  // <-- working query

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
    $this->order_model->select("id, CONCAT(first_name, ' ', last_name) AS customer_name, address, (SELECT COUNT(id) FROM order_products WHERE order_id = orders.id) AS product_count, total, created, status");

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
    $this->order_model->select("id, CONCAT(first_name, ' ', last_name) AS customer_name, address, (SELECT COUNT(id) FROM order_products WHERE order_id = orders.id) AS product_count, total, created, status");

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
    $this->order_model->select("id, CONCAT(first_name, ' ', last_name) AS customer_name, address, (SELECT COUNT(id) FROM order_products WHERE order_id = orders.id) AS product_count, total, created, status");

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
}
