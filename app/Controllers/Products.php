<?php

namespace App\Controllers;

use App\Models\out;

class Products extends BaseController
{
    var $view_data = array();
    public function __construct() {
        $this->order_model = model('checkoutModel');
        $this->pagecounter_model = model('pagecounterModel');
        $this->product_model = model('productModel');
        $this->rating_model = model('ratingModel');
        // $this->db = db_connect();
    }

    

    public function index()
    {
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
            
        //   if (! $this->request->isValidIP($checkIp)) {
        //     echo 'Not Valid';
        // } else {
        //     echo 'Valid';
        // }
            $page_data['stock'] = $this->product_model->where('id', 2)->select('stocks')->first();
            $page_data['ip_views'] = $this->pagecounter_model->countAll();
            $page_data ['rate'] = $this->rating_model->where('id', 12)->select( 'star')->first();
            $page_data ['message'] = $this->rating_model->where('id', 12)->select( 'message')->first();
        //     $page_data['views'] = $this->pagecounter_model->countAll();
        //      // echo "Sample";
        }
          else 
          {  
            $page_data['stock'] = $this->product_model->where('id', 2)->select('stocks')->first();
            $page_data['ip_views'] = $this->pagecounter_model->countAll();
            $this->pagecounter_model->save($newData);
            $page_data ['rate'] = $this->rating_model->where('id', 12)->select( 'star')->first();
            $page_data ['message'] = $this->rating_model->where('id', 12)->select( 'message')->first();
            
            
          }
        echo view('product_view', $page_data);  
    }

    public function save($id = 2) 
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

     $data = $this->product_model->where('id', 2)->select('stocks')->first();
     foreach($data as $datas):
      $stock = $datas -1;
     endforeach;
     $newData = ['stocks' => $stock]; 
   
     $this->product_model->update($id, $newData);
     $this->order_model->save($user_data); 
     return redirect()->to('/Shop');

      
    }
    public function rating(){
      
      $ratings = [
        'message' => $this->request->getPost('message'),  
        'star' => $this->request->getPost('ratings'),  
     ];

     $this->rating_model->save($ratings); 
     $page_data['stock'] = $this->product_model->where('id', 2)->select('stocks')->first();
     $page_data['ip_views'] = $this->pagecounter_model->countAll();
     $page_data ['rate'] = $this->rating_model->where('id', 12)->select( 'star')->first();
     $page_data ['message'] = $this->rating_model->where('id', 12)->select( 'message')->first();
     echo view('product_view', $page_data); 


    }
}
