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
    $this->user_model = model('UserModel');

    $this->sender_email = getenv('SMTP_EMAIL_USER');

    date_default_timezone_set('America/Los_Angeles');

    $this->today = new Time("now", "America/Los_Angeles", "en_EN");

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
    $this->data['daily_sales'] = $this->daily_sales();
    $this->data['monthly_sales'] = $this->monthly_sales();
    $this->data['annual_sales'] = $this->annual_sales();
    $this->data['active_users'] = $this->active_users();

    // $this->active_users();die();

    echo view('dashboard', $this->data);     
  }

  private function daily_sales() {
    $toDate = $this->today->toDateString();

    $where1 = 'created >= "'. $toDate .' 00:00:00"';
    $where2 = 'created <= "'. $toDate .' 23:59:59"';

    $raw_data = $this->checkout_model->where($where1)->where($where2)->where('status = 3')->get()->getResult();

    $total_sales = 0;
    foreach($raw_data as $data) {
      $total_sales += $data->subtotal;
    }

    $return = [
      'date' => $this->today->toLocalizedString('MMM d'),
      'total_sales' => $total_sales,
    ];

    return $return;
  }

  private function monthly_sales() {
    $currMonth = $this->today->month;
    $currYear = $this->today->year;

    $maxDays = date('t'); 

    $startMonth = $currYear.'-'.$currMonth.'-1 00:00:00';
    $endMonth = $currYear.'-'.$currMonth.'-'.$maxDays.' 23:59:59';

    $where1 = 'created >= "'. $startMonth .' 00:00:00"';
    $where2 = 'created <= "'. $endMonth .' 23:59:59"';

    $raw_data = $this->checkout_model->where($where1)->where($where2)->where('status = 3')->get()->getResult();    

    $total_sales = 0;
    foreach($raw_data as $data) {
      $total_sales += $data->subtotal;
    }

    $return = [
      'date' => $this->today->toLocalizedString('MMM YYYY'),
      'total_sales' => $total_sales,
    ];

    return $return;
  }

  private function annual_sales() {
    $currYear = $this->today->year;

    $startYear = $currYear.'-1-1 00:00:00';
    $endYear = $currYear.'-12-31 23:59:59';

    $where1 = 'created >= "'. $startYear .' 00:00:00"';
    $where2 = 'created <= "'. $endYear .' 23:59:59"';

    $raw_data = $this->checkout_model->where($where1)->where($where2)->where('status = 3')->get()->getResult();    

    $total_sales = 0;
    foreach($raw_data as $data) {
      $total_sales += $data->subtotal;
    }

    $return = [
      'date' => $this->today->toLocalizedString('YYYY'),
      'total_sales' => $total_sales,
    ];

    return $return;
  }

  private function active_users() {
    $active_users = $this->user_model->where('role = 3')->where('is_active = 1')->countAllResults();

    // print_r($active_users);

    return $active_users;
  }
}