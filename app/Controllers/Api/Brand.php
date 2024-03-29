<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use Exception;
use ReflectionException;

class Brand extends ResourceController
{
  public function __construct() {
    $this->data = [];
    $this->brand_model = model('BrandModel');
  }

  public function index()
  {
      // return $this->respond($this->model->findAll());
      echo "Strain List";

      // return $this->getResponse(
      //   ResponseInterface::HTTP_OK
      // );
  }

  public function add()
  {
    $data = $this->request->getPost();

    $return = $this->brand_model->insert($data);

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