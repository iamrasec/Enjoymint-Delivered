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

  public function view_all_blogs() {

    $blog = $this->blog_model->get()->getResult(); 

    $this->data['blogs'] = $blog;
    // print_r($this->data['blogs']);

    return view('Blogs/index', $this->data);
}

  public function get_blogs($id)
  {
    $blog = $this->blog_model->getBlogbyID($id); 
    $this->data['blogs'] = $blog;

  return view('Blogs/view_blog', $this->data);

  }

  public function images($filename) {
    $filepath = WRITEPATH . 'uploads/' . $filename;

    $mime = mime_content_type($filepath);
    header('Content-Length: ' . filesize($filepath));
    header("Content-Type: $mime");
    header('Content-Disposition: inline; filename="' . $filepath . '";');
    readfile($filepath);
    exit();
}
}