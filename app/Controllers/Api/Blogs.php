<?php

namespace App\Controllers\API;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use Exception;
use ReflectionException;

class Blogs extends ResourceController
{
  use ResponseTrait;
    public function __construct() 
    {
      helper(['jwt']);

      $this->data = [];
      $this->role = session()->get('role');
      $this->isLoggedIn = session()->get('isLoggedIn');
      $this->guid = session()->get('guid');
      $this->blog_model = model('blogModel');
      $this->image_model = model('ImageModel');

      helper(['form', 'functions']); // load helpers
      addJSONResponseHeader(); // set response header to json
      
    }


  /**
   * This function will save a product into the server
   * 
   * @return object a success indicator and the message
  */
  public function add()
  {
    if($this->request->getPost()) {
      $validation =  \Config\Services::validation();
      
      $rules = [
        'title' => 'required|min_length[1]',
        'description' => 'required|min_length[1]',
        'author' => 'required|min_length[1]',
      ];

      if($this->validate($rules)) {
        $data['validation'] = $this->validator;
        $images = array(); // initialize image array
        if ($this->request->getFiles()) {
          $file = $this->request->getFiles(); // get all files from post request
          // loop through all files uploaded
          foreach($file['productImages'] as $img){
            if (!$img->hasMoved()) {
                $fileName = $img->getRandomName(); // generate a new random name
                $type = $img->getMimeType();
                $img->move( WRITEPATH . 'uploads/' . $fileName); // move the file to writable/uploads
                
                // json data to be save to image
                $imageData = [
                  'filename' => $fileName,
                  'mime' => $type,
                  'url' => 'public/uploads/'. $fileName,
                ];
                $this->image_model->save($imageData); // try to save to images table
                $imageId = $this->image_model->insertID();
                array_push($images, $imageId);
            }
          }
        }
        
        // data mapping for PRODUCTS table save
        $to_save = [
          'title' => $this->request->getVar('title'),
          'description' => $this->request->getVar('description'),
          'author' => $this->request->getVar('author'),
          'images' => implode(',', $images),
        ];
        $this->blog_model->save($to_save); // trying to save product to database
        $data_arr = array("success" => TRUE,"message" => 'Blog Saved!');
      } else {
        $validationError = json_encode($validation->getErrors());
        $data_arr = array("success" => FALSE,"message" => 'Validation Error!'.$validationError);
      }
    } else {
      $data_arr = array("success" => FALSE,"message" => 'No posted data!');
    }
    die(json_encode($data_arr));
  }

}
