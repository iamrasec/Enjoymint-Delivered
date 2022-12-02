<?php

namespace App\Controllers;

use CodeIgniter\I18n\Time;

class Experience extends BaseController
{
    var $view_data = array();

    public function __construct() {
        helper(['jwt', 'date']);
    
        $this->data = [];
        $this->role = session()->get('role');
        $this->isLoggedIn = session()->get('isLoggedIn');
        $this->guid = session()->get('guid');
        $this->product_model = model('ProductModel');
        $this->strain_model = model('StrainModel');
        $this->brand_model = model('BrandModel');
        $this->category_model = model('CategoryModel');
        $this->measurement_model = model('MeasurementModel');
        $this->experience_model = model('ExperienceModel');
    
        $this->data['user_jwt'] = ($this->guid != '') ? getSignedJWTForUser($this->guid) : '';
        $this->image_model = model('ImageModel');
        $this->product_variant_model = model('ProductVariantModel');

        date_default_timezone_set('America/Los_Angeles');
    
        // if($this->isLoggedIn !== 1 && $this->role !== 1) {                                                                                                                                                              
        //   return redirect()->to('/');
        // }
    }

    public function index($url)
    {  $session = session();
        $search= $this->request->getGet('inputdata');

        $experience = $this->experience_model->where('url', $url)->get()->getResult()[0]; 
        $session->exp_id = $experience;
        if(!empty($search)){
            
            $all_products = $this->product_model->where('url', $experience->url)->experienceGetProducts($search);
            
        }else{
        $experience = $this->experience_model->where('url', $url)->get()->getResult()[0]; 
        
        $page_title = $experience->name;
        
        $this->data['page_body_id'] = "shop";
        $this->data['breadcrumbs'] = [
        'parent' => [],
        'current' => $page_title,
        ];
        $this->data['page_title'] = $page_title;
        // $this->data['products'] = $this->product_model->get()->getResult();
        // $this->data['products'] = $this->product_model->getAllProducts();
        
        $all_products = $this->experience_model->experienceGetProductsPaginate($experience->id);
    }
        // echo "<pre>".print_r($all_products, 1)."</pre>"; die();

        $product_arr = [];
        $count = 0;
        foreach($all_products as $product) {
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
        $this->data['pager'] = $this->experience_model->pager;
        $this->data['categories'] = $this->category_model->get()->getResult();
        $this->data['brands'] = $this->brand_model->get()->getResult();
        $this->data['strains'] = $this->strain_model->get()->getResult(); 
        $this->data['guid'] = $this->guid;

        // Generate current date/time (PDT/PST)
        $currDate = new Time("now", "America/Los_Angeles", "en_EN");

        // If current time is more than 6PM, generate tomorrow's date/time (PDT/PST)
        if($currDate->format('H') > '18') {
        $currDate = new Time("tomorrow", "America/Los_Angeles", "en_EN");
        }

        $this->data['currDate'] = $currDate;
        $session->currDate = $this->data['currDate'];
        return view('experience_view', $this->data);
    }

     public function searchProduct(){
        $session = session();
        $search= $this->request->getGet('inputdata1');
        $data = $session->get('exp_id');
       
        // $experience = $this->experience_model->where('url', $url)->get()->getResult()[0]; 
        if(!empty($search)){
             $exp_id = $data->id;
             $search= $this->request->getGet('inputdata1');
            //$all_products = $this->experience_model->experienceGetProducts($search, $exp_url);
            $all_products = $this->experience_model->experienceGetProducts($exp_id, $search);
        }else{
        
        
        
        // $this->data['products'] = $this->product_model->get()->getResult();
        // $this->data['products'] = $this->product_model->getAllProducts();
        
        $all_products = $this->experience_model->experienceGetProductsPaginate($data->id);
    }
        // echo "<pre>".print_r($all_products, 1)."</pre>"; die();
        $page_title = $data->name;
        
        $this->data['page_body_id'] = "shop";
        $this->data['breadcrumbs'] = [
        'parent' => [],
        'current' => $page_title,
        ];
        $this->data['page_title'] = $page_title;

        $product_arr = [];
        $count = 0;
        foreach($all_products as $product) {
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
        $this->data['pager'] = $this->experience_model->pager;
        $this->data['categories'] = $this->category_model->get()->getResult();
        $this->data['brands'] = $this->brand_model->get()->getResult();
        $this->data['strains'] = $this->strain_model->get()->getResult(); 
        $this->data['currDate'] = $session->get('currDate');;
        return view('experience_view', $this->data);
     }

     public function filterProduct(){
        $session = session();
        $searchData = $this->request->getGet();
        $data = $session->get('exp_id');
        
        // $search = "";

        $this->data['current_filter'] = [];

        // echo "<pre>".print_r($searchData, 1)."</pre>";
        //$all_products = $this->product_model->paginate(30);
       if(!empty($searchData['page'])){
        $page = $searchData['page'];
       }else{
        $page = null;
       }      
       
        // $experience = $this->experience_model->where('url', $url)->get()->getResult()[0]; 
        // if(!empty($searchData)){
        //      $exp_id = $data->id;
        //      $data = $session->get('exp_id');
        //     //$all_products = $this->experience_model->experienceGetProducts($search, $exp_url);
        //     $all_products = $this->experience_model->experienceGetProducts($exp_id, $searchData);
        // }else{
        // $all_products = $this->experience_model->experienceGetProductsPaginate($data->id);

        if(empty($searchData)){
            $exp_id = $data->id;
            // $all_products = $this->product_model->paginate(30);
            $all_products = $this->experience_model->experienceGetAllProduct($exp_id);
        }else{
            if($page != null){
                $exp_id = $data->id;
                // $all_products = $this->product_model->paginate(30);
                $all_products = $this->experience_model->experienceGetAllProduct($exp_id);
            }else{
            $exp_id = $data->id;
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
            $all_products = $this->experience_model->getDataWithParam($exp_id, $category, $min_price, $max_price, $strain, $brands, $min_thc, $max_thc, $min_cbd, $max_cbd, $availability);

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
        
    
        // echo "<pre>".print_r($all_products, 1)."</pre>"; die();
        $page_title = $data->name;
        
        $this->data['page_body_id'] = "shop";
        $this->data['breadcrumbs'] = [
        'parent' => [],
        'current' => $page_title,
        ];
        $this->data['page_title'] = $page_title;

        $product_arr = [];
        $count = 0;
        foreach($all_products as $product) {
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
        $this->data['pager'] = $this->experience_model->pager;
        $this->data['categories'] = $this->category_model->get()->getResult();
        $this->data['brands'] = $this->brand_model->get()->getResult();
        $this->data['strains'] = $this->strain_model->get()->getResult(); 
        $this->data['currDate'] = $session->get('currDate');;
        return view('experience_view', $this->data);
     }
}
