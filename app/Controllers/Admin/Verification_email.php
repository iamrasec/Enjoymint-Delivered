<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;

class Verification_email extends BaseController {

  public function __construct() {
    helper(['jwt']);

    $this->data = [];
    $this->role = session()->get('role');
    $this->isLoggedIn = session()->get('isLoggedIn');
    $this->guid = session()->get('guid');
    $this->user_model = model('UserModel');
    $this->verification_model = model('VerificationModel');
    
    $this->data['user_jwt'] = getSignedJWTForUser($this->guid);
    $this->image_model = model('imageModel');

    if($this->isLoggedIn !== 1 && $this->role !== 1) {
      return redirect()->to('/');
    }
    
  }
  
  public function index() 
  {
    // $data = [];
    $page_title = 'List Email Verification';

    $this->data['page_body_id'] = "email_verification";
    $this->data['breadcrumbs'] = [
      'parent' => [],
      'current' => $page_title,
    ];
    $this->data['page_title'] = $page_title;
    $this->data['verification_email'] = $this->user_model->get()->getResult();
    return view('Admin/verification_email', $this->data);
  }

  /**
   * This function will fetch product list from post request of datatable server side processing
   * 
   * @return json product list json format
  */
  public function getVerificationEmail()
  {
    $data  = array();
    $start = $_POST['start'];
    $length = $_POST['length'];

    $verification = $this->verification_model->getAllVerification($start, $length);
    foreach($verification as $verify){
      if($verify->status == '0'){
        $stat = 'Pending';
      }
      elseif($verify->status == '1'){
        $stat = 'Approved';
      }
      elseif($verify->status == '2'){
        $stat = 'Denied';
      }
      else{
        return 'Cancelled';
      }
      $name = $verify->first_name.' '.$verify->last_name;
      $start++;
      $data[] = array(
        $verify->cv_id,
        $name, 
        $stat,
        "<div style='margin-top:20px; margin-left:-35px;'><button class='btn btn-sm deny'  data-id='".$verify->cv_id."'>deny</button>| 
        <button class='btn btn-sm approve'  data-id='".$verify->cv_id."'>approve</button></div>",
      );
    }

    $output = array(
      "draw" => $_POST['draw'],
      "recordsTotal" => $this->verification_model->countAll(),
      "recordsFiltered" => $this->verification_model->countAll(),
      "data" => $data,
    );
    
    echo json_encode($output);

  }

    // ...
    /**
   * This function will update a product into the server
   * @param int id The id of the prodcut to be remove 
   * @return object a success indicator and the message
  */
  public function verification_approve($id){
    $this->verification_model->update($id, ['status' => 1]);
    die(json_encode(array("success" => TRUE,"message" => 'Approved Account!')));
  }

     // ...
    /**
   * This function will update a product into the server
   * @param int id The id of the prodcut to be remove 
   * @return object a success indicator and the message
  */
  public function verification_deny($id){
    $this->verification_model->update($id, ['status' => 2]);
    die(json_encode(array("success" => TRUE,"message" => 'Deny Account!')));
  }

}