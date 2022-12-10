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

  public function complete($id) {
    // $this->drivers_model->save($id, ['name' => $this->request->getVar('driver')]);
    $this->order_model->update($id, ['status' => 1]);
    die(json_encode(array("success" => TRUE,"message" => 'Product Delete!', "id" => $id)));
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
          'proile_img' => (isset($proile_img)) ? '<img class="customer-valid-id" src="'.base_url('users/verification/'.$proile_img[0]->filename).'"style="width:120px; width: 90px;">' : '',
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
