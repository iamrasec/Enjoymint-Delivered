<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;

class Discount extends BaseController {

  public function __construct() {
    helper(['jwt']);

    $this->data = [];
    $this->role = session()->get('role');
    $this->isLoggedIn = session()->get('isLoggedIn');
    $this->guid = session()->get('guid');
    
    $this->promo_model = model('PromoModel');
    $this->promo_products_model = model('PromoProductsModel');

    $this->product_model = model('ProductModel');
    $this->strain_model = model('StrainModel');
    $this->brand_model = model('BrandModel');
    $this->measurement_model = model('MeasurementModel');
    $this->image_model = model('ImageModel');
    $this->product_variant_model = model('ProductVariantModel');
    $this->category_model = model('CategoryModel');
    $this->product_category = model('ProductCategory');
    $this->product_experience = model('ProductExperience');
    $this->compound_model = model('CompoundModel');
    $this->discount_model = model('DiscountModel');
    
    $this->data['user_jwt'] = getSignedJWTForUser($this->guid);

    if($this->isLoggedIn !== 1 && $this->role !== 1) {
      return redirect()->to('/');
    }
    
  }

  public function index() {
    $page_title = 'On Sale Products';

    $this->data['page_body_id'] = "on_sale_list";
    $this->data['breadcrumbs'] = [
      'parent' => [],
      'current' => $page_title,
    ];
    $this->data['page_title'] = $page_title;
    $this->data['all_products'] = $this->product_model->where('on_sale', 1)->get()->getResult();
    return view('Admin/product_discount', $this->data);
  }

  public function add_promo() {
    $page_title = 'Add Promotion';

    $this->data['page_body_id'] = "add_promo";
    $this->data['breadcrumbs'] = [
      'parent' => [],
      'current' => $page_title,
    ];
    $this->data['page_title'] = $page_title;

    

    // echo "<pre>".print_r($this->data)."</pre>"; die();
    
    return view('Promotions/admin_add_promo', $this->data);
  }


  public function getProdList()
  {
    $data  = array();
    $start = $_POST['start'];
    $length = $_POST['length'];

    $products = $this->product_model->select('*')
      ->where('on_sale', 1)
      ->like('title',$_POST['search']['value'])
      ->orLike('url',$_POST['search']['value'])
      ->limit($length, $start)
      ->get()
      ->getResult();
    print($products);
    foreach($products as $prod){
      $start++;
      $data[] = array(
        $prod->id,
        $prod->name, 
        $prod->url,
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