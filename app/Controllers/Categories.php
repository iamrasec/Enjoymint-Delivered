<?php

namespace App\Controllers;

class Categories extends BaseController
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
    
        $this->data['user_jwt'] = ($this->guid != '') ? getSignedJWTForUser($this->guid) : '';
        $this->image_model = model('ImageModel');
        $this->product_variant_model = model('ProductVariantModel');
    
        if($this->isLoggedIn !== 1 && $this->role !== 1) {
          return redirect()->to('/');
        }
    }

    public function index($url)
    {
        $categories = $this->category_model->where('url', $url)->get()->getResult()[0];

        $page_title = $categories->name;

        // print_r($categories);

        $this->data['page_body_id'] = "shop";
        $this->data['breadcrumbs'] = [
        'parent' => [],
        'current' => $page_title,
        ];
        $this->data['page_title'] = $page_title;
        // $this->data['products'] = $this->product_model->get()->getResult();
        // $this->data['products'] = $this->product_model->getAllProducts();

        $all_products = $this->category_model->categoryGetProducts($categories->id);

        $product_arr = [];
        $count = 0;
        foreach($all_products as $product) {
            $product_arr[$count] = $product;
            if($product->images) {
                $imageIds = [];
                $imageIds = explode(',',$product->images);
                $images = $this->image_model->whereIn('id', $imageIds)->get()->getResult();
                $product_arr[$count]->images = $images;
            }

            $count++;
        }

        $this->data['products'] = $product_arr;
        return view('categories_view', $this->data);
    }
}
