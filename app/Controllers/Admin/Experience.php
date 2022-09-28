<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;

class Experience extends BaseController {

    public function __construct() {

        helper(['jwt']);
		$this->data = [];
		$this->role = session()->get('role');
        $this->isLoggedIn = session()->get('isLoggedIn');
        $this->guid = session()->get('guid');
        $this->experience_model = model('ExperienceModel');

        $this->data['user_jwt'] = getSignedJWTForUser($this->guid);
    }

    public function index() {

            $page_title = 'Experiences';
      
            $this->data['page_body_id'] = "experience_list";
            $this->data['breadcrumbs'] = [
              'parent' => [],
              'current' => $page_title,
            ];
            $this->data['page_title'] = $page_title;
            // $this->data['categories'] = $this->category_model->where('parent', 0)->get()->getResult();
            $this->data['experience'] = $this->experience_model->get()->getResult();
      
                echo view('Admin/manage_experience', $this->data);

    }   

    public function add_experience() {
        helper(['form']);

        // if($this->isLoggedIn == 1 && $this->role == 1) {
            $page_title = 'Add Experience';

            $this->data['page_body_id'] = "add_experience";
            $this->data['breadcrumbs'] = [
            'parent' => [],
            'current' => $page_title,
            ];
            $this->data['page_title'] = $page_title;
            $this->data['submit_url'] = base_url('/admin/experience/add_experience');


            // NOTE: For now can only add child category to top level categories (parent = 0)

            // Check if there are posted form data.
            $this->data['post_data'] = $this->request->getPost();

            if($this->request->getPost()) {
                $to_save = [
					'name' => $this->request->getVar('exp_name'),
					'url' => $this->request->getVar('exp_url'),
				];

                $this->save_experience($to_save); 
                return redirect()->to('/admin/experience');
            }
            else {
                echo view('Admin/add_experience', $this->data);
            }
        }

    private function save_experience($to_save) {
        $this->experience_model->save($to_save);
        $session = session();
        $session->setFlashdata('success', 'Experience Added Successfully');
        // return redirect()->to('/admin/categories');
    }
}
