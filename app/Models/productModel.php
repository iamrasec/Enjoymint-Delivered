<?php 

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model {
  protected $table = 'products';
  protected $allowedFields = ['name', 'url', 'description', 'stocks', 'strain', 'brands', 'sku', 'images'];
 
  public function getAllProducts() {
    $this->select('products.*, compounds.thc_pct, compounds.thc_pct, compounds.thc_mg, compounds.cbd_pct, compounds.cbd_mg');
    $this->join('compounds', 'compounds.pid = products.id', 'left');
    return $this->get()->getResult();
  }

  public function getProductCategories($pid) {
    
  }
}