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
    
    // $assignedCat = [];

    // if(!empty($mechanics)) {
    //   // print_r($categories);die();
    //   foreach($mechanics as $mechanic_cat) {
    //     $assignedCat[] = $mechanic_cat->products_cat[0]->text;
    //   }
    // }

    // $this->data['product_categories'] = $assignedCat;
    $this->data['update_data'] = $mechanics;  
    $this->data['promo_data'] = $promo;
    $this->data['all_products'] = $this->product_model->getAllProductsNoPaginate();
    $this->data['all_req_products'] = $this->product_model->getAllProductsNoPaginate();
    $this->data['all_categories'] = $this->category_model->get()->getResult();
    $this->data['all_req_categories'] = $this->category_model->get()->getResult();

    // $this->data['blog_data'] = $this->blog_model->getBlogbyID($id);
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
        '<a href='.base_url('admin/promotion/promoProdLists/'.$promo->id).' target="_blank">view</a> | <a href='.base_url('admin/promotion/edit_promotion/'. $promo->id).'>edit</a>',
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

  public function promoProdLists($id)
  {
    $session = session();
    $page_title = 'Promo Product Page';
    $this->data['page_body_id'] = "promo product page";
    $this->data['breadcrumbs'] = [
      'parent' => [
        ['parent_url' => base_url('/admin/promotion'), 'page_title' => 'Promo Product Page'],
      ],
      'current' => $page_title,
    ];
    $this->data['page_title'] = $page_title;
    $this->data['data1'] = $this->promo_model->where('id', $id)->get()->getResult();
    $data1 = $this->promo_model->where('id', $id)->get()->getResult();
    $this->data['data2'] = json_decode($data1[0]->mechanics);
    if($this->data['data2'][0]->promo_products == "promo_products_all") {
           $this->data['checked'] = "checked";
           $this->data['checked1'] = "";
           $this->data['checked2'] = "";
    }elseif($this->data['data2'][0]->promo_products == "promo_products_specific"){
        $this->data['checked'] = "";
        $this->data['checked1'] = "checked";
        $this->data['checked2'] = "";
    }else{
      $this->data['checked'] = "";
      $this->data['checked1'] = "";
      $this->data['checked2'] = "checked";
    }
    if($this->data['data2'][0]->req_purchase == 1){
      $this->data['req'] = "1";
    }else{
      $this->data['req'] = "0";
    }
    $product_list = $this->promo_products_model->where('promo_id', $id)->get()->getResult();
    // print_r($product_list);
    // $data = explode($product_list)
    if(!empty($product_list)){
     if($product_list[0]->promo_product == "promo_products_all"){
         $data = $this->product_model->paginate();
     }elseif($product_list[0]->promo_product == "promo_products_specific"){
        $delimiter = ",";
         $data_exp = explode($delimiter, $product_list[0]->discounted_specific_product);
         if($data_exp > 1){
         foreach($data_exp as $id){
            $data[] = $this->product_model->where('id', $id)->get()->getResult();
         }
        }else{
          $data = $this->product_model->where('id', $id)->get()->getResult();
        }
     }elseif($product_list[0]->promo_product == "promo_products_cat"){
        $data_cat = $this->product_category->select('pid')->where('cid', $product_list[0]->discounted_category_id)->get()->getResult();
        if($data_cat > 1){
        foreach($data_cat as $category){
          $data[] = $this->product_model->where('id', $category->pid)->get()->getResult();
        }
        }else{
          $data = $this->product_model->where('id', $category->pid)->get()->getResult();
        }
     }
     $this->data['product_data'] = $data;
     $this->data['pager'] = $this->product_model->paginate();
    }else{
      $this->data['product_data'] = null;
    }

    //  print_r($data);
    return view('Promotions/promo_product_list', $this->data);
  }


}