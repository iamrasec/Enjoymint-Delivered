<?php

namespace App\Controllers;

class Home extends BaseController
{
    var $view_data = array();

    public function __construct() {
        helper(['jwt']);
    
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
        $this->experience_model = model('ExperienceModel');
        $this->location_model = model('LocationModel');
    
        $this->data['user_jwt'] = ($this->guid != '') ? getSignedJWTForUser($this->guid) : '';
        $this->image_model = model('ImageModel');
        $this->product_variant_model = model('ProductVariantModel');
    
        if($this->isLoggedIn !== 1 && $this->role !== 1) {
          return redirect()->to('/');
        }
    }

    public function index()
    {   
        $session = session();
        $location = $session->get('search1');
        $this->data['location_keyword'] = $location; 
        $page_title = 'Home';

        $this->data['page_body_id'] = "home";
        $this->data['breadcrumbs'] = [
        'parent' => [],
        'current' => $page_title,
        ];
        $this->data['page_title'] = $page_title;

        // $all_products = $this->product_model->get()->getResult();
        $all_products = $this->product_model->getPopularProducts(10);

        // echo "<pre>".print_r($all_products)."</pre>";die();

        $image = imageExperience();
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
        // $this->data['experience'] = $experiences;
        $this->data['images'] = $image;
         $this->data['experience'] = $this->experience_model->get()->getResult();
        $this->data['categories'] = $this->category_model->get()->getResult();
        return view('home_view', $this->data);
    }
}
