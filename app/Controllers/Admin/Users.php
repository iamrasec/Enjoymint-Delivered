<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;

class Users extends BaseController {

  public function __construct() {
    helper(['jwt']);

    $this->data = [];
    $this->role = session()->get('role');
    $this->isLoggedIn = session()->get('isLoggedIn');
    $this->guid = session()->get('guid');
    $this->user_model = model('UserModel');

    $this->data['user_jwt'] = getSignedJWTForUser($this->guid);

    if($this->isLoggedIn !== 1 && $this->role !== 1) {
      return redirect()->to('/');
    }
    
  }
  
  public function index() 
  {
    // $data = [];
    $page_title = 'List User';

    $this->data['page_body_id'] = "user_list";
    $this->data['breadcrumbs'] = [
      'parent' => [],
      'current' => $page_title,
    ];
    $this->data['page_title'] = $page_title;
    $this->data['users'] = $this->user_model->get()->getResult();
    return view('User/list_user', $this->data);
  }

  public function add_user() 
  {
      $page_title = 'Add User';
      $this->data['page_body_id'] = "User_list";
      $this->data['breadcrumbs'] = [
        'parent' => [
          ['parent_url' => base_url('/admin/users'), 'page_title' => 'Users'],
        ],
        'current' => $page_title,
      ];
      $this->data['page_title'] = $page_title;
      echo view('User/add_user', $this->data);
  }

  public function edit_user($id)
  {
    $page_title = 'Edit Blog';
    $this->data['page_body_id'] = "edit_user";
    $this->data['breadcrumbs'] = [
      'parent' => [
        ['parent_url' => base_url('/admin/users'), 'page_title' => 'Users'],
      ],
      'current' => $page_title,
    ];
    $this->data['page_title'] = $page_title;
    $user = $this->user_model->where('id', $id)->get()->getResult();

    $this->data['user_data'] = $user;

    return view('User/edit_user', $this->data);
  }

  /**
   * This function will fetch product list from post request of datatable server side processing
   * 
   * @return json product list json format
  */
  public function getUserLists()
  {
    $data  = array();
    $start = $_POST['start'];
    $length = $_POST['length'];

    $users = $this->user_model->select('*')
      ->like('first_name',$_POST['search']['value'])
      ->orLike('last_name',$_POST['search']['value'])
      ->limit($length, $start)
      ->get()
      ->getResult();
   
    foreach($users as $user){
      $start++;
      if($user->role == '1'){
        $roles = 'Admin';
      }
      elseif($user->role == '3'){
        $roles = 'Costumers';
      }
      else{
        return '';
      }
      $name = $user->first_name.' '.$user->last_name;
      $data[] = array(
        $user->id, 
        $name, 
        $roles,
        "<a href=".base_url().">Delete</a> | <a href=".base_url('admin/users/edit_user/'. $user->id).">Edit</a>",
      );
    }

    $output = array(
      "draw" => $_POST['draw'],
      "recordsTotal" => $this->user_model->countAll(),
      "recordsFiltered" => $this->user_model->countAll(),
      "data" => $data,
    );
    
    echo json_encode($output);

  }

}