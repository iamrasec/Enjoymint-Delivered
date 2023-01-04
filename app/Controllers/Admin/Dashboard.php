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

    $this->data['sales_channels_pct'] = $this->sales_channels_pct();
    $this->data['top_selling_prods'] = $this->top_selling_prods();

    // echo "<pre>".print_r($this->top_selling_prods(), 1)."</pre>";die();

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

    $prevMonth = ($currMonth == 1) ? 12 : ($currMonth - 1);
    $prevMonthYear = ($currMonth == 1) ? $currYear - 1 : $currYear;

    $startMonth = $prevMonthYear.'-'.$prevMonth.'-1 00:00:00';
    $endMonth = $prevMonthYear.'-'.$prevMonth.'-'.$maxDays.' 23:59:59';

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
    $order_count = 0;
    foreach($raw_data as $data) {
      $total_sales += $data->subtotal;
      $order_count++;
    }

    $last_year = $this->last_year_sales();

    $last_year_sales = ($last_year == 0) ? 1 : $last_year;

    $diff = number_format((($total_sales - $last_year_sales) / $last_year_sales) * 100, 2, '.', ',');

    $return = [
      'date' => $this->today->toLocalizedString('YYYY'),
      'total_sales' => $total_sales,
      'last_year_sales' => $last_year,
      'diff' => $diff,
      'order_count' => $order_count,
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

  private function sales_channels_pct() {
    $website_channel = $this->website_order_count();

    $channels = [
      'website' => $website_channel,
    ];

    return $channels;
  }

  private function website_order_count() {
    $website_orders = $this->annual_sales();

    return $website_orders['order_count'];
  }

  public function salesrev() {
    // $date = date('m', strtotime('2022-12-11'));

    $currYear = $this->today->year;

    $startYear = $currYear.'-1-1 00:00:00';
    $endYear = $currYear.'-12-31 23:59:59';

    $where1 = 'created >= "'. $startYear .' 00:00:00"';
    $where2 = 'created <= "'. $endYear .' 23:59:59"';

    $raw_data = $this->checkout_model->where($where1)->where($where2)->where('status != 3')->get()->getResult();    

    $jan = 0;
    $feb = 0;
    $mar = 0;
    $apr = 0;
    $may = 0;
    $jun = 0;
    $jul = 0;
    $aug = 0;
    $sep = 0;
    $oct = 0;
    $nov = 0;
    $dec = 0;

    foreach($raw_data as $data) {
      // if($data->delivery_schedule != NULL || $data->delivery_schedule != "0000-00-00") {
      //   $date = date_create($data->delivery_schedule ?? '');
      //   print_r(date_format($date, 'm'));
      // }
      

      $date = date_create($data->created ?? '');

      switch(date_format($date, 'm')) {
        case 1:
          $jan += $data->subtotal;
          break;
        case 2:
          $feb += $data->subtotal;
          break;
        case 3:
          $mar += $data->subtotal;
          break;
        case 4:
          $apr += $data->subtotal;
          break;
        case 5:
          $may += $data->subtotal;
          break;
        case 6:
          $jun += $data->subtotal;
          break;
        case 7:
          $jul += $data->subtotal;
          break;
        case 8:
          $aug += $data->subtotal;
          break;
        case 9:
          $sep += $data->subtotal;
          break;
        case 10:
          $oct += $data->subtotal;
          break;
        case 11:
          $nov += $data->subtotal;
          break;
        case 12:
          $dec += $data->subtotal;
          break;
      }

      // $total_sales += $data->subtotal;
      // $order_count++;
    }

    $return = [
      'jan' => $jan,
      'feb' => $feb,
      'mar' => $mar,
      'apr' => $apr,
      'may' => $may,
      'jun' => $jun,
      'jul' => $jul,
      'aug' => $aug,
      'sep' => $sep,
      'oct' => $oct,
      'nov' => $nov,
      'dec' => $dec
    ];

    die(json_encode(array("success" => TRUE, "message" => $return)));
  }

  private function top_selling_prods($limit = 10) {

    $db = \Config\Database::connect();
    $builder = $db->table('v_top_selling_products');
    $return = $builder->limit($limit)->get()->getResult();

    return $return;
  }
}