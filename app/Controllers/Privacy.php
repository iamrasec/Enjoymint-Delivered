<?php

namespace App\Controllers;

class Privacy extends BaseController
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
        $session = session();
        $user_id = $this->guid;
        $page_title = 'Privacy Policy';
        
        $this->data['page_body_id'] = "privacy_policy";
        $this->data['breadcrumbs'] = [
        'parent' => [],
        'current' => $page_title,
        ];
        $this->data['page_title'] = $page_title;
        if($user_id == null){
            $session->setFlashdata('message', 'Please login first');
          }
        $this->data['uid'] = $user_id;
        $this->data['location_keyword'] = $this->location_model->where('user_id', $user_id )->select('address')->first();
        $this->data['location_delivery'] = $this->location_model->where('user_id', $user_id )->select('delivery_schedule')->first();

        return view('privacy_policy_view', $this->data);
    }
}
