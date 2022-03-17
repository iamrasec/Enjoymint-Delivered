<?php

namespace App\Controllers;

use App\Models\UserModel;

class Users extends BaseController
{

	public function index()
	{
		$data = [];
		helper(['form']);

		$this->data['post_data'] = $this->request->getPost();

		d($this->request->getPost());

		if($this->request->getPost()) {
			// Form login validation here
			$rules = [
				'username' => 'required|min_length[3]|max_length[50]',
				'password' => 'required|min_length[3]|max_length[255]|validateUser[username,password]',
			];

			$errors = [
				'password' => [
					'validateUser' => 'Username or Password don\'t match',
				]
			];

			// $model = new UserModel();

			// $user = $model->where('username', $this->request->getVar('username'))->first();

			$user = [
				'id' => 1,
				'email' => $this->data['post_data']['email'],
				'role' => 'admin',
			];

			$this->setuserSession($user);

			return redirect()->to('dashboard');
		}

		$this->data['page_body_id'] = "user_login";

		echo view('login', $this->data);
	}

	public function login() {
		// helper(['form']);
		print_r($_POST);
		die();
		print_r($this->request->getPost());
	}

	private function setuserSession($user) {
		$data = [
			'id' => $user['id'],
			// 'username' => $user['username'],
			// 'firstname' => $user['firstname'],
			// 'lastname' => $user['lastname'],
			'email' => $user['email'],
			'role' => $user['role'],
			'isLoggedIn' => true,
		];

		session()->set($data);
		return true;
	}

	public function register() {
		$data = [];
		helper(['form']);

		// if($this->request->getMethod() == 'post') {
		if($this->request->getPost()) {
			// Form validation here
			$rules = [
				'username' => 'required|min_length[3]|max_length[20]|is_unique[users.username]',
				'firstname' => 'required|min_length[3]|max_length[20]',
				'lastname' => 'required|min_length[3]|max_length[20]',
				'email' => 'min_length[6]|max_length[50]|valid_email|is_unique[users.email]',  // check if email is valid.  check if email is unique on users table
				'password' => 'required|min_length[8]|max_length[255]',
				'password_confirm' => 'matches[password]',
			];

			if(!$this->validate($rules)) {
				$data['validation'] = $this->validator;
			}
			else {
				$model = new UserModel();

				$newData = [
					'username' => $this->request->getVar('username'),
					'firstname' => $this->request->getVar('firstname'),
					'lastname' => $this->request->getVar('lastname'),
					'email' => $this->request->getVar('email'),
					'password' => $this->request->getVar('password'),
				];

				// print_r($newData);

				$model->save($newData);
				$session = session();
				$session->setFlashdata('success', 'Successful Registration');
				return redirect()->to('/');
			}
		}

		echo view('templates/header', $data);
		echo view('register');
		echo view('templates/footer');
	}

	public function profile() {
		$data = [];
		helper(['form']);
		$model = new UserModel();

		if($this->request->getPost()) {
			// Profile validation here
			$rules = [
				'username' => 'required|min_length[3]|max_length[20]|is_unique[users.username]',
				'firstname' => 'required|min_length[3]|max_length[20]',
				'lastname' => 'required|min_length[3]|max_length[20]',
			];

			if($this->request->getPost('password') != '') {
				$rules['password'] = 'required|min_length[8]|max_length[255]';
				$rules['password_confirm'] = 'matches[password]';
			}

			if(!$this->validate($rules)) {
				$data['validation'] = $this->validator;
			}
			else {
				$model = new UserModel();

				$newData = [
					'id' => session()->get('id'),
					'username' => $this->request->getPost('username'),
					'firstname' => $this->request->getPost('firstname'),
					'lastname' => $this->request->getPost('lastname'),
				];

				if($this->request->getPost('password') != '') {
					$newData['password'] = $this->request->getPost('password');
				}

				$model->save($newData);
				session()->setFlashdata('success', 'Successfully Updated');
				return redirect()->to('/profile');
			}
		}

		$data['user'] = $model->where('id', session()->get('id'))->first();
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
}
