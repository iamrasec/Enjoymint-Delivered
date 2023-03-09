<?php

namespace App\Controllers;

class Promotion extends BaseController
{
    var $view_data = array();

    public function __construct() {
        helper(['jwt']);
    
        $this->data = [];
        $this->role = session()->get('role');
        $this->isLoggedIn = session()->get('isLoggedIn');
        $this->guid = session()->get('guid');
        $this->uid = session()->get('id');
        $this->location_model = model('LocationModel');
        $this->promo_model = model('PromoModel');
        $this->promo_products_model = model('PromoProductsModel');
        $this->product_model = model('ProductModel');
        $this->data['user_jwt'] = ($this->guid != '') ? getSignedJWTForUser($this->guid) : '';
    
        if($this->isLoggedIn !== 1 && $this->role !== 1) {
          return redirect()->to('/');
        }
    }

    public function index($url = '')
    {
        $session = session();
    //     $status = $this->order_model->where('customer_id', $this->id )->select('status')->first();
    //     if($this->isLoggedIn == 1 && $status['status'] == 2){
    //       $this->data['isRating'] = 'inline';
    //   }else{
    //       $this->data['isRating'] = 'none';
    //   }
    $user_id = $this->uid;
    if($user_id == null){
        $session->setFlashdata('message', 'Please login first');
      }
    $this->data['uid'] = $user_id;
    $this->data['location_keyword'] = $this->location_model->where('user_id', $user_id )->select('address')->first();
        if($url != '') {
            $product = $this->promo_model-> getProductFromUrl($url);

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

        $session->product_id = $product;
        
        $page_title = $product->title;

        $this->data['page_body_id'] = "product-".$product->id;
        $this->data['breadcrumbs'] = [
        'parent' => [],
        'current' => $page_title,
        ];
        $this->data['page_title'] = $page_title;
        $this->data['product'] = $product;
        $this->data['images'] = [];

      
        // print_r($id); 
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

        $record_view = true;
        
        if(isset($_COOKIE['pvid'])) {
            $pvid = explode(",", $_COOKIE['pvid']);
            
            // echo "<pre>".print_r($pvid, 1)."</pre>";
            
            if(in_array($product->id, $pvid)) {
                $record_view = false;
            }
        }

        // if($record_view == true) {
        //     $this->increment_product_views($product->id);
        // }
        
        // print_r($imageIds);die();

        // print_r($this->image_model->getLastQuery());

        // print_r($this->data['images']);die();
        $prod_id = $this->promo_model->select('id')->where('url', $url)->get()->getResult();
       
        $product_list = $this->promo_products_model->where('promo_id', $prod_id[0]->id)->get()->getResult();
        //  print_r($product_list);
        // $data = explode($product_list)
        if(!empty($product_list)){
            $data = [];
         if($product_list[0]->promo_product == "promo_products_all"){
             $data = $this->product_model->paginate();
         }elseif($product_list[0]->promo_product == "promo_products_specific"){
            $delimiter = ",";
             $data_exp = explode($delimiter, $product_list[0]->discounted_specific_product);
             if($data_exp > 1){
             foreach($data_exp as $id){
                $data[] = $this->product_model->where('id', $id)->get()->getResult();
             }
            }else{
              $data = $this->product_model->where('id', $id)->get()->getResult();
            }
         }elseif($product_list[0]->promo_product == "promo_products_cat"){
            $data_cat = $this->product_category->select('pid')->where('cid', $product_list[0]->discounted_category_id)->get()->getResult();
            if($data_cat > 1){
            foreach($data_cat as $category){
              $data[] = $this->product_model->where('id', $category->pid)->get()->getResult();
            }
            }else{
              $data = $this->product_model->where('id', $category->pid)->get()->getResult();
            }
         }
         $this->data['product_data'] = $data;
         $this->data['pager'] = $this->product_model->paginate();
        }else{
          $this->data['product_data'] = null;
        }
        //print_r($data);
        echo view('promo_view', $this->data);
    }
}
