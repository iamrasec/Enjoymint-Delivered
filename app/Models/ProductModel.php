<?php 

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model {
  protected $table = 'products';
  protected $allowedFields = ['name', 'url', 'description', 'price', 'stocks', 'strain', 'brands', 'sku', 'unit_measure', 'unit_value', 'has_variant', 'images', 'archived', 'tags', 'delivery_type', 'views', 'orders', 'lowstock_threshold'];
 
  public function getAllProducts() {
    $this->select('products.*, compounds.thc_unit, compounds.thc_value, compounds.cbd_unit, compounds.cbd_value, strains.name AS strain_name, strains.url_slug AS strain_url');
    $this->join('strains', 'strains.id = products.strain', 'left');
    $this->join('compounds', 'compounds.pid = products.id', 'left');
    // $this->orderBy('id', 'ASC');
    return $this->paginate(28);
  }
  public function getProducts($search) {
    $this->select('products.*, compounds.thc_unit, compounds.thc_value, compounds.cbd_unit, compounds.cbd_value, strains.name AS strain_name, strains.url_slug AS strain_url');
    $this->join('strains', 'strains.id = products.strain', 'left');
    $this->join('compounds', 'compounds.pid = products.id', 'left');
    $this->like('products.name',$search);
    $this->orlike('products.price', $search);
    return $this->paginate(28);
  }

  public function getAllProductsNoPaginate($sort="none") {
    $this->select('products.*, compounds.thc_unit, compounds.thc_value, compounds.cbd_unit, compounds.cbd_value, strains.name AS strain_name, strains.url_slug AS strain_url');
    $this->join('strains', 'strains.id = products.strain', 'left');
    $this->join('compounds', 'compounds.pid = products.id', 'left');

    if($sort == 'asc') {
      $this->orderBy('products.name', 'ASC');
    }
    elseif($sort == 'desc') {
      $this->orderBy('products.name', 'DESC');
    }

    return $this->get()->getResult();
  }

  public function getFastTracked() {
    $this->select('products.*, compounds.thc_unit, compounds.thc_value, compounds.cbd_unit, compounds.cbd_value, strains.name AS strain_name, strains.url_slug AS strain_url');
    $this->join('strains', 'strains.id = products.strain', 'left');
    $this->join('compounds', 'compounds.pid = products.id', 'left');
    $this->where('products.delivery_type', 2);
    return $this->paginate(28);
  } 

  public function getProductData($pid) {
    $this->select('products.*, compounds.thc_unit, compounds.thc_value, compounds.cbd_unit, compounds.cbd_value, strains.name AS strain_name, strains.url_slug AS strain_url');
    $this->join('strains', 'strains.id = products.strain', 'left');
    $this->join('compounds', 'compounds.pid = products.id', 'left');
    $this->where('products.id', $pid);
    return $this->get()->getResult()[0];
  }
  
  public function getProductFromUrl($url) {
    $this->select('products.*, compounds.thc_unit, compounds.thc_value, compounds.cbd_unit, compounds.cbd_value, strains.name AS strain_name, strains.url_slug AS strain_url');
    $this->join('strains', 'strains.id = products.strain', 'left');
    $this->join('compounds', 'compounds.pid = products.id', 'left');
    $this->where('products.url', $url);
    return $this->get()->getResult();
  }

  public function getPopularProducts($limit=4) {
    $this->select('products.*, compounds.thc_unit, compounds.thc_value, compounds.cbd_unit, compounds.cbd_value, strains.name AS strain_name, strains.url_slug AS strain_url');
    $this->join('strains', 'strains.id = products.strain', 'left');
    $this->join('compounds', 'compounds.pid = products.id', 'left');
    $this->where('products.stocks > 0');
    $this->orderBy('products.orders', 'DESC');
    $this->orderBy('products.views', 'DESC');
    $this->limit($limit);
    return $this->get()->getResultArray();
  }

  public function incrementViews($pid) {
    $this->where("id", $pid);
    $this->set('views', '(views + 1)', FALSE);
    return $this->update();
  }

  public function incrementOrders($pid) {
    $this->where("id", $pid);
    $this->set('orders', '(orders + 1)', FALSE);
    return $this->update();
  }
  
  public function getDataWithParam($category = 0, $min_price = 0, $max_price = 0, $strain = 0, $brands = 0, $min_thc = 0, $max_thc = 0, $min_cbd = 0, $max_cbd = 0, $availability = 0){
    $this->select('products.*, compounds.thc_unit, compounds.thc_value, compounds.cbd_unit, compounds.cbd_value, strains.url_slug, product_categories.cid');
    $this->join('compounds', 'compounds.pid = products.id', 'left');
    $this->join('product_categories', 'product_categories.pid = products.id', 'left');
    $this->join('strains', 'products.strain = strains.id', 'left');
    $this->where('products.delivery_type', 1);
    // Add Category filter if $category is greater than 0
    if($category > 0) {
      // $this->like('cid', $category);
      $this->where('product_categories.cid', $category);
      $this->where('products.delivery_type', 1);
    }

    // Add Strain Type filter if $strain is greater than 0
    if($strain > 0) {
      // $this->like('strain', $strain);
      $this->where('products.strain', $strain);
      $this->where('products.delivery_type', 1);
    }

    // Add Brand filter if $brands is greater than 0
    if($brands > 0) {
      // $this->like('brands', $brands);
      $this->where('products.brands', $brands);
      $this->where('products.delivery_type', 1);
    }

    if($min_price != 0) {
      $this->where('products.price >=', $min_price);
      $this->where('products.delivery_type', 1);
    }

    if($max_price != 0) {
      $this->where('products.price <=', $max_price);
      $this->where('products.delivery_type', 1);
    }
    
    if($min_thc != 0 || $max_thc != 0) {
      $this->where('compounds.thc_value >=', $min_thc);
      $this->where('compounds.thc_value <=', $max_thc);
      $this->where('products.delivery_type', 1);
    }
    
    if($min_cbd != 0 || $max_cbd != 0) {
      $this->where('compounds.cbd_value >=', $min_cbd);
      $this->where('compounds.cbd_value <=', $max_cbd);
      $this->where('products.delivery_type', 1);
    } 

    if($availability == 1) {
      $this->where('products.delivery_type', 1);
    }

    return $this->paginate(28);

  }

  public function getDataWithParamFast_Tracked($category = 0, $min_price = 0, $max_price = 0, $strain = 0, $brands = 0, $min_thc = 0, $max_thc = 0, $min_cbd = 0, $max_cbd = 0, $availability = 0){
    $this->select('products.*, compounds.thc_unit, compounds.thc_value, compounds.cbd_unit, compounds.cbd_value, strains.url_slug, product_categories.cid');
    $this->join('compounds', 'compounds.pid = products.id', 'left');
    $this->join('product_categories', 'product_categories.pid = products.id', 'left');
    $this->join('strains', 'products.strain = strains.id', 'left');
    $this->where('products.delivery_type', 2);
    // Add Category filter if $category is greater than 0
    if($category > 0) {
      // $this->like('cid', $category);   
      $this->where('product_categories.cid', $category);
      $this->where('products.delivery_type', 2);
    }
      
    // Add Strain Type filter if $strain is greater than 0
    if($strain > 0) {
      // $this->like('strain', $strain);
      $this->where('products.strain', $strain);
      $this->where('products.delivery_type', 2);
    }

    // Add Brand filter if $brands is greater than 0
    if($brands > 0) {
      // $this->like('brands', $brands);
      $this->where('products.brands', $brands);
      $this->where('products.delivery_type', 2);
    }

    if($min_price != 0) {
      $this->where('products.price >=', $min_price);
      $this->where('products.delivery_type', 2);
    }

    if($max_price != 0) {
      $this->where('products.price <=', $max_price);
      $this->where('products.delivery_type', 2);
    }
    
    if($min_thc != 0 || $max_thc != 0) {
      $this->where('compounds.thc_value >=', $min_thc);
      $this->where('compounds.thc_value <=', $max_thc);
      $this->where('products.delivery_type', 2);
    }
    
    if($min_cbd != 0 || $max_cbd != 0) {
      $this->where('compounds.cbd_value >=', $min_cbd);
      $this->where('compounds.cbd_value <=', $max_cbd);
      $this->where('products.delivery_type', 2);
    } 

    if($availability == 2) {
      $this->where('products.delivery_type', 2);
    }

    return $this->paginate(28);

  }

}