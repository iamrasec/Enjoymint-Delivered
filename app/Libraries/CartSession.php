<?php 
namespace App\Libraries;

class CartSession {
  
  private $_CI;

  public function __construct()
  {
    $this->data = [];
    $this->isLoggedIn = session()->get('isLoggedIn');
    $this->cart_model = model('CartModel');
  }

  public function update_cart_session()
  {
    
    $session = session();

    if($this->isLoggedIn) {
      $cart_products = $this->cart_model->where('uid', session()->get('id'))->get()->getResult();
      $session->set('cart_items', []);
      $session->push('cart_items', $cart_products);
    }
    


  }
  
}