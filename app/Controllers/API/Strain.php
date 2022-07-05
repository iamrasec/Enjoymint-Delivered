<?php

namespace App\Controllers\API;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use Exception;
use ReflectionException;

class Strain extends ResourceController
{
    // protected $modelName = 'App\Models\Photos';
    // protected $format    = 'json';

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
      echo json_encode(["message" => "Add Strain"]);
      exit;
    }

    // ...
}