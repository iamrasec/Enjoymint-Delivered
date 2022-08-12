<?php

namespace App\Controllers;

use App\Models\out;

class Products extends BaseController
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
        $this->order_model = model('checkoutModel');
    
        if($this->isLoggedIn !== 1 && $this->role !== 1) {
          return redirect()->to('/');
        }
    }

    public function index($url = '')
    {
        if($url != '') {
            $product = $this->product_model->where('url', $url)->get()->getResult()[0];
            
        }
        else {
            $product = $this->product_model->get()->getResult();
            return $this->view_all_products();
        }
        

        $page_title = $product->name;

        $this->data['page_body_id'] = "product-".$product->id;
        $this->data['breadcrumbs'] = [
        'parent' => [],
        'current' => $page_title,
        ];
        $this->data['page_title'] = $page_title;
        $this->data['product'] = $product;

        echo view('product_view', $this->data);
    }

    public function view_all_products() {
        echo "view all products";
    }

	
    public function save() 
    {
     $data = [
        'full_name' => $this->request->getPost('full_name'),
        'c_number' => $this->request->getPost('c_number'),
        'address' => $this->request->getPost('address'),
        'product' => $this->request->getPost('product'),
        'price' => $this->request->getPost('price'),
        'qty' => $this->request->getPost('qty'),
        'total' => $this->request->getPost('total'),
     ];
     $this->order_model->save($data); 
     return redirect()->to('/Shop');
    }
}
