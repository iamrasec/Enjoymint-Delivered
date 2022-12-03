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

    $this->data['user_jwt'] = ($this->guid != '') ? getSignedJWTForUser($this->guid) : '';
  }

  public function index()
  {
    $page_title = 'About Us';

    $this->data['page_body_id'] = "faq";
    $this->data['breadcrumbs'] = [
    'parent' => [],
    'current' => $page_title,
    ];
    $this->data['page_title'] = $page_title;

    return view('about_page', $this->data);
  }
}