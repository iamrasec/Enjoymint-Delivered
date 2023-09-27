<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use Exception;
use ReflectionException;

class Promotion extends ResourceController
{
  public function __construct() {
    $this->data = [];
    $this->promo_model = model('PromoModel');
    $this->promoProducts_model = model('PromoProductsModel');
    $this->product_model = model('ProductModel');
    $this->mechanics_model = model('MechanicsModel');
  } 

  public function add()
  {
    $session = session();
    helper(['form', 'functions']); // load helpers
    addJSONResponseHeader(); // set response header to json
    $validation =  \Config\Services::validation();
    if($this->request->getPost()) {
      $rules = [
        'title' => 'required|min_length[3]',
        'promo_url' => 'min_length[3]|max_length[100]',
        'description' => 'required|min_length[10]',
      ];

      if($this->validate($rules)) {
        $data['validation'] = $this->validator;

        $sale_start_date = "";
        $sale_end_date = "";

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
        
        // Save Mechanics

        $condition = $this->request->getVar('mechanics');
        // $prod_id = $condition[0]['id'];

        // print_r($condition);
        $jsonData = json_decode($condition, true);
          
        // data mapping for PRODUCTS table save
        $to_save = [
          
          'title' => $this->request->getVar('title'),
          'url' => $this->request->getVar('promo_url'),
          'description' => $this->request->getVar('description'),
          'promo_type' => $this->request->getVar('promo_type'),
          'discount_value' => $this->request->getVar('discount_val'),
          'promo_code' => $this->request->getVar('promo_code'),
          'usage_limit' => $this->request->getVar('usage_limit'),
          'show_products' => $this->request->getVar('show_products'),
          'max_prod_discounted' => $this->request->getVar('max_prod_discounted'),
          'mechanics' => $condition,
          'start_date' => $sale_start_date,
          'end_date' => $sale_end_date,
          
        ];
          
         $this->promo_model->save($to_save); // trying to save product to database
         $promortionId = $this->promo_model->insertID();

         // Save Promotion
          // $get_prods = $this->promo_model->select('mechanics')->get()->getResultArray();

          // foreach($condition as $get_prod) {
            // $mechanics = $get_prod['mechanics'];
          
            // $data_condition = json_decode($conditions, true);
          // if($this->request->getVar('promo_type') == "Percentage Off (%)"){
            
          //   $savePro = [          
          //     'promo_id' => $promortionId,
          //     'discounted_product_id' => 0,
          //     'required_product_id' => 0, 
          //     'discounted_product' => 'any',
          //   ];

          // }
            
            if(!empty($jsonData)){
              foreach($jsonData as $json){
              // foreach($json['products_specific'] as $product){             
              
            if($json['req_purchase'] == 0){
              // foreach($json['products_specific'] as $prod_spec){
                if(!empty($json['products_specific'])){
                foreach ($json['products_specific'] as $prod_spec) {
                  $new_array[] = $prod_spec['id'];
               }
              
            $savePro = [
              'promo_id' => $promortionId,
              'promo_product' => $json['promo_products'],
              'discounted_specific_product' => implode(',', $new_array),
              'discounted_category_id' =>0,
              'require_purchase' => 'none',
              'required_product_id' => 0,
              'required_category_id' => 0,
              'required_qty_purchase' => 0,
              'qty_product_discounted' => 0,
            ];
           
           }
          elseif(!empty($json['products_cat'])){
            // foreach($json['products_specific'] as $prod_spec){
              foreach ($json['products_cat'] as $prod_cat) {
                $new_array1[] = $prod_cat['id'];
                // print_r($new_array);
             }
            
          $savePro = [
            'promo_id' => $promortionId,
            'promo_product' => $json['promo_products'],
            'discounted_specific_product' => 0,
            'discounted_category_id' => implode(',', $new_array1),
            'require_purchase' => 'none',
            'required_product_id' => 0,
            'required_category_id' => 0,
            'required_qty_purchase' => 0,
            'qty_product_discounted' => 0,
          ];
         
        }
        else{
          $savePro = [
          'promo_id' => $promortionId,
          'promo_product' => $json['promo_products'],
          'discounted_specific_product' => 0,
          'discounted_category_id' =>0,
          'require_purchase' => 'none',
          'required_product_id' => 0,
          'required_category_id' => 0,
          'required_qty_purchase' => 0,
          'qty_product_discounted' => 0,
        ];
       }
         }
         if($json['req_purchase'] == 1){
          if(!empty($json['products_specific'])){
          foreach ($json['products_specific'] as $prod_spec) {
            $new_array[] = $prod_spec['id'];
         }
         if(!empty($json['req_pp_specific'])){
         foreach ($json['req_pp_specific'] as $req_spec) {
          $new_array1[] = $req_spec['id'];
          $new_array2[] = null;
       }
      }
      if(!empty($json['req_pp_cat'])){
       foreach ($json['req_pp_cat'] as $req_cat) {
        $new_array2[] = $req_cat['id'];
        $new_array1[] = null;
     }
    }
         $savePro = [
          'promo_id' => $promortionId,
          'promo_product' => $json['promo_products'],
          'discounted_specific_product' => implode(',', $new_array),
          'discounted_category_id' => $json['products_cat'],
          'require_purchase' => $json['req_purchase'],
          'required_product_id' => implode(',', $new_array1),
          'required_category_id' => implode(',', $new_array2),
          'required_qty_purchase' => $json['req_purchase'],
          'qty_product_discounted' => $json['req_pp_qty'],
        ];
       }
       elseif(!empty($json['products_cat']))
       {
        foreach ($json['products_cat'] as $prod_cat) {
          $new_array[] = $prod_cat['id'];
       }
       if(!empty($json['req_pp_specific'])){
       foreach ($json['req_pp_specific'] as $req_spec) {
        $new_array1[] = $req_spec['id'];
        $new_array2[] = 0;
     }
    }
    if(!empty($json['req_pp_cat'])){
     foreach ($json['req_pp_cat'] as $req_cat) {
      $new_array2[] = $req_cat['id'];
      $new_array1[] = 0;
   }
  }
       $savePro = [
        'promo_id' => $promortionId,
        'promo_product' => $json['promo_products'],
        'discounted_specific_product' => $json['products_specific'],
        'discounted_category_id' => implode(',', $new_array),
        'require_purchase' => $json['req_purchase'],
        'required_product_id' => implode(',', $new_array1),
        'required_category_id' => implode(',', $new_array2),
        'required_qty_purchase' => $json['req_purchase'],
        'qty_product_discounted' => $json['req_pp_qty'],
      ];
       }
       else
       {
        if(!empty($json['req_pp_specific'])){
          foreach ($json['req_pp_specific'] as $req_spec) {
           $new_array1[] = $req_spec['id'];
           $new_array2[] = 0;
        }
       }
       if(!empty($json['req_pp_cat'])){
        foreach ($json['req_pp_cat'] as $req_cat) {
         $new_array2[] = $req_cat['id'];
         $new_array1[] = 0;
      }
     }
        $savePro = [
          'promo_id' => $promortionId,
          'promo_product' => $json['promo_products'],
          'discounted_specific_product' => $json['products_specific'],
          'discounted_category_id' => $json['products_specific'],
          'require_purchase' => $json['req_purchase'],
          'required_product_id' => implode(',', $new_array1),
          'required_category_id' => implode(',', $new_array2),
          'required_qty_purchase' => $json['req_purchase'],
          'qty_product_discounted' => $json['req_pp_qty'],
        ];
       }
        }
        }   
            $this->promoProducts_model->save($savePro);
          

         }
      
        $data_arr = array("success" => TRUE,"message" => 'Promotion Saved!');
      } else {
        $validationError = json_encode($validation->getErrors());
        $data_arr = array("success" => FALSE,"message" => 'Validation Error!'.$validationError);
      }
    } else {
      $data_arr = array("success" => FALSE,"message" => 'No posted data!');
    }
    die(json_encode($data_arr));
  }

  public function edit_promotion($id)
  {
    $session = session();
    helper(['form', 'functions']); // load helpers
    addJSONResponseHeader(); // set response header to json
    $validation =  \Config\Services::validation();
    // if($this->request->getPost()) {
    //   $rules = [
    //     'title' => 'required|min_length[3]',
    //     'promo_url' => '',
    //     'description' => '',
    //   ];

    //   if($this->validate($rules)) {
    //     $data['validation'] = $this->validator;

        $sale_start_date = "";
        $sale_end_date = "";

        if($this->request->getVar('sale_start_date')) {
          $sale_start_date_raw = $this->request->getVar('sale_start_date');
          $get_start_time = explode(" ", $sale_start_date_raw);
          print_r($get_start_time);
          $sale_start_date = $get_start_time[0] ." ". date('H:i:s', strtotime($get_start_time[1]));
        }

        if($this->request->getVar('sale_end_date')) {
          $sale_end_date_raw = $this->request->getVar('sale_end_date');
          $get_end_time = explode(" ", $sale_end_date_raw);
          $sale_end_date = $get_end_time[0] ." ". date('H:i:s', strtotime($get_end_time[1]));
        }
        
        // Save Mechanics

        $condition = $this->request->getVar('mechanics');
        // $prod_id = $condition[0]['id'];

        // print_r($condition);
        $jsonData = json_decode($condition, true);
          
        // data mapping for PRODUCTS table save
        $to_save = [
          
          'title' => $this->request->getVar('title'),
          'url' => $this->request->getVar('promo_url'),
          'description' => $this->request->getVar('description'),
          'promo_type' => $this->request->getVar('promo_type'),
          'discount_value' => $this->request->getVar('discount_val'),
          'promo_code' => $this->request->getVar('promo_code'),
          'usage_limit' => $this->request->getVar('usage_limit'),
          'show_products' => $this->request->getVar('show_products'),
          'max_prod_discounted' => $this->request->getVar('max_prod_discounted'),
          'mechanics' => $condition,
          'start_date' => $sale_start_date,
          'end_date' => $sale_end_date,
          
        ];
          
        //  $this->promo_model->save($to_save); // trying to save product to database
         $this->promo_model->set($to_save)->where('id', $id)->update(); // trying to update promo to database
         $promortionId = $this->promo_model->insertID();

         // Save Promotion
          // $get_prods = $this->promo_model->select('mechanics')->get()->getResultArray();

          // foreach($condition as $get_prod) {
            // $mechanics = $get_prod['mechanics'];
          
            // $data_condition = json_decode($conditions, true);
          // if($this->request->getVar('promo_type') == "Percentage Off (%)"){
            
          //   $savePro = [          
          //     'promo_id' => $promortionId,
          //     'discounted_product_id' => 0,
          //     'required_product_id' => 0, 
          //     'discounted_product' => 'any',
          //   ];

          // }
            
            if(!empty($jsonData)){
              foreach($jsonData as $json){
              // foreach($json['products_specific'] as $product){             
              
            if($json['req_purchase'] == 0){
              // foreach($json['products_specific'] as $prod_spec){
                if(!empty($json['products_specific'])){
                foreach ($json['products_specific'] as $prod_spec) {
                  $new_array[] = $prod_spec['id'];
               }
              
            $savePro = [
              'promo_id' => $promortionId,
              'promo_product' => $json['promo_products'],
              'discounted_specific_product' => implode(',', $new_array),
              'discounted_category_id' =>0,
              'require_purchase' => 'none',
              'required_product_id' => 0,
              'required_category_id' => 0,
              'required_qty_purchase' => 0,
              'qty_product_discounted' => 0,
            ];
           
           }
          elseif(!empty($json['products_cat'])){
            // foreach($json['products_specific'] as $prod_spec){
              foreach ($json['products_cat'] as $prod_cat) {
                $new_array1[] = $prod_cat['id'];
                // print_r($new_array);
             }
            
          $savePro = [
            'promo_id' => $promortionId,
            'promo_product' => $json['promo_products'],
            'discounted_specific_product' => 0,
            'discounted_category_id' => implode(',', $new_array1),
            'require_purchase' => 'none',
            'required_product_id' => 0,
            'required_category_id' => 0,
            'required_qty_purchase' => 0,
            'qty_product_discounted' => 0,
          ];
         
        }
        else{
          $savePro = [
          'promo_id' => $promortionId,
          'promo_product' => $json['promo_products'],
          'discounted_specific_product' => 0,
          'discounted_category_id' =>0,
          'require_purchase' => 'none',
          'required_product_id' => 0,
          'required_category_id' => 0,
          'required_qty_purchase' => 0,
          'qty_product_discounted' => 0,
        ];
       }
         }
         if($json['req_purchase'] == 1){
          if(!empty($json['products_specific'])){
          foreach ($json['products_specific'] as $prod_spec) {
            $new_array[] = $prod_spec['id'];
         }
         if(!empty($json['req_pp_specific'])){
         foreach ($json['req_pp_specific'] as $req_spec) {
          $new_array1[] = $req_spec['id'];
          $new_array2[] = null;
       }
      }
      if(!empty($json['req_pp_cat'])){
       foreach ($json['req_pp_cat'] as $req_cat) {
        $new_array2[] = $req_cat['id'];
        $new_array1[] = null;
     }
    }
         $savePro = [
          'promo_id' => $promortionId,
          'promo_product' => $json['promo_products'],
          'discounted_specific_product' => implode(',', $new_array),
          'discounted_category_id' => $json['products_cat'],
          'require_purchase' => $json['req_purchase'],
          'required_product_id' => implode(',', $new_array1),
          'required_category_id' => implode(',', $new_array2),
          'required_qty_purchase' => $json['req_purchase'],
          'qty_product_discounted' => $json['req_pp_qty'],
        ];
       }
       elseif(!empty($json['products_cat']))
       {
        foreach ($json['products_cat'] as $prod_cat) {
          $new_array[] = $prod_cat['id'];
       }
       if(!empty($json['req_pp_specific'])){
       foreach ($json['req_pp_specific'] as $req_spec) {
        $new_array1[] = $req_spec['id'];
        $new_array2[] = 0;
     }
    }
    if(!empty($json['req_pp_cat'])){
     foreach ($json['req_pp_cat'] as $req_cat) {
      $new_array2[] = $req_cat['id'];
      $new_array1[] = 0;
   }
  }
       $savePro = [
        'promo_id' => $promortionId,
        'promo_product' => $json['promo_products'],
        'discounted_specific_product' => $json['products_specific'],
        'discounted_category_id' => implode(',', $new_array),
        'require_purchase' => $json['req_purchase'],
        'required_product_id' => implode(',', $new_array1),
        'required_category_id' => implode(',', $new_array2),
        'required_qty_purchase' => $json['req_purchase'],
        'qty_product_discounted' => $json['req_pp_qty'],
      ];
       }
       else
       {
        if(!empty($json['req_pp_specific'])){
          foreach ($json['req_pp_specific'] as $req_spec) {
           $new_array1[] = $req_spec['id'];
           $new_array2[] = 0;
        }
       }
       if(!empty($json['req_pp_cat'])){
        foreach ($json['req_pp_cat'] as $req_cat) {
         $new_array2[] = $req_cat['id'];
         $new_array1[] = 0;
      }
     }
        $savePro = [
          'promo_id' => $promortionId,
          'promo_product' => $json['promo_products'],
          'discounted_specific_product' => $json['products_specific'],
          'discounted_category_id' => $json['products_specific'],
          'require_purchase' => $json['req_purchase'],
          'required_product_id' => implode(',', $new_array1),
          'required_category_id' => implode(',', $new_array2),
          'required_qty_purchase' => $json['req_purchase'],
          'qty_product_discounted' => $json['req_pp_qty'],
        ];
       }
        }
        }   
            // $this->promoProducts_model->save($savePro);
            $this->promoProducts_model->set($savePro)->where('id', $id)->update(); // trying to update promo to database
          

         }
      
         $data_arr = array("success" => TRUE,"message" => 'Promotion Update!');
    //   } else {
    //     $validationError = json_encode($validation->getErrors());
    //     $data_arr = array("success" => FALSE,"message" => 'Validation Error!'.$validationError);
    //   }
    // } else {
    //   $data_arr = array("success" => FALSE,"message" => 'No posted data!');
    // }
    die(json_encode($data_arr));
  }
  

  // ...
}