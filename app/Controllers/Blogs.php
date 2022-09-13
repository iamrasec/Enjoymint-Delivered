<?php

namespace App\Controllers;

use App\Models\out;

class Blogs extends BaseController
{
  public function __construct() {
    helper(['jwt']);

    $this->data = [];
    $this->role = session()->get('role');
    $this->isLoggedIn = session()->get('isLoggedIn');
    $this->guid = session()->get('guid');
    $this->image_model = model('ImageModel');
    $this->blog_model = model('BlogModel');

    $this->data['user_jwt'] = ($this->guid != '') ? getSignedJWTForUser($this->guid) : '';
  }

  public function index($url = '')
  {
    if($url != '') {
      $blog = $this->blog_model->where('url', $url)->get()->getResult();

      if(!empty($blog)) {
          $blog = $blog[0];
      }

      $this->data['blogs'] = $blog;

      return view('Blogs/view_blog', $this->data);
    }
    else {
      $blog = $this->blog_model->get()->getResult();
      // return $this->view_all_products();

      $this->data['blogs'] = $blog;

      return view('Blogs/index', $this->data);
    }
  }
}