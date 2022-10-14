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
    $this->image_model = model('ImageModel');

    if($this->isLoggedIn !== 1 && $this->role !== 1) {
      return redirect()->to('/');
    }
    
  }
  
  public function index() 
  {
    // $data = [];
    $page_title = 'Verification Center';

    $this->data['page_body_id'] = "Verification Center";
    $this->data['breadcrumbs'] = [
      'parent' => [],
      'current' => $page_title,
    ];
    $this->data['page_title'] = $page_title;
    $this->data['verification_email'] = $this->user_model->get()->getResult();
    $this->data['verification'] = $this->verification_model->get()->getResult();
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
      $product_arr = [];
      $img= '';
				if(!empty($verify->image_validID)) {
					$imageIds = [];
					$imageIds = $verify->image_validID;
          
					// $images = $imageIds ?? $this->image_model->whereIn('id', $imageIds)->get()->getResult();
          $images = $this->image_model->where('id', $imageIds)->get()->getResult();
					$product_arr['validID'] = $images;
				}
        if(!empty($verify->image_profile)) {
					$imageIds = [];
					$imageIds = $verify->image_profile;
          
					// $images = $imageIds ?? $this->image_model->whereIn('id', $imageIds)->get()->getResult();
          $images = $this->image_model->where('id', $imageIds)->get()->getResult();
					$product_arr['profile'] = $images;
				}
        if(!empty($verify->image_MMIC)) {
					$imageIds = [];
					$imageIds = $verify->image_MMIC;
          
					// $images = $imageIds ?? $this->image_model->whereIn('id', $imageIds)->get()->getResult();
          $images = $this->image_model->where('id', $imageIds)->get()->getResult();
					$product_arr['mmic'] = $images;
				}

        // echo "<pre>".print_r($verify, 1)."</pre>";
        // echo "<pre>".print_r($product_arr, 1)."</pre>";

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
      if(isset($product_arr['validID'][0])){
        $img = '<img class="id_verification_image" src="'.base_url('users/verification/'.$product_arr['validID'][0]->filename).'" style="width:120px; width: 90px;">';
      }
      if(isset($product_arr['profile'][0])){
        $img = $img. '<img class="id_verification_image" src="'.base_url('users/verification/'.$product_arr['profile'][0]->filename).'" style="width:120px; width: 90px;">';
      }
      if(isset($product_arr['mmic'][0])){
        $img = $img. '<img class="id_verification_image" src="'.base_url('users/verification/'.$product_arr['mmic'][0]->filename).'" style="width:120px; width: 90px;"></a>';
      }
      //  print_r($product_arr);
      $name = $verify->first_name.' '.$verify->last_name;
      $start++;
      $data[] = array(
        $verify->cv_id,
        $name, 
        $stat,
        // '<a href="'.base_url('users/verification/'.$product_arr['images'][0]->filename).'">
        // '.$img.'
        //     </a>',
        $img,
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
    /**./
   * This function will update a product into the server
   * @param int id The id of the prodcut to be remove 
   * @return object a success indicator and the message
  */
  public function verification_deny(){

    $id = $this->request->getVar('verification_id');
    $message = $this->request->getVar('denial_message');
    $valid_id = $this->request->getVar('image_validID');
    $profile = $this->request->getVar('image_profile');

    if(isset($valid_id)){
      $param['image_validID'] = '';
    }
    if(isset($profile)){
      $param['image_profile'] = '';
    }
    $param['status'] = 2;
    $param['denial_message'] = $message;
    $this->verification_model->update($id, $param);
    die(json_encode(array("success" => TRUE,"message" => 'Deny Account!', 'data' => $param)));
  
  }

}