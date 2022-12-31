<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use Exception;
use ReflectionException;

class Cart extends ResourceController
{
  public function __construct() {
    $this->data = [];
    $this->cart_model = model('CartModel');
    $this->user_model = model('UserModel');
    $this->product_model = model('ProductModel');
    
    $this->data['service_charge'] = 5.00;
  }

  public function index()
  {
      // return $this->respond($this->model->findAll());
      echo "View Cart Page";

      // return $this->getResponse(
      //   ResponseInterface::HTTP_OK
      // );
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

  public function fetch()
  {
    $data = $this->request->getPost();

    $user = validateJWTFromRequestOutputUser($data['token']);

    $db_cart = $this->cart_model->where('uid', $user['id'])->get()->getResult();

    echo json_encode(["status" => 'updated', "cartProducts" => $db_cart]);
    exit;
  }

  public function checkout()
  {
    $data = $this->request->getPost();

    $user = $this->user_model->getUserByGuid($data['guid']);

    // Initialize order record in the database
    

    $db_cart = $this->cart_model->where('uid', $user['id'])->get()->getResult();
  }

  public function delete_cart_item()
  {
    $data = $this->request->getPost();

    $user = $this->user_model->getUserByGuid($data['guid']);

    $delete = $this->cart_model->delete_cart_item($user['id'], $data['pid']);

    echo json_encode(["status" => 'deleted', "deleted" => $delete]);
    exit;
  }

  public function update_cart_summary()
  {
    $data = $this->request->getPost();

    $user = $this->user_model->getUserByGuid($data['guid']);

    $cart_raw = $this->cart_model->where('uid', $user['id'])->get()->getResult();

    $item_count = 0;
    $subtotal = 0;
    $tax_cost = 0;
    $total_cost = 0;
    $this->data['tax_rate'] = 1.35;  // 35%

    foreach($cart_raw as $product) {

      // Get products from the database using pid
      $product_data = $this->product_model->getProductData($product->pid);

      $subtotal += $product_data->price * $product->qty;
      $item_count++;
    }

    $tax_cost = $subtotal * ($this->data['tax_rate'] - 1);
    $total_cost = $subtotal * $this->data['tax_rate'];

    $order_costs = [
      'item_count' => ($item_count > 1) ? $item_count." Items" : $item_count." Item",
      'subtotal' => "$".number_format($subtotal, 2, '.', ','),
      'tax' => "$".number_format($tax_cost, 2, '.', ','),
      'service_charge' => "$".($subtotal < 50) ? $this->data['service_charge'] : 0,
      'total' => "$".number_format($total_cost, 2, '.', ','),
    ];

    echo json_encode(["status" => 'updated', "order_costs" => $order_costs]);
    exit;
  }

  // ...
}