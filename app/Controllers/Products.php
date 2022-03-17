<?php

namespace App\Controllers;

class Products extends BaseController
{
    var $view_data = array();

    public function index()
    {
        echo view('product_view');
    }

    public function add_product()
    {
        $data = [];
        helper(['form']);

        $role = session()->get('role');
        $isLoggedIn = session()->get('isLoggedIn');

        if($isLoggedIn == 1) {
            if($role == 'admin') {
                $this->data['page_body_id'] = "Add Category";

                echo view('products/add_product', $this->data);
            }
            else {
                return redirect()->to('/');
            }
        }
        else {
            return redirect()->to('/');
        }
    }

    public function add_category()
    {
        $data = [];
        helper(['form']);

        $role = session()->get('role');
        $isLoggedIn = session()->get('isLoggedIn');

        if($isLoggedIn == 1) {
            if($role == 'admin') {
                $this->data['page_body_id'] = "Add Category";

                echo view('products/add_category', $this->data);
            }
            else {
                return redirect()->to('/');
            }
        }
        else {
            return redirect()->to('/');
        }
    }
}
