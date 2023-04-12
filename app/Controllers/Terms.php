<?php

namespace App\Controllers;

class Terms extends BaseController
{
    var $view_data = array();

    public function __construct() {
        helper(['jwt']);
    
        $this->data = [];
        $this->role = session()->get('role');
        $this->isLoggedIn = session()->get('isLoggedIn');
        $this->guid = session()->get('guid');
        $this->uid = session()->get('id');
        $this->location_model = model('LocationModel');
    
        $this->data['user_jwt'] = ($this->guid != '') ? getSignedJWTForUser($this->guid) : '';
    
        if($this->isLoggedIn !== 1 && $this->role !== 1) {
          return redirect()->to('/');
        }
    }

    public function index()
    {
        $page_title = 'Terms of Agreement';

        $this->data['page_body_id'] = "terms";
        $this->data['breadcrumbs'] = [
        'parent' => [],
        'current' => $page_title,
        ];
        $this->data['page_title'] = $page_title;
        $user_id = $this->uid;
        $this->data['uid'] = $user_id;
        $this->data['location_keyword'] = $this->location_model->where('user_id', $user_id )->select('address')->first();
        $this->data['location_delivery'] = $this->location_model->where('user_id', $user_id )->select('delivery_schedule')->first();

        return view('terms_view', $this->data);
    }
}
