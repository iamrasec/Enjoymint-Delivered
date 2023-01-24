<?php

namespace App\Controllers;

use App\Models\UserModel;

class Users extends BaseController
{

	public function __construct() 
	{
		helper(['jwt', 'edimage']);

		$this->data = [];
		$this->role = session()->get('role');
    	$this->isLoggedIn = session()->get('isLoggedIn');
		$this->guid = session()->get('guid');
		$this->uid = session()->get('id');
		$this->id = session()->get('id');
		$this->user_model = model('UserModel');
		$this->customerverification_model = model('VerificationModel');
		$this->checkout_model = model('CheckoutModel');
    	$this->order_products_model = model('OrderProductsModel');
		$this->forgotpassword_model = model('ForgotpasswordModel');
		$this->image_model = model('ImageModel');
		$this->rating_model = model('RatingModel');
		$this->location_model = model('LocationModel');

        $this->order_model = model('CheckoutModel');
		$this->data['user_jwt'] = ($this->guid != '') ? getSignedJWTForUser($this->guid) : '';

		$this->sender_email = 'cesar@fuegonetworx.com';
		$this->reciever_email = 'welyelf@fuegonetworx.com';
	}

	public function index()
	{
		if($this->isLoggedIn) {
			return redirect()->to('users/dashboard');
		}

		helper(['form']);

		$this->data['post_data'] = $this->request->getPost();

		// d($this->request->getPost());

		if($this->request->getPost()) {
			// Form login validation here
			$rules = [
				'email' => 'min_length[6]|max_length[50]|valid_email',
				'password' => 'required|min_length[3]|max_length[255]|validateUser[username,password]',
			];

			$errors = [
				'password' => [
					'validateUser' => 'Email or Password don\'t match',
				]
			];

			// $model = new UserModel();

			// $user = $model->where('username', $this->request->getVar('username'))->first();
			$user = $this->user_model->where('email', $this->request->getVar('email'))->first();

			// print_r($user);die();

			$session = session();
			if($user) {
				if(password_verify($this->request->getPost('password'), $user['password'])) {
					if($user['is_active'] == 0) {
						$session->setFlashdata('message', 'Account Inactive.  Please activate your account.');
						return redirect()->to('/users');
					}

					$this->setUserSession($user);

					if(isset($_GET['algt'])) {
						return redirect()->to($_GET['algt']);
					}
					
					if($user['role'] == 1) {
						return redirect()->to('admin/dashboard');
					}
					elseif($user['role'] == 2) {
						return redirect()->to('admin/dashboard');
					}
					elseif($user['role'] == 4) {
						return redirect()->to('admin/orders');
					}
					else {
						return redirect()->to('/');
					}
				}
				else {
					$session->setFlashdata('message', 'Incorrect Email or Password.');
					return redirect()->to('/users');
				}
			}
			else {
				$session->setFlashdata('message', 'Incorrect Email or Password.');
				return redirect()->to('/users');
			}
		}

		$this->data['page_body_id'] = "user_login";
		
		echo view('login', $this->data);
	}

	public function login() 
	{
		// helper(['form']);
		// print_r($_POST);
		// die();
		print_r($this->request->getPost());
	}

	private function setUserSession($user) 
	{
		$this->data = [
			'id' => $user['id'],
			'guid' => $user['guid'],
			// 'username' => $user['username'],
			'first_name' => $user['first_name'],
			'last_name' => $user['last_name'],
			'email' => $user['email'],
			'role' => $user['role'],
			'isLoggedIn' => true,
		];

		session()->set($this->data);
		return true;
	}

	public function register() 
	{
		helper(['form']);

		// Set dummy jwt
		// $this->data['user_jwt'] = '000-000-000';
		
		// if($this->request->getMethod() == 'post') {
		if($this->request->getPost()) {
			// print_r($this->request->getPost());die();
			// Form validation here
			$rules = [
				'first_name' => [
					'rules' => 'required|min_length[3]|max_length[20]',
					'errors' => [
						'required' => 'Please input your First Name.',
						'min_length' => 'First Name should be longer than 3 characters.',
						'max_length' => 'First Name should be no longer than 20 characters.',
					],
				],	
				'last_name' => [
					'rules' => 'required|min_length[3]|max_length[20]',
					'errors' => [
						'required' => 'Please input your Last Name.',
						'min_length' => 'Last Name should be longer than 3 characters.',
						'max_length' => 'Last Name should be no longer than 20 characters.',
					],
				],
				'email' => [
					'rules' => 'min_length[6]|max_length[50]|valid_email|is_unique[users.email]',  // check if email is valid.  check if email is unique on users table
					'errors' => [
						'min_length' => 'Email should be longer than 6 characters.',
						'max_length' => 'Email should be no longer than 50 characters.',
						'valid_email' => 'Please input a valid Email.',
						'is_unique' => 'The Email you provided already has an account. Please try another one or Sign in with that email.',
					],
				],
				'password' => [
					'rules' => 'required|min_length[8]|max_length[255]',
					'errors' => [
						'min_length' => 'Password should be longer than 8 characters.',
						'max_length' => 'Password should be no longer than 255 characters.',
					],
				],
				'password_confirm' => [
					'rules' => 'matches[password]',
					'errors' => [
						'matches' => 'Your Password does not match.'
					],
				],
				'accept_terms' => [
					'rules' => 'required',
					'errors' => [
						'required' => 'Please read and accept our Terms and Conditions.'
					],
				],
			];

			if(!$this->validate($rules)) {
				$this->data['validation'] = $this->validator;

				return view('register', $this->data);
			}
			else {
				// $model = new UserModel();

				$newData = [
					'guid' => $this->_generate_guid(),
					// 'username' => $this->request->getVar('username'),
					'first_name' => $this->request->getVar('first_name'),
					'last_name' => $this->request->getVar('last_name'),
					'email' => $this->request->getVar('email'),
					'password' => $this->request->getVar('password'),
					'role' => 3,  // Customer User Role
				];

				// print_r($newData);

				// $model->save($newData);
				$this->user_model->save($newData);

				$user = $this->user_model->where('email', $this->request->getVar('email'))->first();

				// print_r($user);die();

				// Preparation for email.
				$this->send_validation($this->sender_email, $user);
				
				// $this->setUserSession($user);
				
				// $session = session();
				// $session->setFlashdata('success', 'Successful Registration');
				// return redirect()->to('/dashboard');

				$this->data['success'] = 'Please check your email for account activation.';

				return view('register', $this->data);
			}
		}

		return view('register', $this->data);
	}

	public function profile() 
	{
		$data = [];
		helper(['form']);
		// $model = new UserModel();

		if($this->request->getPost()) {
			// Profile validation here
			$rules = [
				'username' => 'required|min_length[3]|max_length[20]|is_unique[users.username]',
				'first_name' => 'required|min_length[3]|max_length[20]',
				'last_name' => 'required|min_length[3]|max_length[20]',
			];

			if($this->request->getPost('password') != '') {
				$rules['password'] = 'required|min_length[8]|max_length[255]';
				$rules['password_confirm'] = 'matches[password]';
			}

			if(!$this->validate($rules)) {
				$data['validation'] = $this->validator;
			}
			else {
				// $model = new UserModel();

				$newData = [
					'id' => session()->get('id'),
					'username' => $this->request->getPost('username'),
					'first_name' => $this->request->getPost('first_name'),
					'last_name' => $this->request->getPost('last_name'),
				];

				if($this->request->getPost('password') != '') {
					$newData['password'] = $this->request->getPost('password');
				}

				// $model->save($newData);
				$this->user_model->save($newData);
				session()->setFlashdata('success', 'Successfully Updated');
				return redirect()->to('/profile');
			}
		}

		// $data['user'] = $model->where('id', session()->get('id'))->first();
		$data['user'] = $this->user_model->where('id', session()->get('id'))->first();
		$role = session()->get('role');

    if($role == 'admin') {
      echo view('templates/admin_header', $data);
    }
    else {
      echo view('templates/header', $data);
    }
		echo view('profile');
		echo view('templates/footer');
	}

	public function logout() 
	{
		helper('cookie');
		if(!delete_cookie('cart_data')) {
			unset($_COOKIE['cart_data']);
			setcookie('cart_data', '', time() - 3600, "/");
		}
		session()->destroy();
		return redirect()->to('/');
	}

	private function _generate_guid() 
	{
		return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
	}
	
	public function checkout()
	{
		if($this->isLoggedIn !== 1) {

		}
        echo view('checkout', $this->data);
	}

	function generateRandomString($length = 10) 
	{
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	} 
	
	public function forgot_password()
	{
		helper(['form']);
		 $data = [];
		  if($this->request->getPost()){
			$rules = [
				'email' => [ 
					'label' => 'Email',
					'rules' => 'required|valid_email',
					'errors' => [
						'required' =>'{field} field required',
						'valid_email' => 'Valid {field} required'
					]				
					],
				];
				if ($this->validate($rules)){
					
					$email = $this->request->getVar('email', FILTER_SANITIZE_EMAIL);
					$verify_email = $this->user_model->verifyEmail($email);

					if($verify_email) {
						$unique_id = $this->generateRandomString($length = 10);
						$newData = [
							'unique_id' => $unique_id,
							'user_id' => $verify_email['id'],
						];

						if($this->forgotpassword_model->save($newData)) {
							$verify_email['email'] = $email;
							$verify_email['token'] = $unique_id;
							$verify_email['reset_url'] = base_url('users/reset_password/'.$unique_id);

						 $send_mail = $this->send_reset_password($this->sender_email, $verify_email);

						 if($send_mail) {
							session()->setTempdata('success', 'Reset password link sent to your registered email. Please verify with in 15 minutes', 3);
							return redirect()->to(current_url());
						 }
						}
						else{
							return redirect()->to(current_url());
						}
					}
					else
					{
						session()->setTempdata('error','Email does not exists', 3);
						return redirect()->to(current_url());
					}
				}
				else{
					$data['validation']= $this->validator;	
				}
		 }
		return view('forgot-password', $this->data);
	}

	public function reset_password($unique_id)
	{
		$session = session();	
		$checkUniqueId = $this->forgotpassword_model->where('unique_id', $unique_id)->first();
		if($checkUniqueId){
			$session->forgot_password_id = $checkUniqueId;
			echo view('reset_password', $this->data);
		}else{
			echo 'Invalid';
		}
	}

	public function updatePassword()
	{
		$session = session();
		
		if($this->request->getPost()){

			$rules = [
				'new_password' => [
					'rules' => 'required|min_length[8]|max_length[255]',
					'errors' => [
						'min_length' => 'Password should be longer than 8 characters.',
						'max_length' => 'Password should be no longer than 255 characters.',
					],
				],
				'confirm_password' => [
					'rules' => 'matches[new_password]',
					'errors' => [
						'matches' => 'Your Password does not match.'
					],
				],
			];

			if(!$this->validate($rules)) {
				$this->data['validation'] = $this->validator;

				return view('reset_password', $this->data);
			}
			else {
				$new_password = [
					'password' => $this->request->getVar('new_password'),
				];
				$data = $session->get('forgot_password_id');
				$save_update = $this->user_model->update($data['user_id'], $new_password);

				if($save_update) {
					$this->forgotpassword_model->delete($data['id']);
					$session->remove('forgot_password_id');

					$session->setFlashdata('message', 'Password Updated successfully');
					return redirect()->to('users');
				}
			}				
		}
		else {
			$data['validation'] = $this->validator;
		}
	}

	public function confirm($token) 
	{
		helper('jwt');
		$validate = validateJWTFromRequestOutputUser($token);

		if(is_array($validate) && !empty($validate) && $validate['is_active'] == 0) {
			$update_data = [
				'id' => $validate['id'],
				'is_active' => 1,
			];

			// echo "<pre>".print_r($update_data, 1)."</pre>"; die();

			$this->user_model->save($update_data);
		}

		else if($validate['is_active'] == 1){
			$this->data['status'] = 'Account is already activated';

			return redirect()->to('/users/customerverification');
		}

		$validate['is_active'] = 1;

		$this->setUserSession($validate);

		$guid = session()->get('guid');

		$validate['user_jwt'] = getSignedJWTForUser($guid);

		// echo "<pre>".print_r($validate, 1)."</pre>"; die();

		return redirect()->to('/users/customerverification');

		// print_r($validate);die();
	}

	public function send_validation($sender_email, $user)
	{
		$confirm_key = getSignedJWTForUser($user['guid']);
		$user['confirm_url'] = base_url('users/confirm/'. $confirm_key);

		$email = \Config\Services::email();
		$email->setFrom($sender_email);
		$email->setTo($user['email']);
		$email->setSubject('Confirm Your Registration');
		$email->setNewline = "\r\n";

		$template = view('email/registration', $user);

		// $body  = 'Name: ' . $recipient_name . "\r\n";
		// $body .= 'E-Mail: ' . $recipient_email . "\r\n";
		// $body .= 'Message: ' . $message . "\r\n";

		$email->setMessage($template);

		if($email->send()) {
				return true;
		} 

		// Output errors for debugging if necessary
		// echo $email->printDebugger();
		// exit;

		//Handle any errors here...
	} 

	public function send_reset_password($sender_email, $user)
	{
		$email = \Config\Services::email();
		$email->setFrom($sender_email);
		$email->setTo($user['email']);
		$email->setSubject('Password Reset');
		$email->setNewline = "\r\n";

		$template = view('email/reset_password', $user);

		// $body  = 'Name: ' . $recipient_name . "\r\n";
		// $body .= 'E-Mail: ' . $recipient_email . "\r\n";
		// $body .= 'Message: ' . $message . "\r\n";

		$email->setMessage($template);

		if($email->send()) {
				return true;
		}
	}

	public function dashboard($tab = "_orders_tab")
	{   $session =session();
		$page_title = "Dashboard";

		// print_r($categories);

		$this->data['page_body_id'] = "shop";
		$this->data['breadcrumbs'] = [
		'parent' => [],
		'current' => $page_title,
		];
		$this->data['page_title'] = $page_title;
		$this->data['user_data'] = $this->user_model->getUserByGuid($this->guid);
		// $this->data['orders'] = array();

		// Identify the tabs that goes with the Dashboard
		$dashboard_tabs = ['_orders_tab', '_archive_tab','_review','_personal_info_tab', '_address_tab'];

		// Added security to only allow tabs that are specified in the array
		if(in_array($tab, $dashboard_tabs)) {			
			if($tab == '_orders_tab') {
				$this->data['orders'] = $this->_user_active_orders($this->uid);
				$this->data['pager'] = $this->checkout_model->pager;
			}
			else if($tab == '_archive_tab') {
				$this->data['orders'] = $this->_user_archive_orders($this->uid);
				$this->data['pager'] = $this->checkout_model->pager;
			}
			else if($tab == '_review') {
				$this->data['orders'] = $this->_user_review_orders($this->uid);
				$this->data['pager'] = $this->checkout_model->pager;
				$this->data['user_data'];
				
				
				
			}

			$this->data['active_tab'] = $tab;		// If tab specified is found, return tab
		}
		else {
			$this->data['active_tab'] = '_orders_tab';  // If tab specified is not found, default back to order tab
		}
		$user_id = $this->uid;
    $this->data['location_keyword'] = $this->location_model->where('user_id', $user_id )->select('address')->first();   
		return view('customer_dashboard/index', $this->data);
	}

	public function customerVerification()
	{   
		$session = session();
		helper(['form', 'jwt']);
		$page_title = "Upload ID";

		// print_r($categories);

		$this->data['page_body_id'] = "customer Verification";
		$this->data['breadcrumbs'] = [
		'parent' => [],
		'current' => $page_title,
		];
		$this->data['page_title'] = $page_title;
		$user_id = $this->uid;
		$upload2 = $this->customerverification_model->get()->getResult();
		$verify = $this->customerverification_model->verifyUser($user_id);

		$this->data['user_jwt'] = getSignedJWTForUser($this->guid);
		
		if(empty($upload2)) {
			return view('User/id_upload', $this->data);
		}else{
			if(empty($verify)) {
			
				return view('User/id_upload', $this->data);	
			}
			else{
				$all_products = $this->customerverification_model->get()->getResult();
					
				$product_arr = [];
				foreach($all_products as $product) {
					// echo "<pre>".print_r($product, 1)."</pre>";
					if(!empty($product->image_validID)) {
						$imageIds = [];
						$imageIds = $product->image_validID;
						$images = $this->image_model->where('id', $imageIds)->get()->getResult();
						$product_arr['validID'] = $images;
						
						$this->data['validID'] = $product_arr['validID'];
					}
					if(!empty($product->image_profile)) {
						$imageIds = [];
						$imageIds = $product->image_profile;
						$images = $this->image_model->where('id', $imageIds)->get()->getResult();
						$product_arr['profile'] = $images;

						$this->data['profile'] = $product_arr['profile'];
					}
					if(!empty($product->image_MMIC)) {
						$imageIds = [];
						$imageIds = $product->image_MMIC;
						$images = $this->image_model->where('id', $imageIds)->get()->getResult();
						$product_arr['mmic'] = $images;

						$this->data['mmic'] = $product_arr['mmic'];
					}
					if($product->status == 2){
						$this->data['success'] = $product->denial_message;
						$this->data['error'] = 'Your photo has been denied for verification!';
						$this->data['color'] = 'Red';
						$this->data['upload'] = '';
						if(empty($product->image_validID)){
							$this->data['upload'] = '<label for="exampleFormControlInput1" class="form-label">Select Valid ID(*Driver License):</label>
							<div>
							<input type="file" name="valid_ID[]" id="file" accept="image/png, image/jpeg, image/jpg" class="form-control fc" id="exampleFormControlInput1" placeholder="name@example.com" >
							</div>
							'; 
						}
						if(empty($product->image_profile)){
							$this->data['upload'] = $this->data['upload'].'<label class="form-label">Select Selfie photo with your Valid ID:</label>
							<div>
								<input type="file" name="profile[]" id="file1" class="form-control fc" accept="image/png, image/jpeg, image/jpg" >
							</div>';
						}
						if(empty($product->image_MMIC)){
							$this->data['upload'] = $this->data['upload'].'
							<label class="form-label">Select Medical Marijuana Identification Card photo (optional):</label>
							<div>  
							<input type="file" name="mmic[]" id="file2" class="form-control fc" accept="image/png, image/jpeg, image/jpg" >
							</div>';
						}

						$this->data['button'] = '<input type="submit" class="btn btn-primary" value="upload" /> ';
						$this->data['display'] = 'inline';
					}elseif($product->status == 1){
						$this->data['success'] = 'Your account has been Verified.';
						$this->data['color'] = 'Green';
						$this->data['upload'] = '';
						$this->data['display'] = 'none';
						$this->data['button'] = '<input type="submit" class="btn btn-primary" value="upload" /> ';
					}else{
						$this->data['success'] = 'Your account is on processing for verification.';
						$this->data['color'] = 'orange';
						$this->data['upload'] = '';	
						$this->data['display'] = 'none';
						$this->data['button'] = '<input type="submit" class="btn btn-primary" value="upload" /> ';
						}
					
				}
			}
		}
		 $this->data['data'] = $all_products;
		return view('User/id_upload', $this->data);
	   
}

public function verification($filename) {
    $filepath = WRITEPATH . 'uploads/' . $filename;

    $mime = mime_content_type($filepath);
    header('Content-Length: ' . filesize($filepath));
    header("Content-Type: $mime");
    header('Content-Disposition: inline; filename="' . $filepath . '";');
    readfile($filepath);
    exit();
}
	
public function uploadID(){
	helper(['form', 'functions']); // load helpers
    addJSONResponseHeader();
	$user_id = $this->uid;
	$verify = $this->customerverification_model->verifyUser($user_id);

	if(empty($verify)){
			$images = array();		
			if (!empty($this->request->getFiles())) {
				$file = $this->request->getFiles(); // get all files from post request
				//upload for valid ID
			   	if(array_key_exists('file', $file)){
					if (!$file['file']->hasMoved()) {
					$fileName = $file['file']->getRandomName(); // generate a new random name
					$type = $file['file']->getMimeType();
					$file['file']->move( WRITEPATH . 'uploads', $fileName); // move the file to writable/uploads
					
					// json data to be save to image
					$imageData = [
					  'filename' => $fileName,
					  'mime' => $type,
					  'url' => 'writable/uploads/'. $fileName,
					];
					$this->image_model->save($imageData); // try to save to images table
					$imageId = $this->image_model->insertID();
					$data['image_validID'] = $imageId;
					}
			   	}
				//upload for profile
				    if(array_key_exists('file1', $file)){
					if (!$file['file1']->hasMoved()) {
					$fileName = $file['file1']->getRandomName(); // generate a new random name
					$type = $file['file1']->getMimeType();
					$file['file1']->move( WRITEPATH . 'uploads', $fileName); // move the file to writable/uploads
					
					// json data to be save to image
					$imageData = [
					  'filename' => $fileName,
					  'mime' => $type,
					  'url' => 'writable/uploads/'. $fileName,
					];
					$this->image_model->save($imageData); // try to save to images table
					$image_profile_id = $this->image_model->insertID();
					$data['image_profile'] = $image_profile_id;
					}
			   	}
				   if(array_key_exists('file2', $file)){
					if (!$file['file2']->hasMoved()) {
					$fileName = $file['file2']->getRandomName(); // generate a new random name
					$type = $file['file2']->getMimeType();
					$file['file2']->move( WRITEPATH . 'uploads', $fileName); // move the file to writable/uploads
					
					// json data to be save to image
					$imageData = [
					  'filename' => $fileName,
					  'mime' => $type,
					  'url' => 'writable/uploads/'. $fileName,
					];
					$this->image_model->save($imageData); // try to save to images table
					$image_mmic = $this->image_model->insertID();
					$data['image_MMIC'] = $image_mmic;
					}
			   	}
				   if(empty($user_id)){
					$data_arr = array("success" => False,"message" => 'Please Sign in First!');
				}else{
				$data['user_id'] = $this->uid;
				$data['status'] = '0';

				$this->customerverification_model->save($data); 
				
				$data_arr = array("success" => TRUE,"message" => 'Upload Success!');
				}
				
			  }else{
				$data_arr = array("success" => False,"message" => 'Please add photo to verify your account!');
			  }
				
			  die(json_encode($data_arr));
	}else{

	
		$images = array();	
		
		if (!empty($this->request->getFiles())) {
			$file = $this->request->getFiles(); 	
		if(array_key_exists('file', $file)){
			if (!$file['file']->hasMoved()) {
			$fileName = $file['file']->getRandomName(); // generate a new random name
			$type = $file['file']->getMimeType();
			$file['file']->move( WRITEPATH . 'uploads', $fileName); // move the file to writable/uploads
			
			// json data to be save to image
			$imageData = [
			  'filename' => $fileName,
			  'mime' => $type,
			  'url' => 'writable/uploads/'. $fileName,
			];
			$this->image_model->save($imageData); // try to save to images table
			$imageId = $this->image_model->insertID();
			$data['image_validID'] = $imageId;
			}
			$this->customerverification_model->update($verify['id'], ['status' => 0, 'image_validID' => $data['image_validID'] , 'denial_message' => null]);
		   }
		//upload for profile
		   if(array_key_exists('file1', $file)){
			if (!$file['file1']->hasMoved()) {
			$fileName = $file['file1']->getRandomName(); // generate a new random name
			$type = $file['file1']->getMimeType();
			$file['file1']->move( WRITEPATH . 'uploads', $fileName); // move the file to writable/uploads
			
			// json data to be save to image
			$imageData = [
			  'filename' => $fileName,
			  'mime' => $type,
			  'url' => 'writable/uploads/'. $fileName,
			];
			$this->image_model->save($imageData); // try to save to images table
			$image_profile_id = $this->image_model->insertID();
			$data['image_profile'] = $image_profile_id;
			}
			$this->customerverification_model->update($verify['id'], ['status' => 0, 'image_profile' => $data['image_profile'], 'denial_message' => null]);
		   }
		   //mmic
		   if(array_key_exists('file2', $file)){
			if (!$file['file2']->hasMoved()) {
			$fileName = $file['file2']->getRandomName(); // generate a new random name
			$type = $file['file2']->getMimeType();
			$file['file2']->move( WRITEPATH . 'uploads', $fileName); // move the file to writable/uploads
			
			// json data to be save to image
			$imageData = [
			  'filename' => $fileName,
			  'mime' => $type,
			  'url' => 'writable/uploads/'. $fileName,
			];
			$this->image_model->save($imageData); // try to save to images table
			$image_mmic = $this->image_model->insertID();
			$data['image_MMIC'] = $image_mmic;
			}
			$this->customerverification_model->update($verify['id'], ['status' => 0, 'image_MMIC' => $data['image_MMIC'], 'denial_message' => null]);
		   }

				
				$data_arr = array("success" => TRUE,"message" => 'Upload Success!');
			  }else{
				$data_arr = array("success" => False,"message" => 'Please add photo to verify your account!');
			  }

			

	}
	die(json_encode($data_arr));


}


	public function send_verification($sender_email, $reciever_email)
	{
		// $confirm_key = getSignedJWTForUser($user['guid']);
		$user['confirm_url'] = base_url('admin/verification_email/');

		$email = \Config\Services::email();
		$email->setFrom($sender_email);
		$email->setTo($reciever_email);
		$email->setSubject('Confirm the Verification');
		$email->setNewline = "\r\n";

		$template = view('email/verification', $user);

		// $body  = 'Name: ' . $recipient_name . "\r\n";
		// $body .= 'E-Mail: ' . $recipient_email . "\r\n";
		// $body .= 'Message: ' . $message . "\r\n";

		$email->setMessage($template);

		if($email->send()) {
				return true;
		} 

		// Output errors for debugging if necessary
		// echo $email->printDebugger();
		// exit;

		//Handle any errors here...
	} 

	// public function get_user_orders($uid)
	// {
	// 	$active_orders = $this->_user_active_orders($uid);

	// 	$archive_orders = $this->_user_archive_orders($uid);

	// 	$orders = ['active_orders' => $active_orders, 'previous_orders' => $archive_orders];
        
	// 	return $orders;
	// }

	private function _user_active_orders($uid)
	{
		// $orders = $this->checkout_model->where('customer_id', $uid)->whereIn('status', [0,1])->get()->getResult();
		$orders = $this->checkout_model->where('customer_id', $uid)->whereIn('status', [0,1])->paginate(10);

		for($i = 0; $i < count($orders); $i++) {
			$orders[$i]['products'] = $this->order_products_model->where('order_id', $orders[$i]['id'])->get()->getResult();

			for($j = 0; $j < count($orders[$i]['products']); $j++) {
				$orders[$i]['products'][$j]->images = getProductImage($orders[$i]['products'][$j]->product_id);
			}
		}

		return $orders;
	}

	private function _user_archive_orders($uid)
	{
		// $orders = $this->checkout_model->where('customer_id', $uid)->whereIn('status', [2])->get()->getResult();
		$orders = $this->checkout_model->where('customer_id', $uid)->whereIn('status', [2])->paginate(10);

		for($i = 0; $i < count($orders); $i++) {
			$orders[$i]['products'] = $this->order_products_model->where('order_id', $orders[$i]['id'])->get()->getResult();

			for($j = 0; $j < count($orders[$i]['products']); $j++) {
				$orders[$i]['products'][$j]->images = getProductImage($orders[$i]['products'][$j]->product_id);
			}
		}

		return $orders;
	}

	private function _user_review_orders($uid)
	{
		
		$orders = $this->checkout_model->where('customer_id', $uid)->where('is_rated', [0])->whereIn('status', [2])->paginate(10);
		//$orders = $this->checkout_model->getOrders($uid);
		
		$this->data['user_data'] = $this->user_model->select("id, CONCAT(first_name, ' ', last_name) AS customer_name")->getUserByGuid($this->guid);
		for($i = 0; $i < count($orders); $i++) {
			$orders[$i]['products'] = $this->order_products_model->where('order_id', $orders[$i]['id'])->get()->getResult();

			for($j = 0; $j < count($orders[$i]['products']); $j++) {
				$orders[$i]['products'][$j]->images = getProductImage($orders[$i]['products'][$j]->product_id);
			}
		}
	


		return $orders;
		
	
	}

	public function update_personal_info()
	{
		$session = session();

		if($this->request->getPost()) {

			// echo "<pre>".print_r($this->request->getPost(), 1)."</pre>";

			$rules = [
				'email' => [
					'rules' => 'min_length[6]|max_length[50]|valid_email|is_unique[users.email, id, '.$this->uid.']',  // check if email is valid.  check if email is unique on users table
					'errors' => [
						'min_length' => 'Email should be longer than 6 characters.',
						'max_length' => 'Email should be no longer than 50 characters.',
						'valid_email' => 'Please input a valid Email.',
						'is_unique' => 'The Email you provided already has an account. Please try another one or Sign in with that email.',
					],
				],
				'mobile_phone' => [
					// 'rules' => 'required|mobileValidation[mobile_phone]|is_unique[users.mobile_phone, id, '.$this->uid.']',
					'rules' => 'required|is_unique[users.mobile_phone, id, '.$this->uid.']',
					'errors' => [
						'required' => 'Mobile Number is required',
						'mobileValidation' => 'Invalid Mobile Number',
						'is_unique' => 'Mobile number already exists',
					],
				],
			];

			if(!$this->validate($rules)) {
				$this->data['validation'] = $this->validator;

				// echo "<pre>".print_r($this->validator->listErrors(), 1)."</pre>"; die();

				$session->setFlashdata('error', $this->validator->listErrors());
				return redirect()->to('users/dashboard/_personal_info_tab');
			}
			else {
				$update_data = [
					'email' => $this->request->getVar('email'),
					'mobile_phone' => $this->request->getVar('mobile_phone'),
				];

				$save_update = $this->user_model->update($this->uid, $update_data);

				if($save_update) {
					$session->setFlashdata('message', 'Personal Info Updated.');
					return redirect()->to('users/dashboard/_personal_info_tab');
				}
			}
		}
	}

	public function add_user() 
	{
		helper(['form']);

		if($this->request->getPost()) {
			$validation =  \Config\Services::validation();

			$rules = [
				'first_name' => [
					'rules' => 'required|min_length[3]|max_length[20]',
					'errors' => [
						'required' => 'Please input your First Name.',
						'min_length' => 'First Name should be longer than 3 characters.',
						'max_length' => 'First Name should be no longer than 20 characters.',
					],
				],	
				'last_name' => [
					'rules' => 'required|min_length[3]|max_length[20]',
					'errors' => [
						'required' => 'Please input your Last Name.',
						'min_length' => 'Last Name should be longer than 3 characters.',
						'max_length' => 'Last Name should be no longer than 20 characters.',
					],
				],
				'email' => [
					'rules' => 'min_length[6]|max_length[50]|valid_email|is_unique[users.email]',  // check if email is valid.  check if email is unique on users table
					'errors' => [
						'min_length' => 'Email should be longer than 6 characters.',
						'max_length' => 'Email should be no longer than 50 characters.',
						'valid_email' => 'Please input a valid Email.',
						'is_unique' => 'The Email you provided already has an account. Please try another one or Sign in with that email.',
					],
				],
				'role' => [
					'rules' => 'required|integer',
					'errors' => [
						'required' => 'Please select a Role.',
					],
				],
				'password' => [
					'rules' => 'required|min_length[8]|max_length[255]',
					'errors' => [
						'min_length' => 'Password should be longer than 8 characters.',
						'max_length' => 'Password should be no longer than 255 characters.',
					],
				],
				'password_confirm' => [
					'rules' => 'matches[password]',
					'errors' => [
						'matches' => 'Your Password does not match.'
					],
				],
			];

			if($this->validate($rules)) {
				$this->data['validation'] = $this->validator;

				$newData = [
					'guid' => $this->_generate_guid(),
					'first_name' => $this->request->getVar('first_name'),
					'last_name' => $this->request->getVar('last_name'),
					'email' => $this->request->getVar('email'),
					'role' => $this->request->getVar('role'),//User Role
					'password' => $this->request->getVar('password'),
				];

				$this->user_model->save($newData);
				$data_arr = array("success" => TRUE,"message" => 'User Saved!');
				
			}
			else {
				$validationError = json_encode($validation->getErrors());
				$data_arr = array("success" => FALSE,"message" => 'Validation Error!'.$validationError);
			  }
		}
		else {
			$data_arr = array("success" => FALSE,"message" => 'No posted data!');
		  }
		  die(json_encode($data_arr));
	}

	public function edit_user($id) 
	{
		helper(['form']);

		if($this->request->getPost()) {
			$validation =  \Config\Services::validation();

			$rules = [
				'first_name' => [
					'rules' => 'required|min_length[3]|max_length[20]',
					'errors' => [
						'required' => 'Please input your First Name.',
						'min_length' => 'First Name should be longer than 3 characters.',
						'max_length' => 'First Name should be no longer than 20 characters.',
					],
				],	
				'last_name' => [
					'rules' => 'required|min_length[3]|max_length[20]',
					'errors' => [
						'required' => 'Please input your Last Name.',
						'min_length' => 'Last Name should be longer than 3 characters.',
						'max_length' => 'Last Name should be no longer than 20 characters.',
					],
				],
				'email' => [
					'rules' => 'min_length[6]|max_length[50]|valid_email|is_unique[users.email]',  // check if email is valid.  check if email is unique on users table
					'errors' => [
						'min_length' => 'Email should be longer than 6 characters.',
						'max_length' => 'Email should be no longer than 50 characters.',
						'valid_email' => 'Please input a valid Email.',
						'is_unique' => 'The Email you provided already has an account. Please try another one or Sign in with that email.',
					],
				],
				'role' => [
					'rules' => 'required|integer',
					'errors' => [
						'required' => 'Please input your Last Name.',
					],
				],
				'password' => [
					'rules' => 'required|min_length[8]|max_length[255]',
					'errors' => [
						'min_length' => 'Password should be longer than 8 characters.',
						'max_length' => 'Password should be no longer than 255 characters.',
					],
				],
				'password_confirm' => [
					'rules' => 'matches[password]',
					'errors' => [
						'matches' => 'Your Password does not match.'
					],
				],
			];

			if($this->validate($rules)) {
				$this->data['validation'] = $this->validator;

				$newData = [
					'guid' => $this->_generate_guid(),
					'first_name' => $this->request->getVar('first_name'),
					'last_name' => $this->request->getVar('last_name'),
					'email' => $this->request->getVar('email'),
					'role' => $this->request->getVar('role'),//User Role
					'password' => $this->request->getVar('password'),
				];

				$this->user_model->set($newData)->where('id', $id)->update(); // trying to update blog to database
				$data_arr = array("success" => TRUE,"message" => 'Edit Successful!');
				
			}
			else {
				$validationError = json_encode($validation->getErrors());
				$data_arr = array("success" => FALSE,"message" => 'Validation Error!'.$validationError);
			  }
		}
		else {
			$data_arr = array("success" => FALSE,"message" => 'No posted data!');
		  }
		  die(json_encode($data_arr));
	}
}
