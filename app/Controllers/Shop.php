<?php

namespace App\Controllers;

use DateTime;

class Shop extends BaseController
{
    var $view_data = array();

    public function __construct() {
        helper(['jwt', 'date']);
    
        $this->data = [];
        $this->role = session()->get('role');
        $this->isLoggedIn = session()->get('isLoggedIn');
        $this->guid = session()->get('guid');
        $this->uid = session()->get('id');
        $this->product_model = model('ProductModel');
        $this->strain_model = model('StrainModel');
        $this->brand_model = model('BrandModel');
        $this->category_model = model('CategoryModel');
        $this->measurement_model = model('MeasurementModel');
        $this->image_model = model('ImageModel');
        $this->brand_model = model('BrandModel');
        $this->strain_model = model('StrainModel');
        $this->location_model = model('LocationModel');
        $this->productcategory_model = model('ProductCategory');
        $this->user_model = model('UserModel');
        $this->discount_model = model('DiscountModel');
        $this->promo_model = model('PromoModel');
        
        $this->data['user_jwt'] = ($this->guid != '') ? getSignedJWTForUser($this->guid) : '';
        $this->image_model = model('ImageModel');
        $this->product_variant_model = model('ProductVariantModel');

        date_default_timezone_set('America/Los_Angeles');
    }

    public function index()
    { 
        $session = session();
        $search_data = $session->get('search');
        // $location = $session->get('search1');
        $user_id = $this->uid;
        $page_title = 'Shop';

        $this->data['page_body_id'] = "shop";
        $this->data['breadcrumbs'] = [
        'parent' => [],
        'current' => $page_title,
        ];
        $this->data['page_title'] = $page_title;
        // $this->data['products'] = $this->product_model->get()->getResult();
        $searchData = $this->request->getGet();
        //$search= $this->request->getGet('inputdata');
        // $search = "";

        $this->data['current_filter'] = [];

        // echo "<pre>".print_r($searchData, 1)."</pre>";
        // $all_products = $this->product_model->paginate(30);

       if(!empty($searchData['page'])){
        $page = $searchData['page'];
       }else{
        $page = null;
       }
      
        if(empty($searchData)) {
            // $all_products = $this->product_model->paginate(30);
            $all_products = $this->product_model->getAllProducts();
        }
        else {
            if($page != null) {
                // $all_products = $this->product_model->paginate(30);
                $all_products = $this->product_model->getAllProducts();
            }
            else {   

                    $category = $searchData['category'];
                    $min_price = $searchData['min_price'];
                    $max_price = $searchData['max_price'];
                    $strain = $searchData['strain'];
                    $brands = $searchData['brands'];
                    $min_thc = $searchData['min_thc'];      
                    $max_thc = $searchData['max_thc'];
                    $min_cbd = $searchData['min_cbd'];
                    $max_cbd = $searchData['max_cbd'];
                    // $availability = $searchData['availability'];
                    $all_products = $this->product_model->getDataWithParamFast_Tracked($category, $min_price, $max_price, $strain, $brands, $min_thc, $max_thc, $min_cbd, $max_cbd);

                    // echo "<pre>".print_r($this->product_model->getLastQuery()->getQuery(), 1)."</pre>";

                    $current_filter = [
                        'category' => $searchData['category'],
                        'strain' => $searchData['strain'],
                        'brands' => $searchData['brands'],
                        'min_price' => $searchData['min_price'],
                        'max_price' => $searchData['max_price'],
                        'min_thc' => $searchData['min_thc'],
                        'max_thc' => $searchData['max_thc'],
                        'min_cbd' => $searchData['min_cbd'],
                        'max_cbd' => $searchData['max_cbd'],
                    ];
                    
                    // echo "<pre>".print_r($this->product_model->getLastQuery()->getQuery(), 1)."</pre>"
                
                    $this->data['current_filter'] = $current_filter;
            
                    $session->filter = $this->data['current_filter'];
                    // $search_location = $this->request->getPost('location');
                                
                    // echo "<pre>".print_r($product_arr, 1)."</pre>"; die();
       
                }   
            }
        
        // $all_products = $this->product_model->paginate(30);
        // echo "<pre>".print_r($all_products, 1)."</pre>"; die();
        // echo "<pre>".print_r($this->data['currDate']->format('H'), 1)."</pre>";die();

        if(empty($search)) {
            $this->data['search_keyword'] = null;
        }
        else {  
            $this->data['search_keyword'] = $search_data;
            $this->data['location_delivery'] = $delivery;
        }  

        $product_arr = [];             
        $count = 0;
        $price = [];
        foreach($all_products as $product) {
            // echo "<pre>".print_r($product, 1)."</pre>";
            if($product['on_sale'] == 1){
                $discount = $this->discount_model->where('pid', $product['id'])->get()->getResult();
                if(!empty($discount)){
                if($discount[0]->discount_attribute == "percent"){
                $new_price = $product['price'] * ($discount[0]->discount_value /100);
                 $sale_price = $product['price'] - $new_price;
                // print_r($this->data['sale_price']);
                }elseif($discount[0]->discount_attribute == "fixed"){
                    $sale_price = $product['price'] - $discount[0]->discount_value ;
                }elseif($discount[0]->discount_attribute == "sale_price"){
                    $sale_price = $discount[0]->discount_value;
                }
                $price[$count] = $sale_price;
                
            }
        }
             $product_arr[$count] = $product;

            if(!empty($product['images'])) {
                $imageIds = [];
                $imageIds = explode(',',$product['images']);
                $images = $this->image_model->whereIn('id', $imageIds)->get()->getResult();
                $product_arr[$count]['images'] = $images;
            }
            // If product has variants, get variants data
            if($product['has_variant'] == 1) {
                $product_arr[$count]['variants'] = $this->product_variant_model->where('pid', $product['id'])->get()->getResult();
            }
             $count++;
        }

        if($user_id == null) {
            $session->setFlashdata('message', 'Please login first');
        }
         $location = $this->location_model->where('user_id', $user_id )->select('address')->first();
         $delivery = $this->location_model->where('user_id', $user_id )->select('delivery_schedule')->first();
         if($location != null && $delivery != null) {
            $this->data['location_keyword'] = $location;
            $this->data['location_delivery'] = $delivery;
         }
         else{
            $this->data['location_keyword'] = "";
            $this->data['location_delivery'] = [];
         }
        $this->data['uid'] = $user_id;
        $this->data['sale_price'] = $price;
        $this->data['products'] = $product_arr;
        $this->data['pager'] = $this->product_model->pager;
        $this->data['categories'] = $this->category_model->getAllCategory();
        $this->data['brands'] = $this->brand_model->orderBy('name', 'ASC')->get()->getResult();
        $this->data['strains'] = $this->strain_model->orderBy('name', 'ASC')->get()->getResult();
        $this->data['fast_tracked'] = false;
        $this->data['currDate'] = new \CodeIgniter\I18n\Time("tomorrow", "America/Los_Angeles", "en_EN");

        if($this->data['currDate']->format('H') > '18') {
            $this->data['currDate'] = new \CodeIgniter\I18n\Time("tomorrow", "America/Los_Angeles", "en_EN");
        }

        $session->filter = $this->data['current_filter'];
        // echo "<pre>".print_r($this->data, 1)."</pre>";die();

        // echo "<pre>".print_r($this->data['currDate']->format('H'), 1)."</pre>";die();
        //  $this->location();

         return view('shop_view', $this->data);
    }
        
    public function fast_tracked()                 
    {         
        $session = session();
        $location = $session->get('search1');
        $page_title = 'Shop';
        $user_id = $this->uid;        
        
        $this->data['page_body_id'] = "shop";
        
        $this->data['breadcrumbs'] = [
            'parent' => [],
            'current' => $page_title,
        ];

        $this->data['page_title'] = $page_title;
        
        // $this->data['products'] = $this->product_model->get()->getResult();
        $searchData = $this->request->getGet();
        // $search = "";

        $this->data['current_filter'] = [];

        // echo "<pre>".print_r($searchData, 1)."</pre>";

        //$all_products = $this->product_model->paginate(30);
        if(!empty($searchData['page'])) {
            $page = $searchData['page'];
        }
        else {           
            $page = null;
        }  

        if(empty($searchData)) {
            // $all_products = $this->product_model->paginate(30);
            $all_products = $this->product_model->getFastTracked();
        }
        else {
            if($searchData['availability'] == 1) {
                $category = $searchData['category'];
                $min_price = $searchData['min_price'];
                $max_price = $searchData['max_price'];
                $strain = $searchData['strain'];
                $brands = $searchData['brands'];
                $min_thc = $searchData['min_thc'];
                $max_thc = $searchData['max_thc'];
                $min_cbd = $searchData['min_cbd'];
                $max_cbd = $searchData['max_cbd'];
                $availability = $searchData['availability'];
                $all_products = $this->product_model->getDataWithParam($category, $min_price, $max_price, $strain, $brands, $min_thc, $max_thc, $min_cbd, $max_cbd, $availability);

                // echo "<pre>".print_r($this->product_model->getLastQuery()->getQuery(), 1)."</pre>";

                $current_filter = [
                    'category' => $searchData['category'],
                    'strain' => $searchData['strain'],
                    'brands' => $searchData['brands'],
                    'min_price' => $searchData['min_price'],
                    'max_price' => $searchData['max_price'],
                    'min_thc' => $searchData['min_thc'],
                    'max_thc' => $searchData['max_thc'],
                    'min_cbd' => $searchData['min_cbd'],
                    'max_cbd' => $searchData['max_cbd'],
                    'availability' => $searchData['availability'],
                ];

                if(empty($search)) {
                    $this->data['search_keyword'] = null;
                }
                else {  
                    $this->data['search_keyword'] = $search_data;
                }  

                $this->data['current_filter'] = $current_filter;

                $product_arr = [];
                $count = 0;
                $price = [];
                foreach($all_products as $product) {
                    // echo "<pre>".print_r($product, 1)."</pre>";
                    if($product['on_sale'] == 1){
                        $discount = $this->discount_model->where('pid', $product['id'])->get()->getResult();
                        if(!empty($discount)){
                        if($discount[0]->discount_attribute == "percent"){
                        $new_price = $product['price'] * ($discount[0]->discount_value /100);
                         $sale_price = $product['price'] - $new_price;
                        // print_r($this->data['sale_price']);
                        }elseif($discount[0]->discount_attribute == "fixed"){
                            $sale_price = $product['price'] - $discount[0]->discount_value ;
                        }elseif($discount[0]->discount_attribute == "sale_price"){
                            $sale_price = $discount[0]->discount_value;
                        }
                        $price[$count] = $sale_price;
                        
                    }
                }
                    $product_arr[$count] = $product;
                    if(!empty($product['images'])) {
                        $imageIds = [];
                        $imageIds = explode(',',$product['images']);
                        $images = $this->image_model->whereIn('id', $imageIds)->get()->getResult();
                        $product_arr[$count]['images'] = $images;
                    }

                    // If product has variants, get variants data
                    if($product['has_variant'] == 1) {
                        $product_arr[$count]['variants'] = $this->product_variant_model->where('pid', $product['id'])->get()->getResult();
                    }

                    $count++;
                }

                if($user_id == null) {
                    $session->setFlashdata('message', 'Please login first');
                }

                // echo "<pre>".print_r($product_arr, 1)."</pre>"; die();
                $this->data['uid'] = $user_id;
                $this->data['sale_price'] = $price;
                $this->data['location_keyword'] = $this->location_model->where('user_id', $user_id )->select('address')->first();
                $this->data['products'] = $product_arr;
                $this->data['pager'] = $this->product_model->pager;
                $this->data['categories'] = $this->category_model->orderBy('name', 'ASC')->get()->getResult();
                $this->data['brands'] = $this->brand_model->orderBy('name', 'ASC')->get()->getResult();
                $this->data['strains'] = $this->strain_model->orderBy('name', 'ASC')->get()->getResult();
                // $this->data['fast_tracked'] = true;
                $this->data['currDate'] = new \CodeIgniter\I18n\Time("now", "America/Los_Angeles", "en_EN");

                if($this->data['currDate']->format('H') > '18') {
                    $this->data['currDate'] = new \CodeIgniter\I18n\Time("tomorrow", "America/Los_Angeles", "en_EN");
                }
                if($searchData['availability'] == 1){
                    $this->data['fast_tracked'] = false;
                }
                return view('shop_view', $this->data);

            }
            else { 
                $category = $searchData['category'];
                $min_price = $searchData['min_price'];
                $max_price = $searchData['max_price'];
                $strain = $searchData['strain'];
                $brands = $searchData['brands'];
                $min_thc = $searchData['min_thc'];
                $max_thc = $searchData['max_thc'];
                $min_cbd = $searchData['min_cbd'];
                $max_cbd = $searchData['max_cbd'];
                $availability = $searchData['availability'];
                $all_products = $this->product_model->getDataWithParamFast_Tracked($category, $min_price, $max_price, $strain, $brands, $min_thc, $max_thc, $min_cbd, $max_cbd, $availability);
    
                // echo "<pre>".print_r($this->product_model->getLastQuery()->getQuery(), 1)."</pre>";
    
                $current_filter = [
                    'category' => $searchData['category'],
                    'strain' => $searchData['strain'],
                    'brands' => $searchData['brands'],
                    'min_price' => $searchData['min_price'],
                    'max_price' => $searchData['max_price'],
                    'min_thc' => $searchData['min_thc'],
                    'max_thc' => $searchData['max_thc'],
                    'min_cbd' => $searchData['min_cbd'],
                    'max_cbd' => $searchData['max_cbd'],
                    'availability' => $searchData['availability'],
                ];
                    
                $this->data['current_filter'] = $current_filter;
                
            }
        }
        // $all_products = $this->product_model->paginate(30);

        // echo "<pre>".print_r($all_products, 1)."</pre>"; die();
        if(empty($search)) {
            $this->data['search_keyword'] = null;
        }
        else {  
            $this->data['search_keyword'] = $search_data;
        }  

        $product_arr = [];
        $count = 0;
        $price = [];
        foreach($all_products as $product) {
            // echo "<pre>".print_r($product, 1)."</pre>";
            if($product['on_sale'] == 1){
                $discount = $this->discount_model->where('pid', $product['id'])->get()->getResult();
                if(!empty($discount)){
                if($discount[0]->discount_attribute == "percent"){
                $new_price = $product['price'] * ($discount[0]->discount_value /100);
                 $sale_price = $product['price'] - $new_price;
                // print_r($this->data['sale_price']);
                }elseif($discount[0]->discount_attribute == "fixed"){
                    $sale_price = $product['price'] - $discount[0]->discount_value ;
                }elseif($discount[0]->discount_attribute == "sale_price"){
                    $sale_price = $discount[0]->discount_value;
                }
                $price[$count] = $sale_price;
                
            }
        }
             $product_arr[$count] = $product;
            if(!empty($product['images'])) {
                $imageIds = [];
                $imageIds = explode(',',$product['images']);
                $images = $this->image_model->whereIn('id', $imageIds)->get()->getResult();
                $product_arr[$count]['images'] = $images;
            }

            // If product has variants, get variants data
            if($product['has_variant'] == 1) {
                $product_arr[$count]['variants'] = $this->product_variant_model->where('pid', $product['id'])->get()->getResult();
            }

             $count++;
        }

        if($user_id == null) {
            $session->setFlashdata('message', 'Please login first');
        }
        
        // echo "<pre>".print_r($product_arr, 1)."</pre>"; die();
        $this->data['uid'] = $user_id;
        $this->data['sale_price'] = $price;
        $this->data['location_keyword'] = $this->location_model->where('user_id', $user_id )->select('address')->first();                       
        $this->data['products'] = $product_arr;
        $this->data['pager'] = $this->product_model->pager;
        $this->data['categories'] = $this->category_model->orderBy('name', 'ASC')->get()->getResult();
        $this->data['brands'] = $this->brand_model->orderBy('name', 'ASC')->get()->getResult();
        $this->data['strains'] = $this->strain_model->orderBy('name', 'ASC')->get()->getResult();
        $this->data['fast_tracked'] = true;
        $this->data['currDate'] = new \CodeIgniter\I18n\Time("now", "America/Los_Angeles", "en_EN");

        if( $this->data['currDate']->format('H') > '18') {
            $this->data['currDate'] = new \CodeIgniter\I18n\Time("tomorrow", "America/Los_Angeles", "en_EN");
        }
        return view('shop_view', $this->data);          
    }     

    public function searchProduct(){

        $session = session();
       
        $location = $session->get('search1');
        $search = $this->request->getGet('inputdata');
        $search_location = $this->request->getPost('location');  
        $user_id = $this->uid;
        $this->data['uid'] = $user_id;
        
        if($user_id == null){
            $session->setFlashdata('message', 'Please login first');
        }

        if(!empty($search)){
            
            $search = $this->request->getGet('inputdata');
            $all_products = $this->product_model->getProducts($search);  
            // print_r($all_products);
        }else{
                // $all_products = $this->product_model->paginate(30);
                $all_products = $this->product_model->getAllProducts();
            }

            if(empty($search)){
                $this->data['search_keyword'] = null;
            }else{  
                $this->data['search_keyword'] = $search;
            }
             $session->search = $this->data['search_keyword'];
        
        $page_title = 'Shop';
        
        $this->data['page_body_id'] = "shop";
        $this->data['breadcrumbs'] = [
        'parent' => [],
        'current' => $page_title,
        ];
        $this->data['page_title'] = $page_title;
        
        $product_arr = [];
        $count = 0;
        $price = [];
        foreach($all_products as $product) {
            if($product['on_sale'] == 1){
                $discount = $this->discount_model->where('pid', $product['id'])->get()->getResult();
                if(!empty($discount)){
                if($discount[0]->discount_attribute == "percent"){
                $new_price = $product['price'] * ($discount[0]->discount_value /100);
                 $sale_price = $product['price'] - $new_price;
                // print_r($this->data['sale_price']);
                }elseif($discount[0]->discount_attribute == "fixed"){
                    $sale_price = $product['price'] - $discount[0]->discount_value ;
                }elseif($discount[0]->discount_attribute == "sale_price"){
                    $sale_price = $discount[0]->discount_value;
                }
                $price[$count] = $sale_price;
                
            }
        }
            $product_arr[$count] = $product;
            if($product['images']) {
                $imageIds = [];                  
                $imageIds = explode(',',$product['images']);
                $images = $this->image_model->whereIn('id', $imageIds)->get()->getResult();
                $product_arr[$count]['images'] = $images;
            }
            
            $count++;
        }
        $user_id = $this->uid;
        if($user_id == null){
            $session->setFlashdata('message', 'Please login first');
          }
        $this->data['uid'] = $user_id;
        $this->data['sale_price'] = $price;
        $this->data['location_keyword'] = $location;   
        $this->data['products'] = $product_arr;
        $this->data['pager'] = $this->product_model->pager;
        $this->data['categories'] = $this->category_model->orderBy('name', 'ASC')->get()->getResult();
        $this->data['brands'] = $this->brand_model->orderBy('name', 'ASC')->get()->getResult();
        $this->data['strains'] = $this->strain_model->orderBy('name', 'ASC')->get()->getResult();
        $this->data['fast_tracked'] = false;
        $this->data['currDate'] = new \CodeIgniter\I18n\Time("now", "America/Los_Angeles", "en_EN");

        if($this->data['currDate']->format('H') > '18') {
            $this->data['currDate'] = new \CodeIgniter\I18n\Time("tomorrow", "America/Los_Angeles", "en_EN");
        }

        // echo "<pre>".print_r($this->data['currDate']->format('H'), 1)."</pre>";die();
       
         return view('shop_view', $this->data);
      
        
    }

    public function location(){

        $session = session();
        $search= $this->request->getPost('location');
        $user_id = $this->uid;
       
      $location = $this->location_model->verifyUser($user_id);
      $this->data['uid'] = $user_id;
      if($user_id == null){
        $session->setFlashdata('message', 'Please login first');
        return redirect()->to('/shop');
      }
      else  
      {                 
        if(!empty($search)){   
          $day = $this->request->getPost('delivery');
            $session->buttonValue = $day;
           if($day == "same_day"){
            $day = "same day";
           }
           elseif($day == "next_day"){
            $day = "next day";
           }
       
            if(empty($location['user_id'])){
                $to_save = [
                    'address' => $this->request->getVar('location'),
                    'user_id' => $this->uid,
                    'delivery_schedule' => $day,
                ];

             $this->location_model->save($to_save); 
                
            }elseif($location['user_id'] == $user_id){
                $to_save = [
                    'address' => $this->request->getVar('location'),
                    'delivery_schedule' => $day,
                 ];
                // $this->location_model->update($id, ['address' => $to_save['address']]);
                $this->location_model->set($to_save)->where('user_id', $user_id)->update();
    }
        // return view('templates/_navigation', $this->data);
    }   
    }
        
        $location = $this->location_model->where('user_id',$user_id)->select('address')->first();
        $delivery = $this->location_model->where('user_id',$user_id)->select('delivery_schedule')->first();
        $all_products = $this->product_model->getAllProducts();
        
        if(empty($location)){
            $this->data['location_keyword'] = null;
            $this->data['location_delivery'] = null;
            }else{  
            $this->data['location_keyword'] = $location;
            $this->data['location_delivery'] = $delivery;
            //return view('shop_view', $this->data);
            }
            $this->data['search_keyword'] = null;
            $this->data['location_delivery'] = $delivery;
            //  $session->search1 = $location;
      
        $page_title = 'Shop';
        
        $this->data['page_body_id'] = "shop";
        $this->data['breadcrumbs'] = [
        'parent' => [],
        'current' => $page_title,
        ];
        $this->data['page_title'] = $page_title;
        
        $product_arr = [];
        $count = 0;
        $price = [];
        foreach($all_products as $product) {
          
            if($product['on_sale'] == 1){
                $discount = $this->discount_model->where('pid', $product['id'])->get()->getResult();
                if(!empty($discount)){
                if($discount[0]->discount_attribute == "percent"){
                $new_price = $product['price'] * ($discount[0]->discount_value /100);
                 $sale_price = $product['price'] - $new_price;
                // print_r($this->data['sale_price']);
                }elseif($discount[0]->discount_attribute == "fixed"){
                    $sale_price = $product['price'] - $discount[0]->discount_value ;
                }elseif($discount[0]->discount_attribute == "sale_price"){
                    $sale_price = $discount[0]->discount_value;
                }
                $price[$count] = $sale_price;
                
            }
        }
            $product_arr[$count] = $product;
            if($product['images']) {
                $imageIds = [];                  
                $imageIds = explode(',',$product['images']);
                $images = $this->image_model->whereIn('id', $imageIds)->get()->getResult();
                $product_arr[$count]['images'] = $images;
            }
            
            $count++;
        }           
      
        $this->data['products'] = $product_arr;
        $this->data['sale_price'] = $price;
        $this->data['pager'] = $this->product_model->pager;
        $this->data['categories'] = $this->category_model->orderBy('name', 'ASC')->get()->getResult();
        $this->data['brands'] = $this->brand_model->orderBy('name', 'ASC')->get()->getResult();
        $this->data['strains'] = $this->strain_model->orderBy('name', 'ASC')->get()->getResult();
        $this->data['fast_tracked'] = false;
        $this->data['currDate'] = new \CodeIgniter\I18n\Time("now", "America/Los_Angeles", "en_EN");

        if($this->data['currDate']->format('H') > '18') {
            $this->data['currDate'] = new \CodeIgniter\I18n\Time("tomorrow", "America/Los_Angeles", "en_EN");
        }

        // echo "<pre>".print_r($this->data['currDate']->format('H'), 1)."</pre>";die();
       
         return redirect()->to('shop');

    }  
    
    public function promoPage(){
        $session = session();
        $search_data = $session->get('search');
        // $location = $session->get('search1');
        $user_id = $this->uid;
        $page_title = 'Shop';

        $this->data['page_body_id'] = "shop";
        $this->data['breadcrumbs'] = [
        'parent' => [],
        'current' => $page_title,
        ];
        $this->data['page_title'] = $page_title;
        // $this->data['products'] = $this->product_model->get()->getResult();

        if($user_id == null){
            $session->setFlashdata('message', 'Please login first');
        }
         
        $this->data['uid'] = $user_id;
        $this->data['location_keyword'] = $this->location_model->where('user_id', $user_id )->select('address')->first();
        $this->data['products'] = $this->promo_model->get()->getResult();
        // $this->data['pager'] = $this->product_model->pager;
        $this->data['categories'] = $this->category_model->getAllCategory();
        $this->data['brands'] = $this->brand_model->orderBy('name', 'ASC')->get()->getResult();
        $this->data['strains'] = $this->strain_model->orderBy('name', 'ASC')->get()->getResult();
        $this->data['fast_tracked'] = false;
        $this->data['currDate'] = new \CodeIgniter\I18n\Time("tomorrow", "America/Los_Angeles", "en_EN");

        if($this->data['currDate']->format('H') > '18') {
            $this->data['currDate'] = new \CodeIgniter\I18n\Time("tomorrow", "America/Los_Angeles", "en_EN");
        }

        // echo "<pre>".print_r($this->data['currDate']->format('H'), 1)."</pre>";die();
        //  $this->location();
         return view('promo_page', $this->data);

    }

    public function salePage(){
        $session = session();
        $search_data = $session->get('search');
        // $location = $session->get('search1');
        $user_id = $this->uid;
        $page_title = 'Shop';

        $this->data['page_body_id'] = "shop";
        $this->data['breadcrumbs'] = [
        'parent' => [],
        'current' => $page_title,
        ];
        $this->data['page_title'] = $page_title;
        // $this->data['products'] = $this->product_model->get()->getResult();

        if($user_id == null){
            $session->setFlashdata('message', 'Please login first');
        }
         $all_products = $this->product_model->where('on_sale', 1)->get()->getResult();

         $product_arr = [];
         $count = 0;
         $price = [];
         foreach($all_products as $product) {
           
             if($product->on_sale == 1){
                 $discount = $this->discount_model->where('pid', $product->id)->get()->getResult();
                 if(!empty($discount)){
                 if($discount[0]->discount_attribute == "percent"){
                  $new_price = $product->price * ($discount[0]->discount_value /100);
                  $sale_price = $product->price - $new_price;
                 // print_r($this->data['sale_price']);
                 }elseif($discount[0]->discount_attribute == "fixed"){
                     $sale_price = $product->price - $discount[0]->discount_value ;
                 }elseif($discount[0]->discount_attribute == "sale_price"){
                     $sale_price = $discount[0]->discount_value;
                 }
                 $price[$count] = $sale_price;
                 
             }
         }
             $product_arr[$count] = $product;
             if($product->images) {
                 $imageIds = [];                  
                 $imageIds = explode(',',$product->images);
                 $images = $this->image_model->whereIn('id', $imageIds)->get()->getResult();
                 $product_arr[$count]->images = $images;
             }
             
             $count++;
         }

        $this->data['uid'] = $user_id;
        $this->data['location_keyword'] = $this->location_model->where('user_id', $user_id )->select('address')->first();
        $this->data['products'] =$product_arr;
        $this->data['sale_price'] = $price;
        // $this->data['pager'] = $this->product_model->pager;
        $this->data['categories'] = $this->category_model->getAllCategory();
        $this->data['brands'] = $this->brand_model->orderBy('name', 'ASC')->get()->getResult();
        $this->data['strains'] = $this->strain_model->orderBy('name', 'ASC')->get()->getResult();
        $this->data['fast_tracked'] = false;
        $this->data['currDate'] = new \CodeIgniter\I18n\Time("tomorrow", "America/Los_Angeles", "en_EN");

        if($this->data['currDate']->format('H') > '18') {
            $this->data['currDate'] = new \CodeIgniter\I18n\Time("tomorrow", "America/Los_Angeles", "en_EN");
        }

        // echo "<pre>".print_r($this->data['currDate']->format('H'), 1)."</pre>";die();
        //  $this->location();
         return view('sale_page', $this->data);

    }
    
}