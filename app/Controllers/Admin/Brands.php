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
        $this->brand_model = model('BrandModel');

        $this->data['user_jwt'] = getSignedJWTForUser($this->guid);
    }

    public function index() {
        if($this->isLoggedIn == 1 && $this->role == 1) {
            $page_title = 'Manage Brands';
      
            $this->data['page_body_id'] = "brands_list";
            $this->data['breadcrumbs'] = [
              'parent' => [],
              'current' => $page_title,
            ];
            $this->data['page_title'] = $page_title;
            // $this->data['categories'] = $this->category_model->where('parent', 0)->get()->getResult();
            $this->data['brands'] = $this->brand_model->get()->getResult();
            
      
                echo view('Admin/manage_categories', $this->data);
        }
        else {
            return redirect()->to('/');
        }
    }


    
}
