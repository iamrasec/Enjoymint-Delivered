<?php

namespace App\Controllers;

use Onfleet\Onfleet;

class Dispatch extends BaseController
{
  public function __construct() 
  {
    helper(['jwt']);
    $this->data = [];
    $this->role = session()->get('role');
    $this->isLoggedIn = session()->get('isLoggedIn');
    $this->guid = session()->get('guid');
    $this->compound_model = model('CompoundModel');
    $this->product_model = model('ProductModel');
    $this->strain_model = model('StrainModel');
    $this->brand_model = model('BrandModel');
    $this->category_model = model('CategoryModel');
    $this->measurement_model = model('MeasurementModel');
    $this->product_category = model('ProductCategory');
    $this->order_model = model('CheckoutModel');
    $this->dispatch_model = model('DispatchModel');

    // $this->data['user_jwt'] = getSignedJWTForUser($this->guid);

    if($this->isLoggedIn !== 1 && $this->role !== 1) {
      return redirect()->to('/');
    }
  }

  public function index()
  {

  }

  public function auto_dispatch()
  {
    $team_id = "QZQ~HNND6XFfiR66nlfRB6rd";  // Work Phones team

    $onfleet = new OnFleet("625fb8f0cfeadde86f7dd6bd28feaf38");

    $if_online = $this->check_team_online_workers($team_id = "QZQ~HNND6XFfiR66nlfRB6rd", $states = "1,2");  // 0: off-duty, 1: is idle, 2: is active

    // print_r($if_online);

    if($if_online == true) {
      // print_r("There are workers online");
      $auto_dispatch = $onfleet->teams->autoDispatch($team_id, ["routeEnd" => null]);

      print_r($auto_dispatch);

      $this->dispatch_model->set("dispatch_id", $auto_dispatch['dispatchId'])->insert();
    }
    else {
      // print_r("All workers offline");
      echo "No workers available.  Nothing to Dispatch.";
    }

    // $onfleet->teams->autoDispatch($team_id,["routeEnd"=> null]);
  }

  public function check_team_online_workers($team_id = "QZQ~HNND6XFfiR66nlfRB6rd", $states = "")
  {
    // $team_id = "QZQ~HNND6XFfiR66nlfRB6rd";  // Work Phones team

    $onfleet = new OnFleet("625fb8f0cfeadde86f7dd6bd28feaf38");

    $query["teams"] = $team_id;

    if($states != "") {
      $query["states"] = $states;
    }

    $workers = $onfleet->workers->get($query, null);

    // echo "<pre>".print_r($workers, 1)."</pre>";

    if(!empty($workers)) {
      // print_r("There are workers online");
      return true;
    }
    else {
      // print_r("All workers offline");
      return false;
    }
  }
}