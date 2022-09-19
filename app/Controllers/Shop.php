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
        $searchData = $this->request->getPost();
        // $search = "";
       
        // print_r($searchData);
        //$all_products = $this->product_model->paginate(30);

        if($searchData == null){
            $all_products = $this->product_model->paginate(30);
        }else{
         $category = $searchData['category'];
         $price = $searchData['price'];
         $strain = $searchData['strain'];
         $brands = $searchData['brands'];
         $thc_value = $searchData['thc_value'];
         $cbd_value = $searchData['cbd_value'];
            $all_products = $this->product_model->getDataWithParam($category, $price, $strain, $brands, $thc_value, $cbd_value);
            
        }
        //  $all_products = $this->product_model->getAllProducts();
    
            
        // print_r($all_products);
        
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
   

        $this->data['products'] = $product_arr;
        $this->data['searchData'] = $searchData;
        $this->data['pager'] = $this->product_model->pager;
        $this->data['categories'] = $this->category_model->get()->getResult();
        $this->data['brands'] = $this->brand_model->get()->getResult();
       return view('shop_view', $this->data);
    }
}
 