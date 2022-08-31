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
    $this->product_model = model('productModel');
    $this->blog_model = model('blogModel');
    
    $this->data['user_jwt'] = getSignedJWTForUser($this->guid);
    $this->image_model = model('imageModel');

    if($this->isLoggedIn !== 1 && $this->role !== 1) {
      return redirect()->to('/');
    }
    
  }
  
  public function index() 
  {
    // $data = [];
    $page_title = 'List Pages';

    $this->data['page_body_id'] = "blog_list";
    $this->data['breadcrumbs'] = [
      'parent' => [],
      'current' => $page_title,
    ];
    $this->data['page_title'] = $page_title;
    $this->data['blogs'] = $this->blog_model->get()->getResult();
    return view('blogs_article/list_page', $this->data);
  }
  
    public function blog() 
    {

      $data['blogs'] = $this->blog_model->select('blogs.*, images.url')
      ->join('images', 'blogs.images = images.id')
      ->get()->getResult();
      echo view('blog', $data);

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
      echo view('blogs_article/add_blog', $this->data);
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

    $blogs = $this->blog_model->select('id,title,description,author')
      ->like('title',$_POST['search']['value'])
      ->orLike('author',$_POST['search']['value'])
      ->limit($length, $start)
      ->get()
      ->getResult();
   
    foreach($blogs as $blog){
      $start++;
      $data[] = array(
        $blog->id, 
        $blog->title, 
        $blog->description,
        $blog->author,
        "<a href=".base_url('admin/products/edit_product/').$blog->id.">edit</a>",
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