<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;

class Compounds extends BaseController {

    public function __construct() {
        helper(['jwt']);
		$this->data = [];
		$this->role = session()->get('role');
        $this->isLoggedIn = session()->get('isLoggedIn');
        $this->guid = session()->get('guid');
        $this->compound_model = model('CompoundModel');

        $this->data['user_jwt'] = getSignedJWTForUser($this->guid);
    }

    public function index() {
        if($this->isLoggedIn == 1 && $this->role == 1) {
            $page_title = 'Manage Compounds';
      
            $this->data['page_body_id'] = "compounds_list";
            $this->data['breadcrumbs'] = [
              'parent' => [],
              'current' => $page_title,
            ];
            $this->data['page_title'] = $page_title;
            $this->data['compounds'] = $this->compound_model->get()->getResult();
      
                echo view('admin/manage_compounds', $this->data);
        }
        else {
            return redirect()->to('/');
        }
    }

    public function add_compound() {
        helper(['form']);

        if($this->isLoggedIn == 1 && $this->role == 1) {
            $page_title = 'Add Category';

            $this->data['page_body_id'] = "add_category";
            $this->data['breadcrumbs'] = [
            'parent' => [
                ['parent_url' => base_url('/admin/categories'), 'page_title' => 'Categories'],
            ],
            'current' => $page_title,
            ];
            $this->data['page_title'] = $page_title;
            $this->data['submit_url'] = base_url('/admin/compounds/add_compound');

            // Check if there are posted form data.
            $this->data['post_data'] = $this->request->getPost();

            if($this->request->getPost()) {
                $to_save = [
					'name' => $this->request->getVar('name'),
					'url' => $this->request->getVar('url'),
					'parent' => $this->request->getVar('parent'),
					'weight' => $this->request->getVar('weight'),
				];

                $this->save_category($to_save); 
            }

            echo view('admin/add_category', $this->data);
        }
        else {
            return redirect()->to('/');
        }
    }

    private function save_category($to_save) {
        $this->category_model->save($to_save);
        $session = session();
        $session->setFlashdata('success', 'Category Added Successfully');
        return redirect()->to('/admin/categories');
    }
}
