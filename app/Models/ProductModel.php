<?php 

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model {
  protected $table = 'products';
  protected $allowedFields = ['name', 'url', 'description', 'price', 'stocks', 'strain', 'brands', 'sku', 'unit_measure', 'unit_value',  'images', 'archived', 'tags', 'delivery_type'];
 
  public function getAllProducts() {
    $this->select('products.*, compounds.thc_unit, compounds.thc_value, compounds.cbd_unit, compounds.cbd_value, strains.name AS strain_name, strains.url_slug AS strain_url');
    $this->join('strains', 'strains.id = products.strain', 'left');
    $this->join('compounds', 'compounds.pid = products.id', 'left');
    return $this->paginate(28);
  }

  public function getAllProductsNoPaginate() {
    $this->select('products.*, compounds.thc_unit, compounds.thc_value, compounds.cbd_unit, compounds.cbd_value, strains.name AS strain_name, strains.url_slug AS strain_url');
    $this->join('strains', 'strains.id = products.strain', 'left');
    $this->join('compounds', 'compounds.pid = products.id', 'left');
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

  public function getDataWithParam($category = 0, $min_price = 0, $max_price = 0, $strain = 0, $brands = 0, $min_thc = 0, $max_thc = 0, $min_cbd = 0, $max_cbd = 0, $fast_tracked = false){
    $this->select('products.*, compounds.thc_unit, compounds.thc_value, compounds.cbd_unit, compounds.cbd_value, strains.url_slug, product_categories.cid');
    $this->join('compounds', 'compounds.pid = products.id', 'left');
    $this->join('product_categories', 'product_categories.pid = products.id', 'left');
    $this->join('strains', 'products.strain = strains.id', 'left');
    
    // Add Category filter if $category is greater than 0
    if($category > 0) {
      // $this->like('cid', $category);
      $this->where('product_categories.cid', $category);
    }

    // Add Strain Type filter if $strain is greater than 0
    if($strain > 0) {
      // $this->like('strain', $strain);
      $this->where('products.strain', $strain);
    }

    // Add Brand filter if $brands is greater than 0
    if($brands > 0) {
      // $this->like('brands', $brands);
      $this->where('products.brands', $brands);
    }

    if($min_price != 0) {
      $this->where('products.price >=', $min_price);
    }

    if($max_price != 0) {
      $this->where('products.price <=', $max_price);
    }
    
    if($min_thc != 0 || $max_thc != 0) {
      $this->where('compounds.thc_value >=', $min_thc);
      $this->where('compounds.thc_value <=', $max_thc);
    }
    
    if($min_cbd != 0 || $max_cbd != 0) {
      $this->where('compounds.cbd_value >=', $min_cbd);
      $this->where('compounds.cbd_value <=', $max_cbd);
    } 

    if($fast_tracked == true) {
      $this->where('products.delivery_type', 2);
    }

    return $this->paginate(28);

  }

}