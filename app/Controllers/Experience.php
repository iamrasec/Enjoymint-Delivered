<?php

namespace App\Controllers;

class Experience extends BaseController
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
        $this->experience_model = model('ExperienceModel');
    
        $this->data['user_jwt'] = ($this->guid != '') ? getSignedJWTForUser($this->guid) : '';
        $this->image_model = model('ImageModel');
        $this->product_variant_model = model('ProductVariantModel');
    
        if($this->isLoggedIn !== 1 && $this->role !== 1) {
          return redirect()->to('/');
        }
    }

    public function index($url)
    {
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
       
        return view('experience_view', $this->data);
    }
}
