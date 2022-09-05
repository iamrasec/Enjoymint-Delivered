<?php

namespace App\Controllers;

use App\Models\UserModel;

class Users extends BaseController
{

	public function __construct() 
	{
		helper(['jwt']);

		$this->data = [];
		$this->role = session()->get('role');
    $this->isLoggedIn = session()->get('isLoggedIn');
		$this->guid = session()->get('guid');
		$this->user_model = model('UserModel');
		$this->forgotpassword_model = model('ForgotpasswordModel');

		$this->data['user_jwt'] = ($this->guid != '') ? getSignedJWTForUser($this->guid) : '';		

		$this->sender_email = 'cesar@fuegonetworx.com';
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

				$success['success'] = 'Please check your email for account activation.';

				return view('register', $success);
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
		session()->destroy();
		delete_cookie('cart_data');
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

			$this->user_model->save($update_data);
		}
		else if($validate['is_active'] == 1){
			return view('confirm_user', ['status' => 'Activation Failed.  Account already in-use.']);	
		}

		$validate['is_active'] = 1;

		$this->setUserSession($validate);

		return view('confirm_user', $validate);

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
	{
		$page_title = "Dashboard";

		// print_r($categories);

		$this->data['page_body_id'] = "shop";
		$this->data['breadcrumbs'] = [
		'parent' => [],
		'current' => $page_title,
		];
		$this->data['page_title'] = $page_title;

		// Identify the tabs that goes with the Dashboard
		$dashboard_tabs = ['_orders_tab', '_personal_info_tab', '_address_tab'];

		// Added security to only allow tabs that are specified in the array
		if(in_array($tab, $dashboard_tabs)) {
			$this->data['active_tab'] = $tab;		// If tab specified is found, return tab
		}
		else {
			$this->data['active_tab'] = '_orders_tab';  // If tab specified is not found, default back to order tab
		}
		
		return view('customer_dashboard/index', $this->data);
	}
}
