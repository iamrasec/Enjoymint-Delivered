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
    $this->data['all_req_products'] = $this->product_model->getAllProductsNoPaginate();
    $this->data['all_categories'] = $this->category_model->get()->getResult();
    $this->data['all_req_categories'] = $this->category_model->get()->getResult();

    // echo "<pre>".print_r($this->data)."</pre>"; die();
    
    return view('Promotions/admin_add_promo', $this->data);
  }

  public function edit_promotion($id){
    $page_title = 'Edit Promo';
    $this->data['page_body_id'] = "edit_promo";
    $this->data['breadcrumbs'] = [
      'parent' => [
        ['parent_url' => base_url('/admin/promotion'), 'page_title' => 'Promotion'],
      ],
      'current' => $page_title,
    ];
    $this->data['page_title'] = $page_title;
    $promo = $this->promo_model->where('id', $id)->get()->getResult();
    $mechanics = json_decode($promo[0]->mechanics);
    print_r($mechanics[0]->products_cat);
    // foreach($promo as $update){
    //   // initialize images
    //   $update_data [] = [
    //   'mechanics' => $update->mechanics,
    //   ];
    // }
    // $promo_decode = json_decode($promo['mechanics'], true);
    // $imageIds = [];

    // for($i = 0; $i < count($blog); $i++) {
    //   if($blog[$i]->images != null || $blog[$i]->images != '') {
    //     $imageIds = explode(',',$blog[$i]->images);
    //     $blog[$i]->images = $this->image_model->whereIn('id', $imageIds)->get()->getResult();
    //   }
    // }

    // $categories = $this->product_category->select('cid')->where('pid', $id)->get()->getResult();
    
    $assignedCat = [];

    if(!empty($mechanics)) {
      // print_r($categories);die();
      foreach($mechanics as $mechanic_cat) {
        $assignedCat[] = $mechanic_cat->products_cat[0]->text;
      }
    }

    $this->data['product_categories'] = $assignedCat;
    $this->data['update_data'] = $mechanics;  
    $this->data['promo_data'] = $promo;
    $this->data['all_products'] = $this->product_model->getAllProductsNoPaginate();
    $this->data['all_req_products'] = $this->product_model->getAllProductsNoPaginate();
    $this->data['all_categories'] = $this->category_model->get()->getResult();
    $this->data['all_req_categories'] = $this->category_model->get()->getResult();

    // $this->data['blog_data'] = $this->blog_model->getBlogbyID($id);
     print_r($this->data['product_categories']);
    return view('Promotions/edit_promotion', $this->data);
  }

  public function getPromoList()
  {
    $data  = array();
    $start = $_POST['start'];
    $length = $_POST['length'];

    $promotion = $this->promo_model->select('*')
      ->like('title',$_POST['search']['value'])
      ->orLike('url',$_POST['search']['value'])
      ->limit($length, $start)
      ->get()
      ->getResult();
   
    foreach($promotion as $promo){
      $start++;
      $data[] = array(
        $promo->id,
        $promo->title, 
        $promo->url,
        $promo->description,
        $promo->promo_type,
        $promo->start_date,
        $promo->end_date,
        $promo->status,     
        "<a href=".base_url('admin/promotion/edit_promotion/'.$promo->id).">edit</a>",
      );
    }

    $output = array(
      "draw" => $_POST['draw'],
      "recordsTotal" => $this->promo_model->countAll(),
      "recordsFiltered" => $this->promo_model->countAll(),
      "data" => $data,
    );
    
    echo json_encode($output);

  }

}