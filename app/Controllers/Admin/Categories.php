<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;

class Categories extends BaseController {

    public function __construct() {
        helper(['jwt']);
		$this->data = [];
		$this->role = session()->get('role');
        $this->isLoggedIn = session()->get('isLoggedIn');
        $this->guid = session()->get('guid');
        $this->category_model = model('CategoryModel');

        $this->data['user_jwt'] = getSignedJWTForUser($this->guid);
    }

    public function index() {
        if($this->isLoggedIn == 1 && $this->role == 1) {
            $page_title = 'Manage Categories';
      
            $this->data['page_body_id'] = "category_list";
            $this->data['breadcrumbs'] = [
              'parent' => [],
              'current' => $page_title,
            ];
            $this->data['page_title'] = $page_title;
            // $this->data['categories'] = $this->category_model->where('parent', 0)->get()->getResult();
            $this->data['categories'] = $this->category_model->get()->getResult();
      
                echo view('Admin/manage_categories', $this->data);
        }
        else {
            return redirect()->to('/');
        }
    }

    public function add_category() {
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
            $this->data['submit_url'] = base_url('/admin/categories/add_category');


            // NOTE: For now can only add child category to top level categories (parent = 0)
            $this->data['categories'] = $this->category_model->where('parent', 0)->get()->getResult();

            // Check if there are posted form data.
            $this->data['post_data'] = $this->request->getPost();

            if($this->request->getPost()) {
                $to_save = [
					'name' => $this->request->getVar('cat_name'),
					'url' => $this->request->getVar('cat_url'),
					'parent' => $this->request->getVar('parent_cat'),
					'weight' => $this->request->getVar('cat_weight'),
				];

                $this->save_category($to_save); 
                return redirect()->to('/admin/categories');
            }
            else {
                echo view('Admin/add_category', $this->data);
            }
        }
        else {
            return redirect()->to('/');
        }
    }

    private function save_category($to_save) {
        $this->category_model->save($to_save);
        $session = session();
        $session->setFlashdata('success', 'Category Added Successfully');
        // return redirect()->to('/admin/categories');
    }
}
