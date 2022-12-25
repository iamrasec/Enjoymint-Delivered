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
    $this->order_products_model = model('OrderProductsModel');
    $this->user_model = model('UserModel');

    $this->sender_email = getenv('SMTP_EMAIL_USER');

    date_default_timezone_set('America/Los_Angeles');

    $this->today = new Time("now", "America/Los_Angeles", "en_EN");
    $this->yesterday = new Time("yesterday", "America/Los_Angeles", "en_EN");

    $this->data['user_jwt'] = ($this->guid != '') ? getSignedJWTForUser($this->guid) : '';

    $this->allowed_roles = [1,2,4];

    if($this->isLoggedIn !== 1 && $this->role !== 1) {
      return redirect()->to('/');
    }
  }
  
  public function index() {
    $data = [];

    if($this->isLoggedIn != 1) {
      return redirect()->to('/');
    }

    if(!in_array($this->role, $this->allowed_roles)) {
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

    $this->data['daily_product_sold'] = $this->daily_products_sold();
    $this->data['monthly_products_sold'] = $this->monthly_products_sold();
    $this->data['annual_products_sold'] = $this->annual_products_sold();

    $this->data['active_users'] = $this->active_users();
    $this->data['pending_users'] = $this->pending_users();

    $this->data['products_count'] = $this->products_count();

    // $this->yesterday_sales();die();

    echo view('dashboard', $this->data);     
  }

  private function daily_products_sold() {
    $toDate = $this->today->toDateString();

    $where1 = 'created >= "'. $toDate .' 00:00:00"';
    $where2 = 'created <= "'. $toDate .' 23:59:59"';

    $order_data = $this->checkout_model->where($where1)->where($where2)->where('status != 3')->get()->getResult();

    $order_ids = [];

    $products_sold = 0;

    foreach($order_data as $order) {
      array_push($order_ids, $order->id);
    }

    if(!empty($order_ids)) {
      $raw_data = $this->order_products_model->whereIn('order_id', $order_ids)->get()->getResult();

      foreach($raw_data as $data) {
        $products_sold += $data->qty;
      }
    }

    return $products_sold;
  }

  private function monthly_products_sold() {
    $currMonth = $this->today->month;
    $currYear = $this->today->year;

    $maxDays = date('t'); 

    $startMonth = $currYear.'-'.$currMonth.'-1 00:00:00';
    $endMonth = $currYear.'-'.$currMonth.'-'.$maxDays.' 23:59:59';

    $where1 = 'created >= "'. $startMonth .' 00:00:00"';
    $where2 = 'created <= "'. $endMonth .' 23:59:59"';

    $order_data = $this->checkout_model->where($where1)->where($where2)->where('status != 3')->get()->getResult();

    $order_ids = [];

    $products_sold = 0;

    foreach($order_data as $order) {
      array_push($order_ids, $order->id);
    }

    if(!empty($order_ids)) {
      $raw_data = $this->order_products_model->whereIn('order_id', $order_ids)->get()->getResult();

      foreach($raw_data as $data) {
        $products_sold += $data->qty;
      }
    }

    return $products_sold;
  }

  private function annual_products_sold() {
    $currYear = $this->today->year;

    $startYear = $currYear.'-1-1 00:00:00';
    $endYear = $currYear.'-12-31 23:59:59';

    $where1 = 'created >= "'. $startYear .' 00:00:00"';
    $where2 = 'created <= "'. $endYear .' 23:59:59"';

    $order_data = $this->checkout_model->where($where1)->where($where2)->where('status != 3')->get()->getResult();

    $order_ids = [];

    $products_sold = 0;

    foreach($order_data as $order) {
      array_push($order_ids, $order->id);
    }

    if(!empty($order_ids)) {
      $raw_data = $this->order_products_model->whereIn('order_id', $order_ids)->get()->getResult();

      foreach($raw_data as $data) {
        $products_sold += $data->qty;
      }
    }

    // echo "<pre>".print_r($where1, 1)."</pre>";
    // echo "<pre>".print_r($where2, 1)."</pre>";
    // echo "<pre>".print_r($order_data, 1)."</pre>";
    // echo "<pre>".print_r($order_ids, 1)."</pre>";
    // echo "<pre>".print_r($raw_data, 1)."</pre>";
    // echo "<pre>".print_r($products_sold, 1)."</pre>";

    return $products_sold;
  }

  private function daily_sales() {
    $toDate = $this->today->toDateString();

    $where1 = 'created >= "'. $toDate .' 00:00:00"';
    $where2 = 'created <= "'. $toDate .' 23:59:59"';

    $raw_data = $this->checkout_model->where($where1)->where($where2)->where('status != 3')->get()->getResult();

    $total_sales = 0;
    foreach($raw_data as $data) {
      $total_sales += $data->subtotal;
    }

    $yesterday = $this->yesterday_sales();

    $yesterday_sales = ($yesterday == 0) ? 1 : $yesterday;

    $diff = number_format((($total_sales - $yesterday_sales) / $yesterday_sales) * 100, 2, '.', ',');

    $return = [
      'date' => $this->today->toLocalizedString('MMM d'),
      'total_sales' => $total_sales,
      'yesterday_sales' => $yesterday,
      'diff' => $diff
    ];

    return $return;
  }

  private function yesterday_sales() {
    $toDate = $this->yesterday->toDateString();

    // echo "<pre>".print_r($toDate, 1)."</pre>";

    $where1 = 'created >= "'. $toDate .' 00:00:00"';
    $where2 = 'created <= "'. $toDate .' 23:59:59"';

    $raw_data = $this->checkout_model->where($where1)->where($where2)->where('status != 3')->get()->getResult();

    $total_sales = 0;
    foreach($raw_data as $data) {
      $total_sales += $data->subtotal;
    }

    return $total_sales;
  }

  private function monthly_sales() {
    $currMonth = $this->today->month;
    $currYear = $this->today->year;

    $maxDays = date('t'); 

    $startMonth = $currYear.'-'.$currMonth.'-1 00:00:00';
    $endMonth = $currYear.'-'.$currMonth.'-'.$maxDays.' 23:59:59';

    $where1 = 'created >= "'. $startMonth .' 00:00:00"';
    $where2 = 'created <= "'. $endMonth .' 23:59:59"';

    $raw_data = $this->checkout_model->where($where1)->where($where2)->where('status != 3')->get()->getResult();

    $total_sales = 0;
    foreach($raw_data as $data) {
      $total_sales += $data->subtotal;
    }

    $last_month = $this->last_month_sales();

    $last_month_sales = ($last_month == 0) ? 1 : $last_month;

    $diff = number_format((($total_sales - $last_month_sales) / $last_month_sales) * 100, 2, '.', ',');

    $return = [
      'date' => $this->today->toLocalizedString('MMM YYYY'),
      'total_sales' => $total_sales,
      'last_month_sales' => $last_month,
      'diff' => $diff
    ];

    return $return;
  }

  private function last_month_sales() {
    $currMonth = $this->today->month;
    $currYear = $this->today->year;

    $maxDays = date("t", mktime(0,0,0, date("n") - 1));

    $startMonth = $currYear.'-'.($currMonth - 1).'-1 00:00:00';
    $endMonth = $currYear.'-'.$currMonth.'-'.$maxDays.' 23:59:59';

    $where1 = 'created >= "'. $startMonth .' 00:00:00"';
    $where2 = 'created <= "'. $endMonth .' 23:59:59"';

    $raw_data = $this->checkout_model->where($where1)->where($where2)->where('status != 3')->get()->getResult();

    $total_sales = 0;
    foreach($raw_data as $data) {
      $total_sales += $data->subtotal;
    }

    return $total_sales;
  }

  private function annual_sales() {
    $currYear = $this->today->year;

    $startYear = $currYear.'-1-1 00:00:00';
    $endYear = $currYear.'-12-31 23:59:59';

    $where1 = 'created >= "'. $startYear .' 00:00:00"';
    $where2 = 'created <= "'. $endYear .' 23:59:59"';

    $raw_data = $this->checkout_model->where($where1)->where($where2)->where('status != 3')->get()->getResult();    

    $total_sales = 0;
    foreach($raw_data as $data) {
      $total_sales += $data->subtotal;
    }

    $last_year = $this->last_year_sales();

    $last_year_sales = ($last_year == 0) ? 1 : $last_year;

    $diff = number_format((($total_sales - $last_year_sales) / $last_year_sales) * 100, 2, '.', ',');

    $return = [
      'date' => $this->today->toLocalizedString('YYYY'),
      'total_sales' => $total_sales,
      'last_year_sales' => $last_year,
      'diff' => $diff
    ];

    return $return;
  }

  private function last_year_sales() {
    $currYear = $this->today->year;

    $startYear = ($currYear - 1) .'-1-1 00:00:00';
    $endYear = ($currYear - 1) .'-12-31 23:59:59';

    $where1 = 'created >= "'. $startYear .' 00:00:00"';
    $where2 = 'created <= "'. $endYear .' 23:59:59"';

    $raw_data = $this->checkout_model->where($where1)->where($where2)->where('status != 3')->get()->getResult();

    $total_sales = 0;
    foreach($raw_data as $data) {
      $total_sales += $data->subtotal;
    }

    return $total_sales;
  }

  private function active_users() {
    $active_users = $this->user_model->where('role = 3')->where('is_active = 1')->countAllResults();

    // print_r($active_users);

    return $active_users;
  }

  private function pending_users() {
    $pending_users = $this->user_model->where('role = 3')->where('is_active = 0')->countAllResults();

    // print_r($active_users);

    return $pending_users;
  }

  private function products_count() {
    $count = $this->product_model->where('archived = 0')->countAllResults();

    return $count;
  }
}