<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Libraries\EnjoymintUtils;
use CodeIgniter\I18n\Time;

class Dashboard extends BaseController {

  public function __construct() {
    helper(['jwt']);

		$this->data = [];
		$this->role = session()->get('role');
    $this->isLoggedIn = session()->get('isLoggedIn');
    $this->guid = session()->get('guid');
    $this->product_model = model('ProductModel');
    $this->checkout_model = model('CheckoutModel');

    $this->sender_email = getenv('SMTP_EMAIL_USER');

    date_default_timezone_set('America/Los_Angeles');

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

    // $this->daily_sales();die();

    echo view('dashboard', $this->data);     
  }

  private function daily_sales() {
    $yesterday = new Time("yesterday", "America/Los_Angeles", "en_EN");
    $today = new Time("now", "America/Los_Angeles", "en_EN");

    $yesterDate = $yesterday->toDateString();
    $toDate = $today->toDateString();

    $debug = [
      'yesterday' => $yesterday,
      'today' => $today,
      'yesterDate' => $yesterDate,
      'toDate' => $toDate,
    ];

    echo "<pre>".print_r($debug,1)."</pre>";


    // $raw_data = $this->checkout_model->where()
  }
}