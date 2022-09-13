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
        $search = "";
        if(isset($searchData) && isset($searchData['search'])){
        $search = $searchData['search'];
        }

        //  $all_products = $this->product_model->getAllProducts();
        if($search == ''){
            $all_products = $this->product_model->paginate(30);
        }else{
            $all_products = $this->product_model->select('*')
            ->like('name',$search)
            ->orLike('price',$search)
            ->paginate(30);
        }
        $product_arr = [];
        $count = 0;
        foreach($all_products as $product) {
             $product_arr[$count] = $product;
            if(!empty($product->images)) {
                $imageIds = [];
                $imageIds = explode(',',$product->images);
                $images = $this->image_model->whereIn('id', $imageIds)->get()->getResult();
                $product_arr[$count]->images = $images;
            }

             $count++;
        }
   

        $this->data['products'] = $all_products;
        $this->data['pager'] = $this->product_model->pager;
        $this->data['categories'] = $this->category_model->get()->getResult();
       return view('shop_view', $this->data);
    }
}
 