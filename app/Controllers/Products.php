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
        $this->pagecounter_model = model('pagecounterModel');
        $this->product_model = model('productModel');
        $this->db = db_connect();
    
        if($this->isLoggedIn !== 1 && $this->role !== 1) {
          return redirect()->to('/');
        }
    }

    public function index($url = '')
    {

        if($url != '') {
            $product = $this->product_model->where('url', $url)->get()->getResult();

            if(!empty($product)) {
                $product = $product[0];
            }
            
        }
        else {
            $product = $this->product_model->get()->getResult();
            return $this->view_all_products();
        }
        
        // print_r($product);die();

        $page_title = $product->name;

        $this->data['page_body_id'] = "product-".$product->id;
        $this->data['breadcrumbs'] = [
        'parent' => [],
        'current' => $page_title,
        ];
        $this->data['page_title'] = $page_title;
        $this->data['product'] = $product;
        $this->data['images'] = [];

        // print_r($product->images);die();

        $imageIds = [];
        if($product->images) {
            $imageIds = explode(',',$product->images);
            $this->data['images'] = $this->image_model->whereIn('id', $imageIds)->get()->getResult();
        }
        
        // print_r($imageIds);die();

        // print_r($this->image_model->getLastQuery());

        // print_r($this->data['images']);die();

        echo view('product_view', $this->data);
    }

    public function view_all_products() {
        echo "view all products";
    }

    public function images($filename) {
        $filepath = WRITEPATH . 'uploads/' . $filename;

        $mime = mime_content_type($filepath);
        header('Content-Length: ' . filesize($filepath));
        header("Content-Type: $mime");
        header('Content-Disposition: inline; filename="' . $filepath . '";');
        readfile($filepath);
        exit();
    }

	
    public function save($id) 
    {
     $user_data = [
        'full_name' => $this->request->getPost('full_name'),
        'c_number' => $this->request->getPost('c_number'),
        'address' => $this->request->getPost('address'),
        'product' => $this->request->getPost('product'),
        'price' => $this->request->getPost('price'),
        'qty' => $this->request->getPost('qty'),
        'total' => $this->request->getPost('total'),
     ];

     $data = $this->product_model->where('id', $id)->select('stocks')->first();
     foreach($data as $datas):
      $stock = $datas -1;
     endforeach;
     $newData = ['stocks' => $stock]; 
   
     $this->product_model->update($id, $newData);
     $this->order_model->save($user_data); 
     return redirect()->to('/Shop');

      
    }
}
