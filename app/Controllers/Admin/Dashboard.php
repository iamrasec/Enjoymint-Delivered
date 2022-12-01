<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;

class Dashboard extends BaseController {

  public function __construct() {
    helper(['jwt']);

		$this->data = [];
		$this->role = session()->get('role');
    $this->isLoggedIn = session()->get('isLoggedIn');
    $this->guid = session()->get('guid');

    $this->data['user_jwt'] = ($this->guid != '') ? getSignedJWTForUser($this->guid) : '';

    if($this->isLoggedIn !== 1 && $this->role !== 1) {
      return redirect()->to('/');
    }
  }
  
  public function index() {
    $data = [];

    if($this->isLoggedIn != 1) {
      return redirect()->to('/');
    }

    if($this->role != 1) {
      return redirect()->to('/');
    }
    // else {
    //   echo "<pre>isLoggedIn: ".$this->isLoggedIn."</pre>";
    //   echo "<pre>role: ".$this->role."</pre>";
    // }

    $this->data['page_body_id'] = "user_login";

    echo view('dashboard', $this->data);     
  }
}