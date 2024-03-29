<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;

class Blogs extends BaseController {

  public function __construct() {
    helper(['jwt']);

    $this->data = [];
    $this->role = session()->get('role');
    $this->isLoggedIn = session()->get('isLoggedIn');
    $this->guid = session()->get('guid');
    $this->product_model = model('ProductModel');
    $this->blog_model = model('BlogModel');
    
    $this->data['user_jwt'] = getSignedJWTForUser($this->guid);
    $this->image_model = model('ImageModel');

    if($this->isLoggedIn !== 1 && $this->role !== 1) {
      return redirect()->to('/');
    }
    
  }
  
  public function index() 
  {
    // $data = [];
    $page_title = 'List Articles';

    $this->data['page_body_id'] = "blog_list";
    $this->data['breadcrumbs'] = [
      'parent' => [],
      'current' => $page_title,
    ];
    $this->data['page_title'] = $page_title;
    $this->data['blogs'] = $this->blog_model->get()->getResult();
    return view('Blogs/list_page', $this->data);
  }
  
  public function add_blog() 
  {
      $page_title = 'Add Blog';
      $this->data['page_body_id'] = "blog_list";
      $this->data['breadcrumbs'] = [
        'parent' => [
          ['parent_url' => base_url('/admin/blogs'), 'page_title' => 'Blogs'],
        ],
        'current' => $page_title,
      ];
      $this->data['page_title'] = $page_title;
      echo view('Blogs/add_blog', $this->data);
  }

  public function edit_blog($id)
  {
    $page_title = 'Edit Blog';
    $this->data['page_body_id'] = "edit_blog";
    $this->data['breadcrumbs'] = [
      'parent' => [
        ['parent_url' => base_url('/admin/blogs'), 'page_title' => 'Blogs'],
      ],
      'current' => $page_title,
    ];
    $this->data['page_title'] = $page_title;
    $blog = $this->blog_model->where('id', $id)->get()->getResult();

    $imageIds = [];

    for($i = 0; $i < count($blog); $i++) {
      if($blog[$i]->images != null || $blog[$i]->images != '') {
        $imageIds = explode(',',$blog[$i]->images);
        $blog[$i]->images = $this->image_model->whereIn('id', $imageIds)->get()->getResult();
      }
    }

    $this->data['blog_data'] = $blog;

    // $this->data['blog_data'] = $this->blog_model->getBlogbyID($id);

    return view('Blogs/edit_blog', $this->data);
  }
  
  public function save_product() {
    $this->request->getPost();

    $rules = [
      'title' => 'required|min_length[3]',
      'description' => 'required|min_length[3]',
      'author' => 'required|min_length[3]',
    ];

    if($this->validate($rules)) {
      $data['validation'] = $this->validator;
    }
  }

  /**
   * This function will fetch product list from post request of datatable server side processing
   * 
   * @return json product list json format
  */
  public function getBlogLists()
  {
    $data  = array();
    $start = $_POST['start'];
    $length = $_POST['length'];

    $blogs = $this->blog_model->select('*')
      ->like('title',$_POST['search']['value'])
      ->orLike('author',$_POST['search']['value'])
      ->limit($length, $start)
      ->get()
      ->getResult();
   
    foreach($blogs as $blog){
      $start++;
      if (strlen($blog->description) >= 200) {
          $description = substr($blog->description, 0, 200). " ... ";
      }
      else {
        $description = $blog->description;
      }
      $data[] = array(
        $blog->id, 
        $blog->title, 
        $description,
        $blog->author,
        '<a href='.base_url('blogs/'.$blog->url).' target="_blank">view</a> | <a href='.base_url('admin/blogs/edit_blog/'. $blog->id).'>edit</a>',
      );
    }

    $output = array(
      "draw" => $_POST['draw'],
      "recordsTotal" => $this->blog_model->countAll(),
      "recordsFiltered" => $this->blog_model->countAll(),
      "data" => $data,
    );
    
    echo json_encode($output);

  }

}