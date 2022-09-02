<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use Exception;
use ReflectionException;

class Users extends ResourceController
{
  public function __construct() {
    $this->data = [];
    $this->user_model = model('UserModel');
		$this->forgotpassword_model = model('ForgotpasswordModel');
  }

  public function index()
  {
      echo "forbidden";
  }

  public function login()
  {
    $data = $this->request->getPost();

    // $return = $this->brand_model->insert($data);

    // if(is_numeric($return)) {
    //   echo json_encode(["message" => true, "data" => $return]);
    // }
    // else {
    //   echo json_encode(["message" => "error"]);
    // }

    echo json_encode(["message" => true, "data" => $data]);
    exit;
  }

  // ...
}