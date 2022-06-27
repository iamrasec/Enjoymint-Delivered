<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;

class Products extends BaseController {

  public function __construct() {
		$this->data = [];
		$this->role = session()->get('role');
    $this->isLoggedIn = session()->get('isLoggedIn');
    $this->product_model = model('productModel');
    $this->strain_model = model('strainModel');
    $this->brand_model = model('brandModel');
    $this->measurement_model = model('measurementModel');

    if($this->isLoggedIn !== 1 && $this->role !== 1) {
      return redirect()->to('/');
    }
  }
  
  public function index() {
    // $data = [];

    if($this->isLoggedIn == 1 && $this->role == 1) {
      $page_title = 'Products List';

      $this->data['page_body_id'] = "products_list";
      $this->data['breadcrumbs'] = [
        'parent' => [],
        'current' => $page_title,
      ];
      $this->data['page_title'] = $page_title;
      $this->data['products'] = $this->product_model->get()->getResult();

		  echo view('admin/products_list_view', $this->data);
    }
    else {
      return redirect()->to('/');
    }        
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

   /**
     * This function will save a product into the server
     * 
     * @return object a success indicator and the message
    */
  public function addProduct()
  {
    helper(['form', 'functions']); // load helpers
    addJSONResponseHeader(); // set response header to json

    if($this->request->getPost()) {
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

        //process saving of images
        //$images = $this->request->getVar('productImages');
        $images = array();
        if ($this->request->getFileMultiple('productImages')) {
          echo 'naay file';
          foreach($this->request->getFile('productImages') as $img){
            if (!$img->hasMoved()) {
              $newName = $img->getRandomName();
              $img->move(WRITEPATH . 'uploads', $newName);
              array_push($images,$newName);
              echo 'new image'. PHP_EOL;
            }
          }
        }

        // data mapping for PRODUCTS table save
        $to_save = [
          'name' => $this->request->getVar('name'),
          'url' => $this->request->getVar('purl'),
          'description' => $this->request->getVar('description'),
          'strain' => $this->request->getVar('strain'),
          'stocks' => $this->request->getVar('qty'),
          'brands' => $this->request->getVar('brand'),
          'sku' => $this->request->getVar('sku'),
          'images' => implode(',', $images),
        ];
        $this->product_model->save($to_save); // trying to save product to database
        $data_arr = array("success" => TRUE,"message" => 'Product Saved!');
      } else {
        $data_arr = array("success" => FALSE,"message" => 'Validation Error!');
      }
    } else {
      $data_arr = array("success" => FALSE,"message" => 'No posted data!');
    }
    die(json_encode($data_arr));
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
}