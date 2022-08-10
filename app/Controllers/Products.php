<?php

namespace App\Controllers;

use App\Models\out;

class Products extends BaseController
{
    var $view_data = array();
    public function __construct() {
        $this->order_model = model('checkoutModel');
    }

    public function index()
    {
      
        echo view('product_view');  
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
