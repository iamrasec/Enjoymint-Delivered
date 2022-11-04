<?php

namespace App\Controllers;

class Shop extends BaseController
{
    var $view_data = array();

    public function __construct() {
        helper(['jwt']);
    
        $this->data = [];
        $this->role = session()->get('role');
        $this->isLoggedIn = session()->get('isLoggedIn');
        $this->guid = session()->get('guid');
        $this->product_model = model('ProductModel');
        $this->strain_model = model('StrainModel');
        $this->brand_model = model('BrandModel');
        $this->category_model = model('CategoryModel');
        $this->measurement_model = model('MeasurementModel');
        $this->image_model = model('ImageModel');
        $this->brand_model = model('BrandModel');
        $this->strain_model = model('StrainModel');
        $this->productcategory_model = model('ProductCategory');
    
        $this->data['user_jwt'] = ($this->guid != '') ? getSignedJWTForUser($this->guid) : '';
        $this->image_model = model('ImageModel');
        $this->product_variant_model = model('ProductVariantModel');
    }

    public function index()
    {
        $page_title = 'Shop';

        $this->data['page_body_id'] = "shop";
        $this->data['breadcrumbs'] = [
        'parent' => [],
        'current' => $page_title,
        ];
        $this->data['page_title'] = $page_title;
        // $this->data['products'] = $this->product_model->get()->getResult();
        $searchData = $this->request->getGet();
        $search= $this->request->getGet('inputdata');
        // $search = "";

        $this->data['current_filter'] = [];

        // echo "<pre>".print_r($searchData, 1)."</pre>";
        //$all_products = $this->product_model->paginate(30);
       if(!empty($searchData['page'])){
        $page = $searchData['page'];
       }else{
        $page = null;
       }
       if(!empty($search)){
        $search = $this->request->getGet('inputdata');
        $all_products = $this->product_model->getProducts($search);
        
    }elseif(empty($searchData)){
            // $all_products = $this->product_model->paginate(30);
            $all_products = $this->product_model->getAllProducts();
        }else{
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
                
            $this->data['current_filter'] = $current_filter;
           
        }
        // $all_products = $this->product_model->paginate(30);

        // echo "<pre>".print_r($all_products, 1)."</pre>"; die();

        $product_arr = [];
        $count = 0;
        foreach($all_products as $product) {
            // echo "<pre>".print_r($product, 1)."</pre>";
             $product_arr[$count] = $product;
            if(!empty($product['images'])) {
                $imageIds = [];
                $imageIds = explode(',',$product['images']);
                $images = $this->image_model->whereIn('id', $imageIds)->get()->getResult();
                $product_arr[$count]['images'] = $images;
            }

             $count++;
        }

        // echo "<pre>".print_r($product_arr, 1)."</pre>"; die();
        
        $this->data['products'] = $product_arr;
        $this->data['pager'] = $this->product_model->pager;
        $this->data['categories'] = $this->category_model->orderBy('name', 'ASC')->get()->getResult();
        $this->data['brands'] = $this->brand_model->orderBy('name', 'ASC')->get()->getResult();
        $this->data['strains'] = $this->strain_model->orderBy('name', 'ASC')->get()->getResult();
        $this->data['fast_tracked'] = false;
        return view('shop_view', $this->data);
    }

    public function fast_tracked()
    {
        $page_title = 'Shop';

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
       if(!empty($searchData['page'])){
        $page = $searchData['page'];
       }else{
        $page = null;
       }
         if(empty($searchData)){
            // $all_products = $this->product_model->paginate(30);
            $all_products = $this->product_model->getFastTracked();
        }else{
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
                
            $this->data['current_filter'] = $current_filter;
           
        }
        // $all_products = $this->product_model->paginate(30);

        // echo "<pre>".print_r($all_products, 1)."</pre>"; die();

        $product_arr = [];
        $count = 0;
        foreach($all_products as $product) {
            // echo "<pre>".print_r($product, 1)."</pre>";
             $product_arr[$count] = $product;
            if(!empty($product['images'])) {
                $imageIds = [];
                $imageIds = explode(',',$product['images']);
                $images = $this->image_model->whereIn('id', $imageIds)->get()->getResult();
                $product_arr[$count]['images'] = $images;
            }

             $count++;
        }

        // echo "<pre>".print_r($product_arr, 1)."</pre>"; die();

        $this->data['products'] = $product_arr;
        $this->data['pager'] = $this->product_model->pager;
        $this->data['categories'] = $this->category_model->orderBy('name', 'ASC')->get()->getResult();
        $this->data['brands'] = $this->brand_model->orderBy('name', 'ASC')->get()->getResult();
        $this->data['strains'] = $this->strain_model->orderBy('name', 'ASC')->get()->getResult();
        $this->data['fast_tracked'] = true;
        return view('shop_view', $this->data);
    }
    
}