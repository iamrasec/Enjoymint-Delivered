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

		$this->data['user_jwt'] = ($this->guid != '') ? getSignedJWTForUser($this->guid) : '';		
	}

  public function index()
  {
    $user_cart = '';
    $cart_raw = [];
    $cart_products = [];

    // Check if user is logged in
    if($this->isLoggedIn == 1) {

		}
    else {
      // Check if cart cookie is set
      $user_cart = $_COOKIE['cart_data'];

      $cart_raw = json_decode($user_cart);

      foreach($cart_raw as $product) {

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
    }

    $this->data['cart_products'] = $cart_products;
    $this->data['guid'] = $this->guid;

    return view('cart/cart_page', $this->data);
  }

  public function checkout()
  {
    if($this->isLoggedIn != 1) {

		}
  }
}