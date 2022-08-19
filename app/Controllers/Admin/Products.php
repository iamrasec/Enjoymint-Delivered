<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;

class Products extends BaseController {

  public function __construct() {
    helper(['jwt']);

		$this->data = [];
		$this->role = session()->get('role');
    $this->isLoggedIn = session()->get('isLoggedIn');
    $this->guid = session()->get('guid');
    $this->product_model = model('productModel');
    $this->strain_model = model('strainModel');
    $this->brand_model = model('brandModel');
    $this->measurement_model = model('measurementModel');

    $this->data['user_jwt'] = getSignedJWTForUser($this->guid);
    $this->image_model = model('imageModel');
    $this->product_variant_model = model('productVariantModel');

    if($this->isLoggedIn !== 1 && $this->role !== 1) {
      return redirect()->to('/');
    }
  }
  
  public function index() 
  {
    // $data = [];
    $page_title = 'Products List';

    $this->data['page_body_id'] = "products_list";
    $this->data['breadcrumbs'] = [
      'parent' => [],
      'current' => $page_title,
    ];
    $this->data['page_title'] = $page_title;
    $this->data['products'] = $this->product_model->get()->getResult();
    return view('admin/products_list_view', $this->data);
  }

  public function add_product() 
  {
      $page_title = 'Add Product';
      $this->data['page_body_id'] = "products_list";
      $this->data['breadcrumbs'] = [
        'parent' => [
          ['parent_url' => base_url('/admin/products'), 'page_title' => 'Products'],
        ],
        'current' => $page_title,
      ];
      $this->data['page_title'] = $page_title;
      $this->data['brands'] = $this->brand_model->get()->getResult();
      $this->data['strains'] = $this->strain_model->get()->getResult();
      $this->data['measurements'] = $this->measurement_model->get()->getResult();
      echo view('admin/add_product', $this->data);
  }
  
  public function save_product() {
    $this->request->getPost();

    $rules = [
      'name' => 'required|min_length[3]',
      'sku' => 'required|min_length[3]',
      'purl' => 'required|min_length[3]',
      'qty' => 'required|decimal',
      'thc_val' => 'required',
      'cbd_val' => 'required',
    ];

    if($this->validate($rules)) {
      $data['validation'] = $this->validator;
    }
  }

  public function strains() {
    if($this->isLoggedIn == 1 && $this->role == 1) {
      $page_title = 'Manage Strains';

      $this->data['page_body_id'] = "product_strains";
      $this->data['breadcrumbs'] = [
        'parent' => [
          ['parent_url' => base_url('/admin/products'), 'page_title' => 'Products'],
        ],
        'current' => $page_title,
      ];
      $this->data['page_title'] = $page_title;
      $this->data['strains'] = $this->strain_model->get()->getResult();

		  echo view('admin/manage_strains', $this->data);
    }
    else {
      return redirect()->to('/');
    }
  }

  public function add_strain() {
    helper(['form']);

    if($this->isLoggedIn == 1 && $this->role == 1) {
      $page_title = 'Add Strain';

      $this->data['page_body_id'] = "add_strain";
      $this->data['breadcrumbs'] = [
        'parent' => [
          ['parent_url' => base_url('/admin/products'), 'page_title' => 'Products'],
          ['parent_url' => base_url('/admin/products/strains'), 'page_title' => 'Manage Strains'],
        ],
        'current' => $page_title,
      ];
      $this->data['page_title'] = $page_title;
      $this->data['submit_url'] = base_url('/admin/products/add_strain');

      // Check if there are posted form data.
      $this->data['post_data'] = $this->request->getPost();

      if($this->request->getPost()) {
          $to_save = [
            'name' => $this->request->getVar('name'),
            'url' => $this->request->getVar('url'),
          ];

          $this->save_strain($to_save); 
          return redirect()->to('/admin/products/strains');
      }

		  echo view('admin/add_strain', $this->data);
    }
    else {
      return redirect()->to('/');
    }
  }

  private function save_strain($to_save) {
    $this->strain_model->save($to_save);
    $session = session();
    $session->setFlashdata('success', 'Strain Added Successfully');
    return true;
  }

  public function brands() {
    if($this->isLoggedIn == 1 && $this->role == 1) {
      $page_title = 'Manage Brands';

      $this->data['page_body_id'] = "product_brands";
      $this->data['breadcrumbs'] = [
        'parent' => [
          ['parent_url' => base_url('/admin/products'), 'page_title' => 'Products'],
        ],
        'current' => $page_title,
      ];
      $this->data['page_title'] = $page_title;
      $this->data['brands'] = $this->brand_model->get()->getResult();

		  echo view('admin/manage_brands', $this->data);
    }
    else {
      return redirect()->to('/');
    }
  }

  public function add_brand() {
    helper(['form']);

    if($this->isLoggedIn == 1 && $this->role == 1) {
      $page_title = 'Add Brand';

      $this->data['page_body_id'] = "add_brand";
      $this->data['breadcrumbs'] = [
        'parent' => [
          ['parent_url' => base_url('/admin/products'), 'page_title' => 'Products'],
          ['parent_url' => base_url('/admin/products/brands'), 'page_title' => 'Manage Strains'],
        ],
        'current' => $page_title,
      ];
      $this->data['page_title'] = $page_title;
      $this->data['submit_url'] = base_url('/admin/products/add_brand');

      // Check if there are posted form data.
      $this->data['post_data'] = $this->request->getPost();

      if($this->request->getPost()) {
          $to_save = [
            'name' => $this->request->getVar('name'),
            'url' => $this->request->getVar('url'),
            'parent' => $this->request->getVar('parent'),
            'weight' => $this->request->getVar('weight'),
          ];

          $this->save_strain($to_save); 
          return redirect()->to('/admin/products/strains');
      }

		  echo view('admin/add_strain', $this->data);
    }
    else {
      return redirect()->to('/');
    }
  }

  public function measurements() {
    if($this->isLoggedIn == 1 && $this->role == 1) {
      $page_title = 'Product Measurements';

      $this->data['page_body_id'] = "product_measurement";
      $this->data['breadcrumbs'] = [
        'parent' => [
          ['parent_url' => base_url('/admin/products'), 'page_title' => 'Products'],
        ],
        'current' => $page_title,
      ];
      $this->data['page_title'] = $page_title;
      $this->data['measurements'] = $this->measurement_model->get()->getResult();

		  echo view('admin/product_measurements', $this->data);
    }
    else {
      return redirect()->to('/');
    }
  }

  public function reviews() {
    if($this->isLoggedIn == 1 && $this->role == 1) {
      $ratings_model = model('ratingModel');
      $page_title = 'Manage Reviews';

      $this->data['page_body_id'] = "manage_review";
      $this->data['breadcrumbs'] = [
        'parent' => [
          ['parent_url' => base_url('/admin/products'), 'page_title' => 'Products'],
        ],
        'current' => $page_title,
      ];
      $this->data['page_title'] = $page_title;
      $this->data['ratings'] = $ratings_model->get()->getResult();

		  echo view('admin/manage_reviews', $this->data);
    }
    else {
      return redirect()->to('/');
    }
  }

  /**
   * This function will fetch product list from post request of datatable server side processing
   * 
   * @return json product list json format
  */
  public function getProductLists()
  {
    $data  = array();
    $start = $_POST['start'];
    $length = $_POST['length'];

    $products = $this->product_model->select('id,name,url')
      ->like('name',$_POST['search']['value'])
      ->orLike('url',$_POST['search']['value'])
      ->limit($length, $start)
      ->get()
      ->getResult();
   
    foreach($products as $product){
      $start++;
      $data[] = array(
        $product->id, 
        $product->name, 
        $product->url,
        "<a href=".base_url('admin/products/edit_product/').$product->id.">edit</a>",
      );
    }

    $output = array(
      "draw" => $_POST['draw'],
      "recordsTotal" => $this->product_model->countAll(),
      "recordsFiltered" => $this->product_model->countAll(),
      "data" => $data,
    );
    echo json_encode($output);
  }
}