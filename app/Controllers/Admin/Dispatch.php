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
    $this->product_model = model('ProductModel');
    $this->strain_model = model('StrainModel');
    $this->brand_model = model('BrandModel');
    $this->category_model = model('CategoryModel');
    $this->measurement_model = model('MeasurementModel');
    $this->product_category = model('ProductCategory');
    $this->order_model = model('CheckoutModel');

    $this->data['user_jwt'] = getSignedJWTForUser($this->guid);

    if($this->isLoggedIn !== 1 && $this->role !== 1) {
      return redirect()->to('/');
    }
  }

  public function index()
  {
    $onfleet = new OnFleet("625fb8f0cfeadde86f7dd6bd28feaf38");
    
    $queryTasks = [
      "from" => "1663120518000",
      // "from" => "1640995200000",
      // "to" => "1663120518000",
      // "state" => 0
    ];

    $this->data["existing_tasks"] = $onfleet->tasks->get($queryTasks, null);
    // $this->data["existing_tasks"] = $onfleet->tasks->get();

    echo "<pre>".print_r($this->data["existing_tasks"], 1)."</pre>";

    // $teams = $onfleet->teams->get("QZQ~HNND6XFfiR66nlfRB6rd", null);  // List single team - Worker Phones

    // echo "<pre>".print_r($teams, 1)."</pre>";
  }

  public function test()
  {
    $onfleet = new OnFleet("625fb8f0cfeadde86f7dd6bd28feaf38");
    
    $queryTasks = [
      "from" => "1663120518000",
      // "from" => "1640995200000",
      // "to" => "1663120518000",
      // "state" => 0
    ];

    // $unassigned = $queryTasks;
    // $unassigned['state'] = 0;

    // $assigned = $queryTasks;
    // $assigned['state'] = "1,2";

    // $completed = $queryTasks;
    // $completed['state'] = 3;

    $this->data["tasks"] = $onfleet->tasks->get($queryTasks, null);

    return view('Dispatch/test', $this->data);
  }

  public function save()
  {
    $data = $this->request->getPost();

    // echo "<pre>".print_r($data, 1)."</pre>";die();

    $team_id = "QZQ~HNND6XFfiR66nlfRB6rd";  // Work Phones team

    $newTask = [
      "destination" =>  [
        "address" =>  [
          "unparsed" => $data['recipient_address']
        ],
        "notes" =>  $data['order_notes']
      ],
      "recipients" =>  [
        [
          "name" => $data['recipient_name'],
          "phone" => $data['recipient_phone'],
          "skipPhoneNumberValidation" => true,
        ] 
      ],
      // "completeAfter" =>  1455151071727,
      "notes" =>  $data['task_details'],
      "autoAssign" => [
        "mode" => "distance",
        "team" => $team_id
      ] 
    ];
    $onfleet = new OnFleet("625fb8f0cfeadde86f7dd6bd28feaf38");
    $onfleet->tasks->create($newTask, null);
    $onfleet->teams->autoDispatch($team_id,["routeEnd"=> null]);

    return redirect()->to('admin/dispatch/test');
  }

  public function test_endpoints()
  {
    // $this->onfleet = new OnFleet("625fb8f0cfeadde86f7dd6bd28feaf38");
    // if($this->onfleet->verifyKey() == 1) {
    //   echo "Key is Valid";
    // }
    // else {
    //   echo "Key is Invalid";
    // }

    // $worker_team_id = [
    //   "id" => "QZQ~HNND6XFfiR66nlfRB6rd",
    // ];

    $onfleet = new OnFleet("625fb8f0cfeadde86f7dd6bd28feaf38");

    // $teams = $onfleet->teams->get();  // List all teams
    $teams = $onfleet->teams->get("QZQ~HNND6XFfiR66nlfRB6rd");  // List single team - Worker Phones

    echo "<pre>".print_r($teams, 1)."</pre>";

    $newTask = [
      "destination" =>  [
        "address" =>  [
          "unparsed" => "25 Nicolls Lane, South San Francisco, CA 94080"],
          "notes" =>  "Small green door by garage door has pin pad, enter *4821*"],
          "recipients" =>  [
            [
              "name" => "Blas Silkovich",
              "phone" => "650-555-4481",
              "notes" => "Knows Neiman, VIP status.",
            ] 
          ],
          "completeAfter" =>  1455151071727,
          "notes" =>  "Order 332: 24oz Stumptown Finca El Puente, 10 x Aji de Gallina Empanadas, 13-inch Lelenitas Tres Leches",
          "autoAssign" => [
            "mode" => "distance",
          ] 
    ];

    $tasks = $onfleet->task->create($newTask);
    
    echo "<pre>".print_r($tasks, 1)."</pre>";
  }
}