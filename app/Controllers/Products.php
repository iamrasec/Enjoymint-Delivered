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
        $this->id = session()->get('id');
        $this->guid = session()->get('guid');
        $this->product_model = model('ProductModel');
        $this->strain_model = model('StrainModel');
        $this->brand_model = model('BrandModel');
        $this->category_model = model('CategoryModel');
        $this->rating_model = model('ratingModel');
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

    public function index($url = ''){
        $session = session();

        // temporarily removed rating status for now as it crashes the product page.
        // $status = $this->order_model->where('user_id', $this->id )->select('status')->first();
        // if($this->isLoggedIn == 1 && $status['status'] == 2){
        //     $this->data['isRating'] = 'inline';
        // }else{
        //     $this->data['isRating'] = 'none';
        // }

        if($url != '') {
            $product = $this->product_model->where('url', $url)->get()->getResult();

        if(!empty($product)) {
            $product = $product[0];
        }        
    }
    else {
         $product = $this->product_model->get()->getResult();
       
    }

    

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
    
    // Temporarily removed rating query as it crashed product page (no db rating table yet on my local).
    // $this->data ['rate_data'] = $this->rating_model->where('id', 32)->select( 'star,message')->first();

    // print_r($imageIds);die();

    // print_r($this->image_model->getLastQuery());

    // print_r($this->data['images']);die();
    // return $this->view_all_products();
    echo view('product_view', $this->data);

    //     $newData = [
    //         'product_name' => $this->request->getPost('product_name'),
    //         'views' => $this->request->getPost('views'),
    //         ];
    //    $this->pagecounter_model->update($newData); 
        $ip_views = $this->request->getIPAddress();
        $newData = ['ip_views' => $ip_views]; 
        $checkIp = $this->pagecounter_model->where('ip_views', $newData)->first();
        // $newCheckIp = ['checkIp' => $checkIp]; 
        if($checkIp){

           // }
           $page_data['stock'] = $this->product_model->where('id', 2)->select('stocks')->first();
           $page_data['ip_views'] = $this->pagecounter_model->countAll();
          
       //     $page_data['views'] = $this->pagecounter_model->countAll();
       //      // echo "Sample"; 
        }
        else {
            $page_data['stock'] = $this->product_model->where('id', 2)->select('stocks')->first();
            $page_data['ip_views'] = $this->pagecounter_model->countAll();
            $this->pagecounter_model->save($newData);

           
            // print_r($product);
        }
}

    

// public function view_all_products(){
//          echo view('product_view', $this->data);
//      }

    public function images($filename) {
        $filepath = WRITEPATH . 'uploads/' . $filename;

        $mime = mime_content_type($filepath);
        header('Content-Length: ' . filesize($filepath));
        header("Content-Type: $mime");
        header('Content-Disposition: inline; filename="' . $filepath . '";');
        readfile($filepath);
        exit();
    }

	
    public function save($id = 2) 
    {
     $user_data = [
        'user_id' => $this->id = session()->get('id'),
        'full_name' => $this->request->getPost('full_name'),
        'c_number' => $this->request->getPost('c_number'),
        'address' => $this->request->getPost('address'),
        'product' => $this->request->getPost('product'),
        'price' => $this->request->getPost('price'),
        'qty' => $this->request->getPost('qty'),
        'total' => $this->request->getPost('total'),
     ];

     $data = $this->product_model->where('id', 2)->select('stocks')->first();
     foreach($data as $datas):
      $stock = $datas -1;
     endforeach;
     $newData = ['stocks' => $stock]; 
     $this->product_model->update($id, $newData);
     $session = session();
     $session->user_data = $user_data;
     $this->order_model->save($user_data); 
     return redirect()->to('/Shop');

      
    }

    public function rating(){

        $ratings = [
          'message' => $this->request->getPost('message'),  
          'star' => $this->request->getPost('ratings'),  
       ];
  
       $this->rating_model->save($ratings);
       return redirect()->to('/Shop'); 
    //    $page_data ['rate'] = $this->rating_model->where('id', 12)->select( 'star')->first();
    //    $page_data ['message'] = $this->rating_model->where('id', 12)->select( 'message')->first();
    //    echo view('product_view', $page_data); 
       
  
    }
}
