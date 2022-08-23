<?php

namespace App\Controllers\API;

use CodeIgniter\RESTful\ResourceController;

class Ratings extends ResourceController
{
    public function __construct() 
    {
      $this->rating_model = model('ratingModel');
      helper(['form', 'functions']); // load helpers
      addJSONResponseHeader(); // set response header to json
    }


  /**
   * This function will save a new rating to a product
   * 
   * @return object a success indicator and the message
  */
  public function add()
  {
    if($this->request->getPost()) {
      $rules = [
        'name' => 'required|max_length[50]',
        'message' => 'required|max_length[500]',
        'rating' => 'required',
      ];

      if($this->validate($rules)) {
        // data mapping for RATINGS table save
        $to_save = [
          'name' => $this->request->getVar('name'),
          'message' => $this->request->getVar('purl'),
          'rating' => $this->request->getVar('description'),
          'status' => 1,
          'product_id' => 1,
        ];
        $this->rating_model->save($to_save); 
        $data_arr = array("success" => TRUE,"message" => 'Ratings added.');
      } else {
        $data_arr = array("success" => FALSE,"message" => 'Validation Error.');
      }
    }
    die(json_encode($data_arr));
  }

    // ...
}