<?php

namespace App\Controllers\API;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use Exception;
use ReflectionException;

class Products extends ResourceController
{
    public function __construct() 
    {
      $this->data = [];
      $this->role = session()->get('role');
      $this->isLoggedIn = session()->get('isLoggedIn');
      $this->guid = session()->get('guid');
      $this->product_model = model('productModel');
      $this->strain_model = model('strainModel');
      $this->brand_model = model('brandModel');
      $this->measurement_model = model('measurementModel');
      $this->image_model = model('imageModel');
      $this->product_variant_model = model('productVariantModel');
      $this->category_model = model('CategoryModel');
  
      if($this->isLoggedIn !== 1 && $this->role !== 1) {
        return redirect()->to('/');
      }
    }


  /**
   * This function will save a product into the server
   * 
   * @return object a success indicator and the message
  */
  public function add()
  {
    helper(['form', 'functions']); // load helpers
    addJSONResponseHeader(); // set response header to json

    print_r($this->request->getPost());

    if($this->request->getPost()) {
      $rules = [
        'name' => 'required|min_length[3]',
        'sku' => 'required|min_length[3]',
        'purl' => 'required|min_length[3]',
        'qty' => 'required|decimal',
        'thc_val' => 'required',
        'cbd_val' => 'required',
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
                $img->move( WRITEPATH . 'uploads', $fileName); // move the file to writable/uploads
                
                // json data to be save to image
                $imageData = [
                  'filename' => $fileName,
                  'mime' => $type,
                  'url' => 'writable/uploads/'. $fileName,
                ];
                $this->image_model->save($imageData); // try to save to images table
                $imageId = $this->image_model->insertID();
                array_push($images, $imageId);
            }
          }
        }
        
        // data mapping for PRODUCTS table save
        $to_save = [
          'name' => $this->request->getVar('name'),
          'url' => $this->request->getVar('purl'),
          'description' => $this->request->getVar('description'),
          'strain' => $this->request->getVar('strain'),
          'stocks' => $this->request->getVar('qty'),
          'brands' => $this->request->getVar('brand'),
          'price' => $this->request->getVar('price'),
          'sku' => $this->request->getVar('sku'),
          'images' => implode(',', $images),
        ];
        $this->product_model->save($to_save); // trying to save product to database
        $productId = $this->product_model->insertID();

        // Save Categories
        if($this->request->getVar('categories') != "") {
          $categories = explode(",", $this->request->getVar('categories'));
          print_r($categories);
        }

        $variantCount = count($this->request->getVar('prices[]'));
        for($x=0;$x<$variantCount;$x++){
          $variantData = [
            'pid' => $productId,
            'unit	' => $_POST['units'][$x],
            'unit_value' => $_POST['unit_values'][$x],
            'price' => $_POST['prices'][$x],
            'stock' => $_POST['stocks'][$x]
          ];
          $this->product_variant_model->save($variantData); // try to save product variant
        }
        $data_arr = array("success" => TRUE,"message" => 'Product Saved!');
      } else {
        $data_arr = array("success" => FALSE,"message" => 'Validation Error!');
      }
    } else {
      $data_arr = array("success" => FALSE,"message" => 'No posted data!');
    }
    die(json_encode($data_arr));
  }

    // ...
}