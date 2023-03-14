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
    $this->product_model = model('ProductModel');
    $this->strain_model = model('StrainModel');
    $this->brand_model = model('BrandModel');
    $this->category_model = model('CategoryModel');
    $this->measurement_model = model('MeasurementModel');
    $this->product_category = model('ProductCategory');
    $this->product_experience = model('ProductExperience');
    $this->order_model = model('CheckoutModel');
    $this->experience_model = model('ExperienceModel');
    $this->discount_model = model('DiscountModel');
    $this->promo_model = model('PromoModel');
    $this->promo_products_model = model('PromoProductsModel');

    $this->data['user_jwt'] = getSignedJWTForUser($this->guid);
    $this->image_model = model('ImageModel');
    $this->product_variant_model = model('ProductVariantModel');

    if($this->isLoggedIn !== 1 && $this->role !== 1) {
      return redirect()->to('/');
    }
    
  }
  
  public function index() 
  {
    // $data = [];
    $page_title = 'Products List';

    $state = (isset($_GET['state'])) ? $_GET['state'] : 'all';

    $this->data['page_body_id'] = "products_list";
    $this->data['breadcrumbs'] = [
      'parent' => [],
      'current' => $page_title,
    ];
    $this->data['page_title'] = $page_title;
    $this->data['products'] = $this->product_model->get()->getResult();
    $this->data['state'] = $state;
    return view('Admin/products_list_view', $this->data);
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
      $this->data['categories'] = $this->category_model->get()->getResult();
      $this->data['experience'] = $this->experience_model->get()->getResult();
      $this->data['measurements'] = $this->measurement_model->get()->getResult();
      echo view('Admin/add_product', $this->data);
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

		  echo view('Admin/manage_strains', $this->data);
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

		  echo view('Admin/add_strain', $this->data);
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

		  echo view('Admin/manage_brands', $this->data);
    }
    else {
      return redirect()->to('/');
    }
  }

  public function edit_brand($id) {
    if($this->isLoggedIn == 1 && $this->role == 1) {
      // $get_brand_data = $this->brand_model->where('id', $id)->first();
      $page_title = 'Edit Brands';

      $this->data['page_body_id'] = "product_brands";
      $this->data['breadcrumbs'] = [
        'parent' => [
          ['parent_url' => base_url('/admin/products'), 'page_title' => 'Products'],
        ],
        'current' => $page_title,
      ];
      $this->data['page_title'] = $page_title;
      $this->data['brand_data'] = $this->brand_model->where('id', $id)->first();
      $this->data['submit_url'] = base_url('/admin/products/save_edit_brand');

		  echo view('Admin/edit_brand', $this->data);
    }
    else {
      return redirect()->to('/');
    }
  }

  public function save_edit_brand() {
    if($this->isLoggedIn == 1 && $this->role == 1) {
      $post = $this->request->getPost();
      
      // print_r($post);die();

      $update = [
        'name' => $post['name'],
        'url' => $post['url'],
      ];

      $this->brand_model->where('id', $post['id'])->set($update)->update();

      $session = session();
      $session->setFlashdata('success', 'Brand '. $post['name'] .' Modifications Saved!');
      return redirect()->to('/admin/products/brands');
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
            // 'parent' => $this->request->getVar('parent'),
            // 'weight' => $this->request->getVar('weight'),
          ];

          $this->save_brand($to_save); 
          return redirect()->to('/admin/products/brands');
      }

		  echo view('Admin/add_brand', $this->data);
    }
    else {
      return redirect()->to('/');
    }
  }

  private function save_brand($to_save) {
    $this->brand_model->save($to_save);
    $session = session();
    $session->setFlashdata('success', 'Brand Added Successfully');
    return true;
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

		  echo view('Admin/product_measurements', $this->data);
    }
    else {
      return redirect()->to('/');
    }
  }

  public function edit_product($pid) {
    helper('url');
    $session = session();
    $page_title = 'Edit Product';
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
    $this->data['categories'] = $this->category_model->get()->getResult();
    $this->data['experiences'] = $this->experience_model->get()->getResult();
    $this->data['measurements'] = $this->measurement_model->get()->getResult();
    $this->data['discount'] = $this->discount_model->where('pid', $pid)->get()->getResult();
    
   
   
    $this->data['promo'] = $this->promo_products_model->where('discounted_specific_product', $pid)->get()->getResult();
    // $uri = current_url();
    // $segments = $this->request->uri->getSegment(4);
    // $prod_spec = $this->promo_products_model->select('promo_id')
    // ->like('discounted_specific_product', $segments)
    // ->get()
    // ->getResult();
    // print_r($prod_spec);
    if(!empty($this->data['discount'])) {
      for($i = 0; $i < count($this->data['discount']); $i++) {
        
      }
    }
    if(!empty($this->data['promo'])) {
      for($i = 0; $i < count($this->data['discount']); $i++) {
        
      }
    }

    $product = $this->product_model->getProductData($pid);

    $this->data['product_data'] = $product;

    $this->data['variants_data'] = $this->product_variant_model->where('pid', $pid)->get()->getResult();

    $this->data['images'] = [];

    $imageIds = [];
    if($product->images) {
        $imageIds = explode(',',$product->images);
        $this->data['images'] = $this->image_model->whereIn('id', $imageIds)->get()->getResult();
    }
    
    $categories = $this->product_category->select('cid')->where('pid', $pid)->get()->getResult();
    $experience = $this->product_experience->select('exp_id')->where('pid', $pid)->get()->getResult();
    $assignedCat = [];
    $assignedExp = [];
    
    if($categories) {
      // print_r($categories);die();
      foreach($categories as $category) {
        $assignedCat[] = $category->cid;
      }
    }

    if($experience) {
      // print_r($categories);die();
      foreach($experience as $exps) {
        $assignedExp[] = $exps->exp_id;
      }
    }
    
    $this->data['product_categories'] = $assignedCat;
    $this->data['product_experience'] = $assignedExp;

    echo view('Admin/edit_product', $this->data);
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
  public function getProductLists($state = 'all')
  {
    
    $data  = array();
    $post = $this->request->getPost();
    // $state = (isset($_GET['state'])) ? $_GET['state'] : 'all';

    // echo print_r($state);die();

    // 1st query for counting data
    $this->product_model->select("id");

    if(isset($post['search']['value']) && !empty($post['search']['value'])) {
      $search_value = strtolower($post['search']['value']);
      $this->product_model->like("LOWER(name)", $search_value);
      $this->product_model->orLike("LOWER(url)", $search_value);
    }

    if($state == 'low-stock') {
      $this->product_model->where("stocks <= lowstock_threshold");
      $this->product_model->where("stocks > 0");
    }

    if($state == 'out-of-stock') {
      $this->product_model->where("stocks = 0");
    }

    if($state == 'archived') {
      $this->product_model->where("archived = 1");
    }

    $count_all = $this->product_model->countAllResults();

    // 2nd Query that gets all the data
    $this->product_model->select('id, name, url, stocks, archived');

    if(isset($post['search']['value']) && !empty($post['search']['value'])) {
      $search_value = strtolower($post['search']['value']);
      $this->product_model->like("LOWER(name)", $search_value);
      $this->product_model->orLike("LOWER(url)", $search_value);
    }

    if($state == 'low-stock') {
      $this->product_model->where("stocks <= lowstock_threshold");
      $this->product_model->where("stocks > 0");
    }
 
    if($state == 'out-of-stock') {
      $this->product_model->where("stocks = 0");
    }

    if($state == 'archived') {
      $this->product_model->where("archived = 1");
    }

    $this->order_model->orderBy("id ASC");

    if(isset($post['start']) && isset($post['length'])) {
      $products = $this->product_model->get($post['length'], $post['start'])->getResult();
    }
    else {
      $products = $this->product_model->get()->getResult();
    }

    $output = array(
      "draw" => $post['draw'],
      "recordsTotal" => $count_all,
      "recordsFiltered" => $count_all,
      "data" => $products,
    );

    echo json_encode($output);
    exit();
  }

  public function getPromoLists()
  {
   
    $data  = array();
    $start = $_POST['start'];
    $length = $_POST['length'];
    $uri = current_url();
    $segments = $this->request->uri->getSegment(4);

    $promo_products = "promo_products_all";
   // $promo_products1 = "promo_products_cat";
    $promo_all = $this->promo_model->select('*')
      ->like('mechanics', $promo_products)
      ->limit($length, $start)
      ->get()
      ->getResult();

  // $cat_id = $session->get('cat_id');
  // $categories = $this->category_model->get()->getResult();
  //  $data = $this->promo_products_model->get()->getResults();
  //  foreach($data as $pid){
  //   if(in_array($pid))

    $prodSpec = $this->promo_model->select('*')
    ->where('JSON_CONTAINS(mechanics, \'{"products_specific": "'.$segments.'"}\')')
    ->limit($length, $start)
    ->get()
    ->getResult();
    
     $merged = array_merge($promo_all, $prodSpec);
    // print_r($merged);
    foreach($merged as $promo_data){
        $start++;
      $data[] = array(
        $promo_data->id, 
        $promo_data->title, 
        '<a href='.base_url('admin/promotion/').' target="_blank">view</a> | <a href='.base_url('admin/promotion/edit_promo/').'>edit</a>',
      );
    }

    $output = array(
      // "draw" => $_POST['draw'],
      "recordsTotal" => $this->promo_model->countAll(),
      "recordsFiltered" => $this->promo_model->countAll(),
      "data" => $data,
    );
    
    echo json_encode($output);
    
  }
}