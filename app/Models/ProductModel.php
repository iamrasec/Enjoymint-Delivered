<?php 

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model {
  protected $table = 'products';
  protected $allowedFields = ['name', 'url', 'description', 'price', 'stocks', 'strain', 'brands', 'sku', 'unit_measure', 'unit_value',  'images', 'archived', 'tags'];
 
  public function getAllProducts() {
    $this->select('products.*, compounds.thc_unit, compounds.thc_value, compounds.cbd_unit, compounds.cbd_value, product_categories. pid');
    $this->join('compounds', 'compounds.pid = products.id', 'left');
    $this->join('product_categories', 'product_categories.pid = products.id', 'inner');
    $this->paginate(30);
   
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

  public function getDataWithParam($category = 0, $min_price = 0, $max_price = 300, $strain = 0, $brands = 0, $min_thc = 0, $max_thc = 30, $min_cbd = 0, $max_cbd = 30){
    $this->select('products.*, compounds.thc_unit, compounds.thc_value, compounds.cbd_unit, compounds.cbd_value, strains.url_slug');
    $this->join('compounds', 'compounds.pid = products.id', 'left');
    $this->join('strains', 'products.strain = strains.id', 'left');
    if($category != 0){
      $this->like('category', $category);
    }
    if($strain != 0){
      $this->orlike('strain', $strain);
    }
    if($brands != 0){
      $this->orlike('brands', $brands);
    }
    if($min_cbd != 0 && $max_cbd != 300){
      $this->where('price >=', $min_price);
      $this->where('price <=', $max_price);
    }
    if($min_thc != 0 && $max_thc != 30){
      $this->where('thc_value >=', $min_thc);
      $this->where('thc_value <=', $max_thc);
    }
    if($min_cbd != 0 && $max_cbd != 30){
      $this->where('cbd_value >=', $min_cbd);
      $this->where('cbd_value <=', $max_cbd);
  } 
    return $this->paginate(30);

  }
}