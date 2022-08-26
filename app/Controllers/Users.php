<?php

namespace App\Controllers;

use App\Models\UserModel;

class Users extends BaseController
{

	public function __construct() {
		helper(['jwt']);

		$this->data = [];
		$this->guid = session()->get('guid');
		$this->user_model = model('UserModel');
		$this->forgotpassword_model = model('ForgotpasswordModel');

		$this->data['user_jwt'] = ($this->guid != '') ? getSignedJWTForUser($this->guid) : '';		
	}

	public function index()
	{
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

			if($user) {
				if(password_verify($this->request->getPost('password'), $user['password'])) {
					$this->setUserSession($user);
	
					return redirect()->to('admin/dashboard');
				}
				else {
					$session = session();
					$session->setFlashdata('message', 'Incorrect Email or Password.');
					return redirect()->to('/users');
				}
			}
			else {
				$session = session();
				$session->setFlashdata('message', 'Incorrect Email or Password.');
				return redirect()->to('/users');
			}
		}

		$this->data['page_body_id'] = "user_login";

		echo view('login', $this->data);
	}

	public function login() {
		// helper(['form']);
		// print_r($_POST);
		// die();
		print_r($this->request->getPost());
	}

	private function setUserSession($user) {
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

	public function register() {
		helper(['form']);

		// if($this->request->getMethod() == 'post') {
		if($this->request->getPost()) {
			// print_r($this->request->getPost());die();
			// Form validation here
			$rules = [
				// 'username' => 'required|min_length[3]|max_length[20]|is_unique[users.username]',
				'first_name' => 'required|min_length[3]|max_length[20]',
				'last_name' => 'required|min_length[3]|max_length[20]',
				'email' => 'min_length[6]|max_length[50]|valid_email|is_unique[users.email]',  // check if email is valid.  check if email is unique on users table
				'password' => 'required|min_length[8]|max_length[255]',
				'password_confirm' => 'matches[password]',
			];

			if(!$this->validate($rules)) {
				$this->data['validation'] = $this->validator;
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
				// $this->user_model->save($newData);

				// $user = $this->user_model->where('email', $this->request->getVar('email'))->first();

				// Preparation for email.
				
				// $this->setUserSession($user);
				
				// $session = session();
				// $session->setFlashdata('success', 'Successful Registration');
				// return redirect()->to('/dashboard');
			}

			$name = $this->request->getVar('first_name') .' '. $this->request->getVar('last_name');
			$email = $this->request->getVar('email');
			$message = "This the body of the message";


			$this->send($name, $email, 'cesaryamutajr@gmail.com', $message);
		}

		echo view('register', $this->data);
	}

	public function profile() {
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

	public function logout() {
		session()->destroy();
		return redirect()->to('/');
	}

	private function _generate_guid() {
		return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
	}
	public function checkout()
	{
        echo view('checkout');
	}

	function generateRandomString($length = 10) {
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
		  if($this->request->getMethod() == "post"){
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
					if($this->user_model->verifyEmail($email)){
					// if(!empty($userdata)){
						$unique_id = $this->generateRandomString($length = 10);
						$newData = ['unique_id' => $unique_id];
						if($this->forgotpassword_model->save($newData))
						{
							echo 'Password reset link sent! <br>';
							echo base_url('password-reset/').$unique_id;
						 //echo view('reset_password');
						}
						else{
							return redirect()->to(current_url());
						}
						// if($this->user_model->updatedAt($userdata['guid'])){
						//  	$to = $email;
						//  	$subject = "Reset Password Link";
						//  	$token = $userdata['guid'];
						//  	$message = 'Hi '.$userdata['first_name'].'<br><br>'
						// 			. 'Your reset password request has been recieved. Please click'
						// 			. 'the below link to reset your password.<br><br>'
						// 			. '<a href="'.base_url().'/Users/reset_password/'.$token.'">Click here to Reset password</a><br><br>'
						// 			. 'Thanks<br>Enjoymint';

						// 	$email = \Config\Services::email();
						//  	$email->setTo($to);
						//  	$email->setFrom('Enjoymint@gmail.com','Enjoymint');
						//  	$email->setSubject($subject);
						//  	$email->setMessage($message);
						//  	if($email->send()){

						//  		session()->setTempdata('success', 'Reset password link sent to your registered email. Please verify with in 15 minutes', 3);
						//  		return redirect()->to(current_url());
						//  	}
						//  	else 
						//  	{
						//  		$data = $email->printDebugger(['headers']);
						//  		print_r($data);
						//  	}
						// }
						//  else
						// {
						// 	session()->setTempdata('error','Unable to update', 3);
						// return redirect()->to(current_url());
						// }

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

	public function reset_password($unique_id){
		$session = session();	
		$checkUniqueId = $this->forgotpassword_model->where('unique_id', $unique_id)->first();
		if($checkUniqueId){
			$session->forgot_password_id = $checkUniqueId;
			//  $_SESSION['forgor-password-id'] = $checkUniqueId;
			//print_r($_SESSION['forgor-password-id']); 
			//$data['id']= 
			echo view('reset_password', $this->data);
		}else{
			echo 'Invalid';
		}
	}


	public function updatePassword()
	{
		$session = session();
		// $data = [];
		// $data['userdata'] = $this->user_model->getLoggedInUserData(session()->get('logged_user'));
		
		if($this->request->getMethod() == 'post'){
			// $rules = [
			// 		'old_password' => 'required',
			// 		'new_password' => 'required|min_length[8]|max_length[255]',
			// 		'confirm_password' => 'matches[password]',
			// 	];
		//    if($this->validate($rules)){
				// $old_password = $this->request->getVar('old_password');
				$new_password = [
					'password' => password_hash($this->request->getVar('new_password'), PASSWORD_DEFAULT)
				];
				$data = $session->get('forgot_password_id');
				$this->user_model->update($data, $new_password);
				return redirect()->to('users');
				
				//    $checkId = $this->user_model->where('id', $id)->first();
			//    $password =  [
			// 	'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT)
			//    ];
			// if($this->user_model->passwordVerify($old_password, $data['userdata']->password))
			// {
			// 	if($this->user_model->update($new_password, session()->get('logged_user')))
			// 	{
			// 		session()->setTempdata('success','Password Updated successfully', 3);
			// 		return redirect()->to('Users');
			// 	}
			// 	else
			// 	{
			// 		session()->setTempdata('error','Unabled to update', 3);
			// 		return redirect()->to(current_url());
			// 	}
			// }else{
			// 	session()->setTempdata('error','Old password does not matched with db password', 3);
			// 	return redirect()->to('Users');
			// }
				

			//    $this->user_model->update($checkId, $password);
			
			//    session()->setTempdata('success','Password updated', 3);
			// 	return redirect()->to('Users');
				
			}
			else
			{
			$data['validation'] = $this->validator;
			}
	}

	//Expecting $_POST['Name', 'Email', 'Phone', 'Message']
	public function send($rname, $rmail, $sender_email, $message) 
	{
		// print_r("test");die();

		// $this->load->library('email');

		// $config = array();
    // $config['protocol']     = "smtp"; // you can use 'mail' instead of 'sendmail or smtp'
    // $config['smtp_host']    = "smtppro.zoho.com";// you can use 'smtp.googlemail.com' or 'smtp.gmail.com' instead of 'ssl://smtp.googlemail.com'
    // $config['smtp_user']    = "cesar@fuegonetworx.com"; // client email gmail id
    // $config['smtp_pass']    = "Newyear2022!!"; // client password
    // $config['smtp_port']    =  587;
    // $config['smtp_crypto']  = 'TLS';
    // $config['smtp_timeout'] = "";
    // $config['mailtype']     = "html";
    // $config['charset']      = "iso-8859-1";
    // $config['newline']      = "\r\n";
    // $config['wordwrap']     = TRUE;
    // $config['validate']     = FALSE;
    // $this->load->library('email', $config); // intializing email library, whitch is defiend in system

		// $this->email->set_newline("\r\n"); // comuplsory line attechment because codeIgniter interacts with the SMTP server with regards to line break

    // $from_email = $sender_email; // sender email, coming from my view page 
    // $to_email = $rmail; // reciever email, coming from my view page
    // //Load email library

    // $this->email->from($from_email);
    // $this->email->to($to_email);
    // $this->email->subject('Send Email Codeigniter'); 
    // $this->email->message('The email send using codeigniter library');  // we can use html tag also beacause use $config['mailtype'] = 'HTML'
    // //Send mail
    // if($this->email->send()){
    //     $this->session->set_flashdata("email_sent","Congragulation Email Send Successfully.");
    //     echo "email_sent";
    // }
    // else{
    //     echo "email_not_sent";
    //     echo $this->email->print_debugger();  // If any error come, its run
    // }

		// helper('form');

		// //Send mail form data
		$email = \Config\Services::email();
		$email->setFrom($sender_email);
		$email->setTo($rmail);
		$email->setSubject('Confirm Registration');
		$email->setNewline = "\r\n";

		$body  = 'Name: ' . $rname . "\r\n";
		$body .= 'E-Mail: ' . $rmail . "\r\n";
		$body .= 'Message: ' . $message . "\r\n";
		$email->setMessage($body);

		if($email->send()) {
				print_r("email sent");
		} 

		// Output errors for debugging if necessary
		echo $email->printDebugger();
		exit;

		//Handle any errors here...
	} 
}
