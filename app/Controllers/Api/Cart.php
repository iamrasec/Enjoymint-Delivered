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

    $return = $this->cart_model->insert($data);

    if(is_numeric($return)) {
      echo json_encode(["message" => true, "data" => $return]);
    }
    else {
      echo json_encode(["message" => "error"]);
    }
    exit;
  }

  // ...
}