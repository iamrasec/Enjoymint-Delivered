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
        $this->uid = session()->get('id');
        $this->product_model = model('ProductModel');
        $this->strain_model = model('StrainModel');
        $this->brand_model = model('BrandModel');
        $this->category_model = model('CategoryModel');
        $this->measurement_model = model('MeasurementModel');
        $this->location_model = model('LocationModel');
        $this->discount_model = model('DiscountModel');
    
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

        $user_id = $this->uid;

        // print_r($categories);

        $this->data['page_body_id'] = "shop";
        $this->data['breadcrumbs'] = [
        'parent' => [],
        'current' => $page_title,
        ];
        $this->data['page_title'] = $page_title;
        // $this->data['products'] = $this->product_model->get()->getResult();
        // $this->data['products'] = $this->product_model->getAllProducts();

        $all_products = $this->category_model->categoryGetProductsPaginate($categories->id);

        // echo "<pre>".print_r($all_products, 1)."</pre>"; die();

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

            // If product has variants, get variants data
            if($product['has_variant'] == 1) {
                $product_arr[$count]['variants'] = $this->product_variant_model->where('pid', $product['id'])->get()->getResult();
            }

            $count++;
        }

        $this->data['uid'] = $user_id;
        $this->data['sale_price'] = $price;
        $this->data['products'] = $product_arr;
        $this->data['pager'] = $this->category_model->pager;
        $this->data['categories'] = $this->category_model->get()->getResult();
        $this->data['brands'] = $this->brand_model->get()->getResult();
        $this->data['strains'] = $this->strain_model->get()->getResult();
        $this->data['location_keyword'] = $this->location_model->where('user_id', $user_id )->select('address')->first();
        $this->data['location_delivery'] = $this->location_model->where('user_id', $user_id )->select('delivery_schedule')->first();
        return view('categories_view', $this->data);
    }
}
