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
        $this->image_model = model('ImageModel');
        $this->product_model = model('ProductModel');
        $this->drivers_model = model('Drivers');
        $this->customerverification_model = model('VerificationModel');
        $this->promo_model = model('PromoModel');

        $this->allowed_roles = [1,2,4];
        $this->tax_rate = 1.35;  // 35%

        $this->data['user_jwt'] = getSignedJWTForUser($this->guid);
        if($this->isLoggedIn !== 1 && $this->role !== 1) {
            return redirect()->to('/');
          }
    }

    public function index() {

        if(!in_array($this->role, $this->allowed_roles)) {
            return redirect()->to('/');
        }
       
        $page_title = 'All Orders';
    
        $this->data['page_body_id'] = "orders";
        $this->data['breadcrumbs'] = [
            'parent' => [],
            'current' => $page_title,
        ];
        $this->data['page_title'] = $page_title;
        $this->data['role'] = $this->role;
        
        echo view('Admin/Orders/all_orders', $this->data);       
    }

    public function edit($id)
    {
        $session = session();
        $distotal = $session->get('distotal');
        print_r($distotal);
        $order = $this->order_model->where('id', $id)->get()->getRow();
        $order_products = $this->order_products->where('order_id', $id)->get()->getResult();
        $session->order_products = $order_products;
        print_r($session->order_products = $order_products);
        $all_products = $this->product_model->getAllProductsNoPaginate('asc');

        $order_pids = [];

        for($i = 0; $i < count($all_products); $i++) {

            $images = [];
            $imageIds = [];

            // Fetch product images
            if($all_products[$i]->images) {
                $imageIds = explode(',',$all_products[$i]->images);
                $images = $this->image_model->whereIn('id', $imageIds)->get()->getResult();
            }

            $all_products[$i]->images = $images;
            
            for($j = 0; $j < count($order_products); $j++) {
                if($order_products[$j]->product_id == $all_products[$i]->id) {
                    $order_products[$j]->product_data = $all_products[$i];
                    $order_pids[] = $order_products[$j]->product_id;

                    unset($all_products[$i]);
                    break;
                }
            }
        }

        // echo "<pre>".print_r($order_products, 1)."</pre>"; die();

        $page_title = 'Edit Order #'. $order->id;

        $this->data['role'] = $this->role;
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
        $this->data['order_products'] = $order_products;
        $this->data['all_products'] = $all_products;
        $this->data['order_pids'] = implode(',', $order_pids);
        $this->data['tax_rate'] = $this->tax_rate;
        $this->data['currDate'] = new \CodeIgniter\I18n\Time("now", "America/Los_Angeles", "en_EN");
        $this->data['all_promo'] = $this->promo_model->get()->getResult();

        $this->data['pricesubtotal'] = $distotal;

        if($this->data['currDate']->format('H') > '16') {
            $this->data['currDate'] = new \CodeIgniter\I18n\Time("tomorrow", "America/Los_Angeles", "en_EN");
        }
        
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
        $this->data['role'] = $this->role;

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
            $this->data['role'] = $this->role;

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
        $this->data['role'] = $this->role;

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
