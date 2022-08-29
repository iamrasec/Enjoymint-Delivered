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
    $new_item = 0;

    if(!empty($product_in_cart)) {
      $saveCart = $this->cart_model->updateCartProduct($data['uid'], $data['pid'], $data['qty']);

      // $session_data = session()->get('cart_items');

      // if(empty($session_data)) {
      //   session()->push('cart_items', $this->cart_model->where('uid', $data['uid'])->get()->getResult());
      // }
      // else {
      //   for($i = 0; $i < count($session_data); $i++) {
      //     if($session_data[$i]['pid'] == $data['pid']) {
      //       $session_data[$i]['qty'] += $data['qty'];
      //     }
      //   }
  
      //   session()->push('cart_items', $session_data);
      // }
      
    }
    else {
      $saveCart = $this->cart_model->insert($data);
      $new_item++;
    }
    

    echo json_encode(["status" => true, "newItem" => $new_item]);
    exit;
  }

  // ...
}