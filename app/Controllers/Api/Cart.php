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

    echo json_encode(["status" => 'updated', "deleted" => $data]);
    exit;
  }

  // ...
}