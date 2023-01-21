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

  public function index($url = "")
  {
    if($url == "") {
      return $this->view_all_blogs();
    }
    else {
      return $this->view_blog($url);
    }
  }

  public function view_blog($url)
  {
    $session = session();

    $blog = $this->blog_model->getBlogByUrl($url); 

    // echo "<pre>".print_r($blog, 1)."</pre>"; die();

    $imageIds = [];

    for($i = 0; $i < count($blog); $i++) {
      if($blog[$i]->images != null) {
        $imageIds = explode(',',$blog[$i]->images);
        $blog[$i]->images = $this->image_model->whereIn('id', $imageIds)->get()->getResult();
      }
    }

    $this->data['blog'] = $blog;
    $location = $session->get('search1');
    $this->data['location_keyword'] = $location; 

    return view('Blogs/view_blog', $this->data);
  }

  public function view_all_blogs() 
  {
    $session = session();

    $blog = $this->blog_model->orderBy('created', 'DESC')->get()->getResult(); 

    $imageIds = [];

    for($i = 0; $i < count($blog); $i++) {
      if($blog[$i]->images != null) {
        $imageIds = explode(',',$blog[$i]->images);
        $blog[$i]->images = $this->image_model->whereIn('id', $imageIds)->get()->getResult();
      }
    }

    $this->data['blogs'] = $blog;

    
    // echo "<pre>".print_r($blog, 1)."</pre>"; die();

    // print_r($this->data['blogs']);
    $location = $session->get('search1');
    $this->data['location_keyword'] = $location; 

    return view('Blogs/index', $this->data);
}

  // public function get_blogs($id)
  // {
  //   $blog = $this->blog_model->getBlogbyID($id); 
  //   $this->data['blogs'] = $blog;

  //   return view('Blogs/view_blog', $this->data);
  // }

  public function images($filename) {
    $filepath = WRITEPATH . 'uploads/blogs/' . $filename;

    $mime = mime_content_type($filepath);
    header('Content-Length: ' . filesize($filepath));
    header("Content-Type: $mime");
    header('Content-Disposition: inline; filename="' . $filepath . '";');
    readfile($filepath);
    exit();
}
}