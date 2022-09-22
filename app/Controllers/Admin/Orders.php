<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;

class Orders extends BaseController {

    public function __construct() {
        helper(['jwt']);
		$this->data = [];
		 $this->role = session()->get('role');
         $this->isLoggedIn = session()->get('isLoggedIn');
        $this->guid = session()->get('guid');
        $this->order_model = model('CheckoutModel');
        $this->order_products = model('OrderProductsModel');
        $this->drivers_model = model('Drivers');

        $this->data['user_jwt'] = getSignedJWTForUser($this->guid);
        if($this->isLoggedIn !== 1 && $this->role !== 1) {
            return redirect()->to('/');
          }
    }

    public function index() {
       
        $page_title = 'All Orders';
    
        $this->data['page_body_id'] = "orders";
        $this->data['breadcrumbs'] = [
            'parent' => [],
            'current' => $page_title,
        ];
        $this->data['page_title'] = $page_title;
        
        echo view('Admin/Orders/all_orders', $this->data);       
    }

    public function edit($id)
    {
        $order = $this->order_model->where('id', $id)->get()->getRow();

        $page_title = 'Edit Order #'. $order->id;

        $this->data['page_body_id'] = "c_orders";
        $this->data['breadcrumbs'] = [
        'parent' => [
            ['parent_url' => base_url('/admin/orders'), 'page_title' => 'Orders'],
        ],
        'current' => $page_title,
        ];
        $this->data['page_title'] = $page_title;
        $this->data['submit_url'] = base_url('/admin/orders/save_edit');
        $this->data['order_data'] = $order;
        $this->data['order_products'] = $this->order_products->where('order_id', $id)->get()->getResult();
        
        echo view('Admin/Orders/edit_order', $this->data);
    }

    public function save_edit()
    {

    }

    public function active()
    {
        $page_title = 'Active Orders';

        $this->data['page_body_id'] = "c_orders";
        $this->data['breadcrumbs'] = [
        'parent' => [
            ['parent_url' => base_url('/admin/orders'), 'page_title' => 'Orders'],
        ],
        'current' => $page_title,
        ];
        $this->data['page_title'] = $page_title;
        $this->data['submit_url'] = base_url('/admin/orders/order');

        $this->data['product_sale'] = $this->order_model->where('status', 1)->get()->getResult();
        
        echo view('Admin/pending_orders', $this->data);
    }

    public function completed(){
       
             $page_title = 'Completed Orders';

            $this->data['page_body_id'] = "c_orders";
            $this->data['breadcrumbs'] = [
            'parent' => [
                ['parent_url' => base_url('/admin/orders'), 'page_title' => 'Orders'],
            ],
            'current' => $page_title,
            ];
            $this->data['page_title'] = $page_title;
            $this->data['submit_url'] = base_url('/admin/orders/order');

            $this->data['product_sale'] = $this->order_model->where('status', 1)->get()->getResult();
          
                echo view('Admin/complete_orders', $this->data);
            
    }

    public function cancelled()
    {
        $page_title = 'Cancelled Orders';

        $this->data['page_body_id'] = "c_orders";
        $this->data['breadcrumbs'] = [
        'parent' => [
            ['parent_url' => base_url('/admin/orders'), 'page_title' => 'Orders'],
        ],
        'current' => $page_title,
        ];
        $this->data['page_title'] = $page_title;
        $this->data['submit_url'] = base_url('/admin/orders/order');

        $this->data['product_sale'] = $this->order_model->where('status', 1)->get()->getResult();
        
        echo view('Admin/cancelled_orders', $this->data);
    }

    public function drivers(){
       $session= session();
        $page_title = 'Drivers Table';

       $this->data['page_body_id'] = "drivers";
       $this->data['breadcrumbs'] = [
       'parent' => [
           ['parent_url' => base_url('/admin/orders'), 'page_title' => 'Orders'],
       ],
       'current' => $page_title,
       ];
       $this->data['page_title'] = $page_title;
       $this->data['submit_url'] = base_url('/admin/orders/drivers');
       
    //    $driver = [
    //         'name' => $this->request->getPost('drivers'),
    //       ];
        // // $this->drivers_model->save($driver);
        // $driver_id =$this->drivers_model->select('id')->first();
        //  $session->driver = $driver_id; 
        //  print_r($driver_id) ;
        //  $data = ['driver_id' => $session->get('driver')];

        //  $this->order_model->save($data);
        
           return view('Admin/drivers', $this->data);
       
}


public function complete($id = null) {
    $this->order_model->update($id, ['status' => 1]);
    die(json_encode(array("success" => TRUE,"message" => 'Product Delete!')));
}

}
