<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;

class Sale extends BaseController {

    public function __construct() {
        helper(['jwt']);
		$this->data = [];
		 $this->role = session()->get('role');
         $this->isLoggedIn = session()->get('isLoggedIn');
        $this->guid = session()->get('guid');
        $this->order_model = model('CheckoutModel');

        $this->data['user_jwt'] = getSignedJWTForUser($this->guid);
        if($this->isLoggedIn !== 1 && $this->role !== 1) {
            return redirect()->to('/');
          }
    }

    public function index() {
       
            $page_title = 'Product Sale';
      
            $this->data['page_body_id'] = "product_sale";
            $this->data['breadcrumbs'] = [
              'parent' => [],
              'current' => $page_title,
            ];
            $this->data['page_title'] = $page_title;
            // $page_data['product_sale'] = $this->order_model->where('status', 1)->select('stocks')->first();
             $this->data['product_sale'] = $this->order_model->where('status', 1)->get()->getResult();
             $sales = $this->order_model->where('status', 1)->selectSum('qty')->first();
            $this->data ['sales']  = $sales;
                echo view('Admin/product_sale', $this->data);
        
       
    }

}
