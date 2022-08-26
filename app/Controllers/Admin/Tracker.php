<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;

class Tracker extends BaseController {
  
  public function __construct() {
    helper(['jwt']);

		$this->data = [];
		$this->role = session()->get('role');
    $this->isLoggedIn = session()->get('isLoggedIn');
    $this->guid = session()->get('guid');

    $this->data['user_jwt'] = getSignedJWTForUser($this->guid);

    if($this->isLoggedIn !== 1 && $this->role !== 1) {
      return redirect()->to('/');
    }
  }

  public function index() {
    $page_title = 'Order List';

    $this->data['page_body_id'] = "ticket_list";
    $this->data['breadcrumbs'] = [
      'parent' => [],
      'current' => $page_title,
    ];
    $this->data['page_title'] = $page_title;
    return view('admin/tracker/index', $this->data);
  }

  public function create() {
    $page_title = 'Create Order';

    $this->data['page_body_id'] = "create_ticket";
    $this->data['breadcrumbs'] = [
      'parent' => [],
      'current' => $page_title,
    ];
    $this->data['page_title'] = $page_title;
    return view('admin/tracker/create_ticket', $this->data);
  }


}