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
    $this->select('products.*, compounds.thc_unit, compounds.thc_value, compounds.cbd_unit, compounds.cbd_value');
    $this->join('compounds', 'compounds.pid = products.id', 'left');
    $this->where('products.id', $pid);
    return $this->get()->getResult()[0];
  }
  
  public function getProductFromUrl($url) {
    $this->select('products.*, compounds.thc_unit, compounds.thc_value, compounds.cbd_unit, compounds.cbd_value, strains.name AS strain_name, strains.url_slug AS strain_url');
    $this->join('strains', 'strains.id = products.strain', 'inner');
    $this->join('compounds', 'compounds.pid = products.id', 'left');
    $this->where('products.url', $url);
    return $this->get()->getResult();
  }

  public function getDataWithParam($category = 0, $price = 0, $strain = 0, $brands = 0, $thc_value = 0, $cbd_value = 0){
    $this->select('products.*, compounds.thc_unit, compounds.thc_value, compounds.cbd_unit, compounds.cbd_value');
    $this->join('compounds', 'compounds.pid = products.id', 'left');
    if($category != 0){
      $this->like('category', $category);
    }
    if($price != 0){
      $this->orlike('price', $price);
    }
    if($strain != 0){
      $this->orlike('strain', $strain);
    }
    if($brands != 0){
      $this->orlike('brands', $brands);
    }
    if($thc_value != 0){
      $this->orlike('thc_value', $thc_value);
    }
    if($cbd_value != 0){
      $this->orlike('cbd_value', $cbd_value);
    } 
    // $result = $this->get();
    // return $result->getResult();
    return $this->paginate(30);

  }
}