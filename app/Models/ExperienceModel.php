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
}