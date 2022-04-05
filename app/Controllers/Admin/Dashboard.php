<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;

class Dashboard extends BaseController {
  
  public function index() {
    $data = [];

    $role = session()->get('role');
    $isLoggedIn = session()->get('isLoggedIn');

    if($isLoggedIn == 1) {
      $this->data['page_body_id'] = "user_login";

		  echo view('dashboard', $this->data);
    }
    else {
      return redirect()->to('/');
    }        
  }
}