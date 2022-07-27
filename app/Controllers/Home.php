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
        $this->product_model = model('ProductModel');
        $this->strain_model = model('StrainModel');
        $this->brand_model = model('BrandModel');
        $this->category_model = model('CategoryModel');
        $this->measurement_model = model('MeasurementModel');
    
        $this->data['user_jwt'] = getSignedJWTForUser($this->guid);
        $this->image_model = model('ImageModel');
        $this->product_variant_model = model('ProductVariantModel');
    
        if($this->isLoggedIn !== 1 && $this->role !== 1) {
          return redirect()->to('/');
        }
    }

    public function index()
    {
        $page_title = 'Home';

        $this->data['page_body_id'] = "home";
        $this->data['breadcrumbs'] = [
        'parent' => [],
        'current' => $page_title,
        ];
        $this->data['page_title'] = $page_title;
        $this->data['products'] = $this->product_model->get()->getResult();
        $this->data['categories'] = $this->category_model->get()->getResult();
        return view('home_view', $this->data);
    }
}
