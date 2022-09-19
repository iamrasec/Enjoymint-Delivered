<?php

namespace App\Controllers\API;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use Exception;
use ReflectionException;

class Orders extends ResourceController
{
  use ResponseTrait;
    public function __construct() 
    {
      helper(['jwt']);

      $this->data = [];
      $this->role = session()->get('role');
      $this->isLoggedIn = session()->get('isLoggedIn');
      $this->guid = session()->get('guid');
      $this->drivers_model = model('Drivers');
      $this->order_model = model('CheckoutModel');

      helper(['form', 'functions']); // load helpers
      addJSONResponseHeader(); // set response header to json
      
    }


  /**
   * This function will save a drivers and update order into the server
   * 
   * @return object a success indicator and the message
  */
  public function add()
  {
    
    if($this->request->getPost()) {
      
        $drivers = [
            'name' => $this->request->getPost('driver'),
          ];
          $this->drivers_model->save($drivers);
    }
    
}

public function complete($id) {
  // $this->drivers_model->save($id, ['name' => $this->request->getVar('driver')]);
  $this->order_model->update($id, ['status' => 1]);
  die(json_encode(array("success" => TRUE,"message" => 'Product Delete!', "id" => $id)));
}

}
