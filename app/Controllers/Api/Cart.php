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

  // ...
}