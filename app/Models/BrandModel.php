<?php 

namespace App\Models;

use CodeIgniter\Model;

class BrandModel extends Model {
  protected $table = 'brands';
  protected $allowedFields = ['name', 'url'];


  public function brandGetProducts($id) {
    $this->select('products.*, compounds.thc_unit, compounds.thc_value, compounds.cbd_unit, compounds.cbd_value');
    $this->join('brands', 'brands.id = products.brands', 'left');
    $this->join('compounds', 'compounds.pid = products.id', 'left');
    $this->where('brands.id', $id);
    return $this->get()->getResult();
  }
}