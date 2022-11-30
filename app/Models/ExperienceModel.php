<?php 

namespace App\Models;

use CodeIgniter\Model;

class ExperienceModel extends Model {
  protected $table = 'experience';
  protected $allowedFields = ['name', 'url'];

/** 
   * Get Products in a Particular Experience
   * 
   */
 public function experienceGetAllProduct($exp_id){
  $this->select('products.*, compounds.thc_unit, compounds.thc_value, compounds.cbd_unit, compounds.cbd_value, experience.url');
  $this->join('product_experience', 'product_experience.exp_id = experience.id', 'inner');
  $this->join('products', 'products.id = product_experience.pid', 'inner');
  $this->join('compounds', 'compounds.pid = products.id', 'left');
  $this->where('experience.id', $exp_id);
  return $this->get()->getResult();

 }

 public function experienceGetProductsPaginate($exp_id) {
  $this->select('products.*, compounds.thc_unit, compounds.thc_value, compounds.cbd_unit, compounds.cbd_value');
  $this->join('product_experience', 'product_experience.exp_id = experience.id', 'inner');
  $this->join('products', 'products.id = product_experience.pid', 'inner');
  $this->join('compounds', 'compounds.pid = products.id', 'left');
  $this->where('experience.id', $exp_id);
  return $this->paginate(28);

}
public function experienceGetProducts($exp_id, $search){
  $this->select('products.*, compounds.thc_unit, compounds.thc_value, compounds.cbd_unit, compounds.cbd_value, strains.name AS strain_name, strains.url_slug AS strain_url');
  $this->join('product_experience', 'product_experience.exp_id = experience.id', 'inner');
  $this->join('products', 'products.id = product_experience.pid', 'inner');
  $this->join('strains', 'strains.id = products.strain', 'left');
  $this->join('compounds', 'compounds.pid = products.id', 'left');
  $this->where('experience.id', $exp_id); 
  $this->like('products.name', $search);
  $this->orlike('products.price', $search);
  return $this->paginate(28);
}

public function getDataWithParam($exp_id, $category = 0, $min_price = 0, $max_price = 0, $strain = 0, $brands = 0, $min_thc = 0, $max_thc = 0, $min_cbd = 0, $max_cbd = 0, $availability = 0){
  $this->select('products.*, compounds.thc_unit, compounds.thc_value, compounds.cbd_unit, compounds.cbd_value, strains.url_slug, product_categories.cid');
  $this->join('product_experience', 'product_experience.exp_id = experience.id', 'left');
  $this->join('products', 'products.id = product_experience.pid', 'left');
  $this->join('compounds', 'compounds.pid = products.id', 'left');
  $this->join('product_categories', 'product_categories.pid = products.id', 'left');
  // $this->join('categories','categories.id = product_categories.pid');
  $this->join('strains', 'products.strain = strains.id', 'left');
  $this->where('experience.id', $exp_id);
  // Add Category filter if $category is greater than 0
  if($category != 0) {
    $this->where('product_categories.cid', $category);
    
  }

  // Add Strain Type filter if $strain is greater than 0
  if($strain != 0) {
    $this->where('products.strain', $strain);
    
  }

  // Add Brand filter if $brands is greater than 0
  if($brands != 0) {
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
    // $this->like('compounds.cbd_value', $min_cbd);
    // $this->orlike('compounds.cbd_value', $max_cbd);
    $this->where('compounds.cbd_value >=', $min_cbd);
    $this->where('compounds.cbd_value <=', $max_cbd);
      
  } 

  if($availability == 2) {
    $this->where('products.delivery_type', 2);
  }
  
  return $this->paginate(28);

}

}