<?php

namespace App\Controllers\Api;

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
      $this->product_model = model('ProductModel');
      $this->strain_model = model('StrainModel');
      $this->brand_model = model('BrandModel');
      $this->measurement_model = model('MeasurementModel');
      $this->image_model = model('ImageModel');
      $this->product_variant_model = model('ProductVariantModel');
      $this->category_model = model('CategoryModel');
      $this->product_category = model('ProductCategory');
      $this->product_experience = model('ProductExperience');
      $this->compound_model = model('CompoundModel');
      $this->discount_model = model('DiscountModel');
      // $this->experience_model = model('ExperienceModel');


      $this->data['user_jwt'] = getSignedJWTForUser($this->guid);
  
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

    // $variants = json_decode($this->request->getVar('variants'));
    // if(empty($variants)) {
    //   echo "<pre>No Variants Added</pre>";
    // }
    // else {
    //   echo "<pre>".print_r($variants, 1)."</pre>";
    // }
    // die();

    // print_r($this->request->getPost());

    /** Start Testing Discounts */
    // echo "<pre>".print_r($this->request->getPost(), 1)."</ pre>";

    // if($this->request->getVar('discount_val') > 0) {

    //   if($this->request->getVar('sale_start_date')) {
    //     $sale_start_date_raw = $this->request->getVar('sale_start_date');
    //     $get_start_time = explode(" ", $sale_start_date_raw);
    //     $sale_start_date = $get_start_time[0] ." ". date('H:i:s', strtotime($get_start_time[1]." ".$get_start_time[2]));
    //     echo "<pre>".print_r($sale_start_date, 1)."</ pre>";
    //   }
    //   else {
    //     echo "<pre>No Start Date</pre>";
    //   }
      
    // }
    // else {
    //   echo "<pre>Product Not On Sale</pre>";
    // }
    // die();
    /** END testing discounts */

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

        // Check if there are variants submitted
        $variants = json_decode($this->request->getVar('variants'));

        $has_variant = (!empty($variants)) ? 1 : 0;
        
        // data mapping for PRODUCTS table save
        $to_save = [
          'name' => $this->request->getVar('name'),
          'url' => $this->request->getVar('purl'),
          'description' => $this->request->getVar('description'),
          'category' => $this->request->getVar('category'),
          'strain' => $this->request->getVar('strain'),
          'stocks' => $this->request->getVar('qty'),
          'brands' => $this->request->getVar('brand'),
          'price' => $this->request->getVar('price'),
          'sku' => $this->request->getVar('sku'),
          'unit_measure' => $this->request->getVar('unit_measure'),
          'unit_value' => $this->request->getVar('unit_value'),
          'has_variant' => $has_variant,
          'delivery_type' => $this->request->getVar('delivery_type'),
          'lowstock_threshold' => $this->request->getVar('lowstock_threshold'),
          'on_sale' => $this->request->getVar('on_sale'),
          'images' => implode(',', $images),
        ];
        $this->product_model->save($to_save); // trying to save product to database
        $productId = $this->product_model->insertID();

        // Save Categories
        if($this->request->getVar('categories') != "") {
          $categories = explode(",", $this->request->getVar('categories'));

          foreach($categories as $category) {
            $saveCat = [
              'pid' => $productId,
              'cid' => $category,
            ];
            $this->product_category->save($saveCat);
          }
        }

        
        // Save Experience
        if($this->request->getVar('experience') != "") {
          $experience = explode(",", $this->request->getVar('experience'));

          foreach($experience as $exps) {
            $saveExp = [
              'pid' => $productId,
              'exp_id' => $exps,
            ];
            $this->product_experience->save($saveExp);
          }
        }

        //Save Compounds
        $saveCompounds = [
          'pid' => $productId,
          'thc_unit' => $this->request->getVar('thc_measure'),
          'thc_value' => ($this->request->getVar('thc_val') ? $this->request->getVar('thc_val') : 0),
          'cbd_unit' => $this->request->getVar('cbd_measure'),
          'cbd_value' => ($this->request->getVar('cbd_val') ? $this->request->getVar('cbd_val') : 0),
        ];

        $this->compound_model->save($saveCompounds);

        /** SAVE VARIANTS */
        if(!empty($variants)) {
          foreach($variants as $variant) {
            $save_variant = [
              'pid' => $productId,
              'unit' => $variant->variant_unit,
              'unit_value' => $variant->variant_unit_value,
              'price' => $variant->variant_price,
              'stock' => $variant->variant_qty,
            ];

            $this->product_variant_model->save($save_variant);
          }

          // echo "<pre>".print_r($variants, 1)."</pre>";
        }

        // Save Sale/Discount
        if($this->request->getVar('discount_val') > 0) {
          $sale_start_date = "";
          $sale_end_date = "";
          $variant_id = 0;
          
          if($this->request->getVar('sale_start_date')) {
            $sale_start_date_raw = $this->request->getVar('sale_start_date');
            $get_start_time = explode(" ", $sale_start_date_raw);
            $sale_start_date = $get_start_time[0] ." ". date('H:i:s', strtotime($get_start_time[1]." ".$get_start_time[2]));
          }

          if($this->request->getVar('sale_end_date')) {
            $sale_end_date_raw = $this->request->getVar('sale_end_date');
            $get_end_time = explode(" ", $sale_end_date_raw);
            $sale_end_date = $get_end_time[0] ." ". date('H:i:s', strtotime($get_end_time[1]." ".$get_end_time[2]));
          }

          $saveDiscount = [
            'pid' => $productId,
            'variant_id' => $variant_id,
            'discount_value' => $this->request->getVar('discount_val'),
            'discount_attribute' => $this->request->getVar('discount_type'),
            'start_date' => $sale_start_date,
            'end_date' => $sale_end_date,
            'status' => 1,
          ];

          $this->discount_model->save($saveDiscount);
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

  public function edit_product($pid)
  {
    helper(['form', 'functions']); // load helpers
    addJSONResponseHeader(); // set response header to json

    // $variants = json_decode($this->request->getVar('variants'));
    // if(empty($variants)) {
    //   echo "<pre>No Variants Added</pre>";
    // }
    // else {
    //   echo "<pre>".print_r($variants, 1)."</pre>";
    // }
    // die();

    // print_r($this->request->getPost()); die();

    if($this->request->getPost()) {
      // $rules = [
      //   'name' => 'required|min_length[3]',
      //   'sku' => 'required|min_length[3]',
      //   'purl' => 'required|min_length[3]',
      //   'qty' => 'required|decimal',
      //   'thc_val' => 'required',
      //   'cbd_val' => 'required',
      // ];

      // if($this->validate($rules)) {
      //   $data['validation'] = $this->validator;

        $images = array(); // initialize image array

        $current_images = $this->request->getVar('current_images');

        // print_r($current_images); die();
      if($current_images != null) {
      foreach($current_images as $current_image) {
        array_push($images, $current_image);
       }
        }
        if ($this->request->getFiles()) {
          $file = $this->request->getFiles(); // get all files from post request
          // loop through all files uploaded
          foreach($file['productImages'] as $img){
            if (!$img->hasMoved()) {
                $fileName = $img->getRandomName(); // generate a new random name
                $type = $img->getMimeType();
                // $img->move( WRITEPATH . 'uploads', $fileName); // move the file to writable/uploads
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

        // Check if there are variants submitted
        $variants = json_decode($this->request->getVar('variants'));

        $has_variant = (!empty($variants)) ? 1 : 0;
        
        // data mapping for PRODUCTS table save
        $to_save = [
          // 'id' => $this->request->getVar('pid'),
          'name' => $this->request->getVar('name'),
          'url' => $this->request->getVar('purl'),
          'description' => $this->request->getVar('description'),
          'strain' => $this->request->getVar('strain'),
          'stocks' => $this->request->getVar('qty'),
          'brands' => $this->request->getVar('brand'),
          'price' => $this->request->getVar('price'),
          'sku' => $this->request->getVar('sku'),
          'unit_measure' => $this->request->getVar('unit_measure'),
          'unit_value' => $this->request->getVar('unit_value'),
          'has_variant' => $has_variant,
          'delivery_type' => $this->request->getVar('delivery_type'),
          'lowstock_threshold' => $this->request->getVar('lowstock_threshold'),
          'on_sale' => $this->request->getVar('on_sale'),
          'images' => implode(',', $images),
        ];

        // print_r($to_save); die();

        $this->product_model->set($to_save)->where('id', $pid)->update();
        // $this->product_model->where('id', $pid)->update($to_save);
        // $productId = $this->product_model->insertID();

        // Save Categories
        if($this->request->getVar('categories') != "") {
           $categories = explode(",", $this->request->getVar('categories'));

          $this->product_category->where('pid', $pid)->delete();

          // print_r($this->product_category->getLastQuery());

          foreach($categories as $category) {
            $saveCat = [
              'pid' => $pid,
              'cid' => $category,
            ];

            $this->product_category->save($saveCat);
          }
        }

        /** SAVE EXPERIENCE */
        if($this->request->getVar('experiences') != "") {
          $experience = explode(",", $this->request->getVar('experiences'));

          $this->product_experience->where('pid', $pid)->delete();

          // print_r($this->product_category->getLastQuery());
                  
          foreach($experience as $exps) {
            $saveExp = [
              'pid' => $pid,
              'exp_id' => $exps,
            ];

            $this->product_experience->save($saveExp);
          }
        }

        /** SAVE COMPOUNDS */
        $saveCompounds = [
          'pid' => $pid,
          'thc_unit' => $this->request->getVar('thc_measure'),
          'thc_value' => ($this->request->getVar('thc_val') ? $this->request->getVar('thc_val') : 0),
          'cbd_unit' => $this->request->getVar('cbd_measure'),
          'cbd_value' => ($this->request->getVar('cbd_val') ? $this->request->getVar('cbd_val') : 0),
        ];

        $this->compound_model->where('pid', $pid)->delete();
        
        // print_r($this->product_category->getLastQuery());

        $this->compound_model->save($saveCompounds);

        // print_r($this->product_category->getLastQuery());

        // Save Sale/Discount
if($this->request->getVar('discount_val') > 0) {
    $sale_start_date = "";
    $sale_end_date = "";
    $variant_id = 0;

    if($this->request->getVar('sale_start_date')) {
        $sale_start_date_raw = $this->request->getVar('sale_start_date');
        $get_start_time = explode(" ", $sale_start_date_raw);
        $sale_start_date = $get_start_time[0] . " " . date('H:i:s', strtotime($get_start_time[1] . " " . $get_start_time[2]));
    }

    if($this->request->getVar('sale_end_date')) {
        $sale_end_date_raw = $this->request->getVar('sale_end_date');
        $get_end_time = explode(" ", $sale_end_date_raw);
        $sale_end_date = $get_end_time[0] . " " . date('H:i:s', strtotime($get_end_time[1] . " " . $get_end_time[2]));
    }

    $saveDiscount = [
      'pid' => $pid,
      'variant_id' => $variant_id,
      'discount_value' => $this->request->getVar('discount_val'),
      'discount_attribute' => $this->request->getVar('discount_type'),
      'start_date' => $sale_start_date,
      'end_date' => $sale_end_date,
      'status' => 1,
    ];

    $this->discount_model->save($saveDiscount);
    /** SAVE VARIANTS */
    if(!empty($variants)) {
        $this->product_variant_model->where('pid', $pid)->delete();

        foreach($variants as $variant) {
            $save_variant = [
              'pid' => $pid,
              'unit' => $variant->variant_unit,
              'unit_value' => $variant->variant_unit_value,
              'price' => $variant->variant_price,
              'stock' => $variant->variant_qty,
            ];

            $this->product_variant_model->save($save_variant);
        }

        // echo "<pre>".print_r($variants, 1)."</pre>";
    }
    }
        $data_arr = array("success" => TRUE,"message" => 'Product Saved!');
      // } else {
      //   $data_arr = array("success" => FALSE,"message" => 'Validation Error!');
      // }
    } else {
      $data_arr = array("success" => FALSE,"message" => 'No posted data!');
    }
    die(json_encode($data_arr));
  }
  
  //   /**
  //  * This function will update order status completed
  //  * @param  int    id  The id of order
  //  * @return object A json object response with status and message
  //  */

  public function orderFullfill($id = null)
  {
    $success = true;
    if($this->request->getMethod(true) == 'POST') { 
            // prepare to save
            $save = [
                'status' => 1
            ];
            $this->order_model->update($id, $save); // update product status
        } else {
            $success = false;
        }    
        $success ? $data_arr = array("status" => 201, "success" => TRUE,"message" => 'Order completed.') : $data_arr = array("success" => FALSE,"message" => 'Invalid request.');
        die(json_encode($data_arr)); 
  }

    // ...
    /**
   * This function will delete a product into the server
   * @param int pid The pid of the prodcut to be remove 
   * @return object a success indicator and the message
  */
    public function delete_product($pid){
      $this->product_model->update($pid, ['archived' => 1]);
      die(json_encode(array("success" => TRUE,"message" => 'Product Delete!')));
    }
}
