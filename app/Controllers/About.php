<?php

namespace App\Controllers;

class About extends BaseController
{
  public function __construct() 
  {
    helper(['jwt']);
    
    $this->data = [];
    $this->role = session()->get('role');
    $this->isLoggedIn = session()->get('isLoggedIn');
    $this->guid = session()->get('guid');
    $this->uid = session()->get('id');
    $this->location_model = model('LocationModel');

    $this->data['user_jwt'] = ($this->guid != '') ? getSignedJWTForUser($this->guid) : '';
  }

  public function index()
  {
    $session = session();
    
    $page_title = 'About Us';

    $this->data['page_body_id'] = "faq";
    $this->data['breadcrumbs'] = [
    'parent' => [],
    'current' => $page_title,
    ];
    $this->data['page_title'] = $page_title;
    $user_id = $this->uid;
    if($user_id == null){
      $session->setFlashdata('message', 'Please login first');
    }
    $this->data['uid'] = $user_id;
    $this->data['location_keyword'] = $this->location_model->where('user_id', $user_id )->select('address')->first();
    

    return view('about_page', $this->data);
  }
}