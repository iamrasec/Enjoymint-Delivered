<?php

namespace App\Controllers\API;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use Exception;
use ReflectionException;

class Contact extends ResourceController
{
  
  public function __construct() {
    $this->data = [];
    $this->contact_model = model('ContactModel');
  }

  public function index() {
    echo 'Contact API';
  }

  public function save() {
    $data = $this->request->getPost();

    // print_r($data);

    $return = $this->contact_model->insert($data);

    echo json_encode(["message" => true, "data" => $data, "return" => $return]);

    // $return = $this->contact_model->insert($data);

    // if(is_numeric($return)) {
    //   echo json_encode(["message" => true, "data" => $return]);
    // }
    // else {
    //   echo json_encode(["message" => "error"]);
    // }
    exit;
  }
  // ...
}