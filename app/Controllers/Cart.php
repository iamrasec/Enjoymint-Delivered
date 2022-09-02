<?php

namespace App\Controllers;

use App\Models\UserModel;

class Cart extends BaseController
{
  public function __construct() 
	{
		helper(['jwt']);

		$this->data = [];
		$this->role = session()->get('role');
    $this->isLoggedIn = session()->get('isLoggedIn');
		$this->guid = session()->get('guid');
    $this->product_model = model('ProductModel');
    $this->image_model = model('ImageModel');
    $this->cart_model = model('CartModel');
    $this->user_model = model('UserModel');

		$this->data['user_jwt'] = ($this->guid != '') ? getSignedJWTForUser($this->guid) : '';		
	}

  public function index()
  {
    $user_cart = '';
    $cart_raw = [];
    $cart_products = [];

    // Check if user is logged in
    if($this->isLoggedIn == 1) {
      // Fetch user data using guid
      $user_data = $this->user_model->getUserByGuid($this->guid);

      // print_r($user_data);die();

      // Fetch cart items from db
      $db_cart = $this->cart_model->where('uid', $user_data['id'])->get()->getResult();

      // print_r($db_cart);die();

      // If there are products from db, they should show up on the page
      if(!empty($db_cart)) {
        $cart_raw = $db_cart;
      }
		}
    else {
      // Check if cart cookie is set
      if(isset($_COOKIE['cart_data'])) {
        $user_cart = $_COOKIE['cart_data'];

        $cart_raw = json_decode($user_cart);
      }
    }

    // Loop through all the products and fetch all the product info
    foreach($cart_raw as $product) {

      // print_r($product);

      // Get products from the database using pid
      $product_data = $this->product_model->getProductData($product->pid);
      
      // initialize images
      $images = [];
      $imageIds = [];

      // Fetch product images
      if($product_data->images) {
        $imageIds = explode(',',$product_data->images);
        $images = $this->image_model->whereIn('id', $imageIds)->get()->getResult();
      }

      // Output array
      $cart_products[] = [
        'pid' => $product->pid,
        'qty' => $product->qty,
        'product_data' => $product_data,
        'images' => $images,
      ];
    }

    // print_r($cart_products);die();

    $this->data['cart_products'] = $cart_products;
    $this->data['guid'] = $this->guid;

    return view('cart/cart_page', $this->data);
  }

  public function checkout()
  {
    if($this->isLoggedIn != 1) {
      return redirect()->to('/cart');
		}

    return view('cart/checkout', $this->data);
  }
}