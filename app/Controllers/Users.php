<?php

namespace App\Controllers;

use App\Models\UserModel;

class Users extends BaseController
{

	public function __construct() {
		$data = [];
		$this->user_model = model('UserModel');
		
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

			/* $user = [
				'id' => 1,
				'email' => $this->data['post_data']['email'],
				'role' => 'admin',
			]; */

			$this->setUserSession($user);

			return redirect()->to('admin/dashboard');
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
					'role' => 2,
				];

				// print_r($newData);

				// $model->save($newData);
				$this->user_model->save($newData);

				$user = $this->user_model->where('email', $this->request->getVar('email'))->first();
				$this->setUserSession($user);
				
				$session = session();
				$session->setFlashdata('success', 'Successful Registration');
				return redirect()->to('/dashboard');
			}
		}

		echo view('register');
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
}
 