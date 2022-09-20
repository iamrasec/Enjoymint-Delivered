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

    $count_all = $this->order_model->countAllResults();

    // print_r($count_all);

    // 2nd Query that gets all the data
    $this->order_model->select("id, CONCAT(first_name, ' ', last_name) AS customer_name, address, (SELECT COUNT(id) FROM order_products WHERE order_id = orders.id) AS product_count, total, created, status");

    if(isset($post['search']['value']) && !empty($post['search']['value'])) {
      $search_value = strtolower($post['search']['value']);
      $this->order_model->like("LOWER(CONCAT(first_name, ' ', last_name))", $search_value);
      $this->order_model->orLike("LOWER(address)", $search_value);
    }

    if(isset($post['start']) && isset($post['length'])) {
      $orders = $this->order_model->get($post['start'], $post['length'])->getResult();
    }
    else {
      $orders = $this->order_model->get()->getResult();
    }

    // print_r($orders);

    $output = array(
      "draw" => $post['draw'],
      "recordsTotal" => $this->order_model->countAll(),
      "recordsFiltered" => $count_all,
      "data" => $orders,
    );

    echo json_encode($output);
    exit();
  }

}
