<?php

namespace App\Controllers\API;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class Strain extends ResourceController
{
    // protected $modelName = 'App\Models\Photos';
    // protected $format    = 'json';

    public function index()
    {
        // return $this->respond($this->model->findAll());
        print_r("Strain List");
    }

    public function add()
    {
      print_r("Add Strain");
    }

    // ...
}