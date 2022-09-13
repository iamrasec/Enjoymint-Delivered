<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use Onfleet\Onfleet;

class Dispatch extends BaseController 
{
  // protected $onfleet;

  public function __construct() 
  {
    helper(['jwt']);
    $this->data = [];
    $this->role = session()->get('role');
    $this->isLoggedIn = session()->get('isLoggedIn');
    $this->guid = session()->get('guid');
    $this->compound_model = model('CompoundModel');

    $this->data['user_jwt'] = getSignedJWTForUser($this->guid);

    if($this->isLoggedIn !== 1 && $this->role !== 1) {
      return redirect()->to('/');
    }
  }

  public function index()
  {
    // $worker_team_id = [
    //   "id" => "QZQ~HNND6XFfiR66nlfRB6rd",
    // ];
    
    $onfleet = new OnFleet("625fb8f0cfeadde86f7dd6bd28feaf38");

    $teams = $onfleet->teams->get("QZQ~HNND6XFfiR66nlfRB6rd");

    echo "<pre>".print_r($teams, 1)."</pre>";
  }

  public function test()
  {
    $this->onfleet = new OnFleet("625fb8f0cfeadde86f7dd6bd28feaf38");
    if($this->onfleet->verifyKey() == 1) {
      echo "Key is Valid";
    }
    else {
      echo "Key is Invalid";
    }
  }
}