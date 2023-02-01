<?php
namespace App\Controllers\Admin;
use App\Controllers\BaseController;

class Promotion extends BaseController {

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
    
    $this->data['user_jwt'] = getSignedJWTForUser($this->guid);

    if($this->isLoggedIn !== 1 && $this->role !== 1) {
      return redirect()->to('/');
    }
    
  }

  public function index() {
    $page_title = 'Promotions List';

    $this->data['page_body_id'] = "promo_list";
    $this->data['breadcrumbs'] = [
      'parent' => [],
      'current' => $page_title,
    ];
    $this->data['page_title'] = $page_title;
    $this->data['promotions'] = $this->promo_model->get()->getResult();
    return view('Promotions/admin_list_page', $this->data);
  }

  public function add_promo() {
    $page_title = 'Add Promotion';

    $this->data['page_body_id'] = "add_promo";
    $this->data['breadcrumbs'] = [
      'parent' => [],
      'current' => $page_title,
    ];
    $this->data['page_title'] = $page_title;
    $this->data['all_products'] = $this->product_model->getAllProductsNoPaginate();
    $this->data['all_categories'] = $this->category_model->get()->getResult();

    // echo "<pre>".print_r($this->data)."</pre>"; die();
    
    return view('Promotions/admin_add_promo', $this->data);
  }

}