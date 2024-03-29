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
      $this->blog_model = model('BlogModel');
      $this->image_model = model('ImageModel');

      helper(['form', 'functions']); // load helpers
      addJSONResponseHeader(); // set response header to json
    }

  /**
   * This function will save a blog into the server
   * 
   * @return object a success indicator and the message
  */
  public function add()
  {
    if($this->request->getPost()) {
      $validation =  \Config\Services::validation();
      
      $rules = [
        'title' => 'required|min_length[1]',
        'url' => 'required|min_length[1]',
        'content' => 'required|min_length[10]',
        'author' => 'required|min_length[1]',
      ];

      if($this->validate($rules)) {
        $data['validation'] = $this->validator;
        $images = array(); // initialize image array
        if ($this->request->getFiles()) {
          $file = $this->request->getFiles(); // get all files from post request

          $img = $file['blog_image'];

          if (!$img->hasMoved()) {
            $fileName = $img->getRandomName(); // generate a new random name
            $type = $img->getMimeType();
            $img->move( WRITEPATH . 'uploads/blogs', $fileName); // move the file to writable/uploads
            // $img->store('blogs/', $fileName);
            
            // json data to be save to image
            $imageData = [
              'filename' => $fileName,
              'mime' => $type,
              'url' => 'writable/uploads/blogs/'. $fileName,
            ];

            $this->image_model->save($imageData); // try to save to images table
            $imageId = $this->image_model->insertID();
            array_push($images, $imageId);
          }
        }
        
        // data mapping for BLOGS table save
        $to_save = [
          'title' => $this->request->getVar('title'),
          'url' => $this->request->getVar('url'),
          'description' => $this->request->getVar('description'),
          'content' => $this->request->getVar('content'),
          'author' => $this->request->getVar('author'),
          'images' => implode(',', $images),
        ];

        // print_r($to_save);

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

  /**
   * This function will edit a blog into the server
   * 
   * @return object a success indicator and the message
  */
  public function edit_blog($id)
  {
    if($this->request->getPost()) {
      // print_r($this->request->getPost());

      $validation =  \Config\Services::validation();
      
      $rules = [
        'title' => 'required|min_length[1]',
        'url' => 'required|min_length[1]',
        'content' => 'required|min_length[10]',
        'author' => 'required|min_length[1]',
      ];
        
      if($this->validate($rules)) {
        $data['validation'] = $this->validator;
        
        $images = array(); // initialize image array
        if ($this->request->getFiles()) {
          $file = $this->request->getFiles(); // get all files from post request

          $img = $file['blog_image'];

          if (!$img->hasMoved()) {
            $fileName = $img->getRandomName(); // generate a new random name
            $type = $img->getMimeType();
            $img->move( WRITEPATH . 'uploads/blogs', $fileName); // move the file to writable/uploads
            // $img->store('blogs/', $fileName);
            
            // json data to be save to image
            $imageData = [
              'filename' => $fileName,
              'mime' => $type,
              'url' => 'writable/uploads/blogs/'. $fileName,
            ];

            $this->image_model->save($imageData); // try to save to images table
            $imageId = $this->image_model->insertID();
            array_push($images, $imageId);
          }
        }
        else {
          $images[0] = $this->request->getVar('current_image');
        }
        
        // data mapping for BLOGS table save
        $to_save = [
          'title' => $this->request->getVar('title'),
          'url' => $this->request->getVar('url'),
          'description' => $this->request->getVar('description'),
          'content' => $this->request->getVar('content'),
          'author' => $this->request->getVar('author'),
          'images' => implode(',', $images),
        ];
        
        // print_r($to_save);

        $this->blog_model->set($to_save)->where('id', $id)->update(); // trying to update blog to database
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
