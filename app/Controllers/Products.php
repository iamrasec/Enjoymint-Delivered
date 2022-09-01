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
        $this->cart_model = model('CartModel');
    
        $this->data['user_jwt'] = ($this->guid != '') ? getSignedJWTForUser($this->guid) : '';
        $this->image_model = model('ImageModel');
        $this->product_variant_model = model('ProductVariantModel');
        $this->order_model = model('checkoutModel');
        $this->pagecounter_model = model('pagecounterModel');
        $this->product_model = model('productModel');
        $this->db = db_connect();
    }

    

    public function index($url = '')
    {
        if($url != '') {
            $product = $this->product_model->getProductFromUrl($url);

            if(!empty($product)) {
                $product = $product[0];
            }
        }
        else {
            $product = $this->product_model->get()->getResult();
            return $this->view_all_products();
        }

        // $ip_views = $this->request->getIPAddress();
        // $newData = ['ip_views' => $ip_views]; 
        // $checkIp = $this->pagecounter_model->where('ip_views', $newData)->first();
        // if($checkIp){
        //     $page_data['stock'] = $this->product_model->where('id', 2)->select('stocks')->first();
        //     $page_data['ip_views'] = $this->pagecounter_model->countAll();
        // }
        // else {  
        //     $page_data['stock'] = $this->product_model->where('id', 2)->select('stocks')->first();
        //     $page_data['ip_views'] = $this->pagecounter_model->countAll();
        //     $this->pagecounter_model->save($newData);
        // }

        $page_title = $product->name;

        $this->data['page_body_id'] = "product-".$product->id;
        $this->data['breadcrumbs'] = [
        'parent' => [],
        'current' => $page_title,
        ];
        $this->data['page_title'] = $page_title;
        $this->data['product'] = $product;
        $this->data['images'] = [];

        $imageIds = [];
        if($product->images) {
            $imageIds = explode(',',$product->images);
            $this->data['images'] = $this->image_model->whereIn('id', $imageIds)->get()->getResult();
        }

        $session = session();
        $session->set('cart_items', []);
        $cookie_cart = [];
        
        if($this->isLoggedIn && !isset($_COOKIE["cart_data"])) {
            // $this->data['cart_products'] = $this->cart_model->cartProductsCount(session()->get('id'));
            $cart_products = $this->cart_model->where('uid', session()->get('id'))->get()->getResult();

            foreach($cart_products as $cart_product) {
                // $session->push('cart_items', [['pid' => $cart_product->pid, 'qty' => $cart_product->qty]]);
                $cookie_cart[] = (array)['pid' => $cart_product->pid, 'qty' => (int)$cart_product->qty];
            }

            // print_r(json_encode($cookie_cart, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE));die();

            $json_encoded = json_encode($cookie_cart, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);

            // setcookie("cart_data", $json_encoded, time() + (86400 * 30), "/"); // 86400 = 1 day

            $this->data['cookie_cart'] = $json_encoded;

            //print_r($cart_products);
            // $session->push('cart_items', $cart_products);
        }
        else if(!$this->isLoggedIn && isset($_COOKIE["cart_data"])) {
            $this->data['cookie_cart'] = $_COOKIE["cart_data"];

            // print_r($this->data['cookie_cart']);die();
        }
        else {
            $this->data['cookie_cart'] = [];
            $this->data['cart_products'] = 0;
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

     $data = $this->product_model->where('id', 2)->select('stocks')->first();
     foreach($data as $datas):
      $stock = $datas -1;
     endforeach;
     $newData = ['stocks' => $stock]; 
   
     $this->product_model->update($id, $newData);
     $this->order_model->save($user_data); 
     return redirect()->to('/Shop');

      
    }
}
